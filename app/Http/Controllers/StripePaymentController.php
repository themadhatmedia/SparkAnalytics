<?php


namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Http\RedirectResponse;

class StripePaymentController extends Controller
{
    public $currancy;
    public $currancy_symbol;

    public $stripe_secret;
    public $stripe_key;
    // public $stripe_webhook_secret;



    public function index()
    {
        $objUser = \Auth::user();
        if (\Auth::user()->user_type == 'super admin') {
            $orders  = Order::select(
                [
                    'Orders.*',
                    'users.name as user_name',
                ]
            )->join('users', 'Orders.user_id', '=', 'users.id')->orderBy('Orders.created_at', 'DESC')->get();

            $userOrders = Order::select('*')
                ->whereIn('id', function ($query) {
                    $query->selectRaw('MAX(id)')
                        ->from('Orders')
                        ->groupBy('user_id');
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return view('order.index', compact('orders', 'userOrders'));
        } elseif (\Auth::user()->user_type == 'company') {
            $objUser = \Auth::user();
            $orders  = Order::select(
                [
                    'Orders.*',
                    'users.name as user_name',
                ]
            )->join('users', 'Orders.user_id', '=', 'users.id')->where('user_id', $objUser->id)->orderBy('Orders.created_at', 'DESC')->get();

            return view('order.index', compact('orders'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function refund(Request $request, $id, $user_id)
    {
        Order::where('id', $request->id)->update(['is_refund' => 1]);

        $user = User::find($user_id);

        $assignPlan = $this->assignPlan(1, $user->id);

        return redirect()->back()->with('success', __('We successfully planned a refund and assigned a free plan.'));
    }

    public function stripePost(Request $request)
    {
        $this->planpaymentSetting();

        $objUser = \Auth::user();
        $planID  = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan    = Plan::find($planID);

        if ($plan) {
            try {

                $price = (float)$plan->{$request->stripe_payment_frequency . '_price'};

                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun     = $coupons->used_coupon();
                        $discount_value = ($plan->{$request->stripe_payment_frequency . '_price'} / 100) * $coupons->discount;
                        $price          = $plan->{$request->stripe_payment_frequency . '_price'} - $discount_value;

                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                if ($price > 0.0) {
                    // try
                    // {

                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    $authuser     = Auth::user();
                    $payment_plan = $payment_frequency = $request->payment_frequency;
                    $payment_type = $request->payment_type;

                    /* Payment details */
                    $code = '';




                    if (isset($request->coupon) && !empty($request->coupon) && $plan->discounted_price) {
                        $price = $plan->discounted_price;
                        $code  = $request->coupon;
                    }

                    $product = $plan->name;

                    /* Final price */
                    $stripe_formatted_price = in_array(
                        $this->currancy,
                        [
                            'MGA',
                            'BIF',
                            'CLP',
                            'PYG',
                            'DJF',
                            'RWF',
                            'GNF',
                            'UGX',
                            'JPY',
                            'VND',
                            'VUV',
                            'XAF',
                            'KMF',
                            'KRW',
                            'XOF',
                            'XPF',
                        ]
                    ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

                    $return_url_parameters = function ($return_type) {
                        return '&return_type=' . $return_type . '&payment_processor=stripe';
                    };

                    /* Initiate Stripe */
                    \Stripe\Stripe::setApiKey($this->stripe_secret);



                    $stripe_session = \Stripe\Checkout\Session::create(
                        [
                            'payment_method_types' => ['card'],
                            'line_items' => [
                                [
                                    'name' => $product,
                                    'description' => $payment_plan,
                                    'amount' => $stripe_formatted_price,
                                    'currency' => $this->currancy,
                                    'quantity' => 1,
                                ],
                            ],
                            'metadata' => [
                                'user_id' => $authuser->id,
                                'package_id' => $plan->id,
                                'payment_frequency' => $payment_frequency,
                                'code' => $code,
                            ],
                            'success_url' => route(
                                'stripe.payment.status',
                                [
                                    'plan_id' => $plan->id,
                                    'frequency' => $request->stripe_payment_frequency,
                                    'currency' => $this->currancy,
                                    'amount' => $price,
                                    'coupon_id' => $coupons,
                                    $return_url_parameters('success'),
                                ]
                            ),
                            'cancel_url' => route(
                                'stripe.payment.status',
                                [
                                    'plan_id' => $plan->id,  'currency' => $this->currancy,
                                    'amount' => $price,
                                    'frequency' => $request->stripe_payment_frequency,
                                    'coupon_id' => $coupons,
                                    $return_url_parameters('cancel'),
                                ]
                            ),
                        ]
                    );

                    $stripe_session = $stripe_session ?? false;


                    try {
                        return new RedirectResponse($stripe_session->url);
                    } catch (\Exception $e) {
                        return redirect()->route('plans')->with('error', __('Transaction has been failed!'));
                    }
                    // }
                    // catch(\Exception $e)
                    // {
                    //     \Log::debug($e->getMessage());
                    // }
                } else {

                    Order::create(
                        [
                            'order_id' => $orderID,
                            'name' => $request->name,
                            'card_number' => '',
                            'card_exp_month' => '',
                            'card_exp_year' => '',
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $price,
                            'price_currency' => $this->currancy,
                            'txn_id' => '',
                            'payment_type' => __('STRIPE'),
                            'payment_status' => isset($data['status']) ? $data['status'] : 'succeeded',
                            'receipt' => 'free coupon',
                            'user_id' => $objUser->id,
                        ]
                    );
                    if (!empty($request->coupon)) {
                        $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                        $userCoupon         = new UserCoupon();
                        $userCoupon->user   = $objUser->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order  = $orderID;
                        $userCoupon->save();

                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit <= $usedCoupun) {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                    return redirect()->route('plans')->with('success', __('Plan Successfully Activated.'));
                }

                }



                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

                    $userCoupon         = new UserCoupon();
                    $userCoupon->user   = $objUser->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order  = $orderID;
                    $userCoupon->save();

                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit <= $usedCoupun) {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                }
            } catch (\Exception $e) {
                return redirect()->route('plans')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->route('plans')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetStripePaymentStatus(Request $request)
    {
        $objUser = \Auth::user();
        $this->planpaymentSetting();
        $plan = Plan::find($request->plan_id);

        Session::forget('stripe_session');

        try {
            if ($request->return_type == 'success') {
                $objUser                    = \Auth::user();

                $assignPlan = $this->assignPlan($request->plan_id,$objUser->id, $request->frequency);
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                if ($request->has('coupon_id') && $request->coupon_id != '') {
                    $coupons = Coupon::find($request->coupon_id);
                    if (!empty($coupons)) {
                        $userCoupon         = new UserCoupon();
                        $userCoupon->user   = $objUser->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order  = $orderID;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                    }
                }


                Order::create(
                    [
                        'order_id' => $orderID,
                        'name' => $objUser->name,
                        'card_number' => '',
                        'card_exp_month' => '',
                        'card_exp_year' => '',
                        'plan_name' => $plan->name,
                        'plan_id' => $plan->id,
                        'price' =>  $request->amount,
                        'price_currency' => $this->currancy,
                        'txn_id' => '',
                        'payment_type' => 'STRIPE',
                        'payment_status' => $request->return_type,
                        'receipt' => '',
                        'user_id' => $objUser->id,
                    ]
                );

                Utility::add_referal_settings($plan);

                if ($assignPlan['is_success']) {

                    return redirect()->route('plans')->with('success', __('Plan successfully activated.'));
                } else {
                    return redirect()->route('plans')->with('error', __($assignPlan['error']));
                }
            } else {
                return redirect()->route('plans')->with('error', __('Your Payment has failed!'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('plans')->with('error', __('Something went wrong.'));
        }
    }



    public function planpaymentSetting()
    {


        $admin_payment_setting = Utility::getAdminPaymentSetting();

        $this->currancy_symbol = isset($admin_payment_setting['currency_symbol']) ? $admin_payment_setting['currency_symbol'] : '';
        $this->currancy = isset($admin_payment_setting['currency']) ? $admin_payment_setting['currency'] : '';

        $this->stripe_secret = isset($admin_payment_setting['stripe_secret']) ? $admin_payment_setting['stripe_secret'] : '';
        $this->stripe_key = isset($admin_payment_setting['stripe_key']) ? $admin_payment_setting['stripe_key'] : '';
    }
}
