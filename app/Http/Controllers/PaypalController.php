<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    public $currancy;
    public $currancy_symbol;
    protected $invoiceData;
    public function paymentConfig()
    {
        if (\Auth::check()) {
            $payment_setting = Utility::getAdminPaymentSetting();
        } else {
            $payment_setting = Utility::getCompanyPaymentSetting($this->invoiceData);
        }

        if ($payment_setting['paypal_mode'] == 'live') {
            config([
                'paypal.live.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                'paypal.live.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
            ]);
        } else {
            config([
                'paypal.sandbox.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                'paypal.sandbox.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
            ]);
        }
    }
    public function planPayWithPaypal(Request $request)
    {

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan   = Plan::find($planID);

        $this->paymentConfig();
        $payment_setting = Utility::getAdminPaymentSetting();
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $get_amount = (float)$plan->{$request->paypal_payment_frequency . '_price'};

        if ($plan) {
            try {
                $coupon_id = $request->coupon;
                $price     = $get_amount;
                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun     = $coupons->used_coupon();
                        $discount_value = ($get_amount / 100) * $coupons->discount;
                        $price          = $get_amount - $discount_value;
                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                        $coupon_id = $coupons->id;
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
                if ($price > 0.0) {
                    $paypalToken = $provider->getAccessToken();
                    $response = $provider->createOrder([
                        "intent" => "CAPTURE",
                        "application_context" => [
                            "return_url" => route('plan.get.payment.status', [$plan->id, 'coupon_id' => $coupon_id, 'amount' => $price, 'frequency' => $request->paypal_payment_frequency]),
                            "cancel_url" =>  route('plan.get.payment.status', [$plan->id, 'coupon_id' => $coupon_id, 'amount' => $price, 'frequency' => $request->paypal_payment_frequency]),
                        ],
                        "purchase_units" => [
                            0 => [
                                "amount" => [
                                    "currency_code" => $payment_setting['currency'],
                                    "value" => $price
                                ]
                            ]
                        ]
                    ]);

                    if (isset($response['id']) && $response['id'] != null) {
                        
                        // redirect to approve href
                        foreach ($response['links'] as $links) {
                            if ($links['rel'] == 'approve') {
                                return redirect()->away($links['href']);
                            }
                        }
                        return redirect()
                            ->route('plans')
                            ->with('error', 'Something went wrong.');
                    } else {

                        return redirect()
                            ->route('plans')
                            ->with('error', $response['message'] ?? 'Something went wrong.');
                        }
                } else {
                    $authuser   = Auth::user();
                    $authuser->plan = $plan->id;
                    $authuser->save();
                    
                    $assignPlan = $authuser->assignPlan($plan->id, $request->paypal_payment_frequency);
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                    $ordes = Order::create(

                        [
                            'order_id' => $orderID,
                            'name' => $request->name,
                            'card_number' => '',
                            'card_exp_month' => '',
                            'card_exp_year' => '',
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $price,
                            'price_currency' => 'USD',
                            'txn_id' => '',
                            'payment_type' => __('PayPal'),
                            'payment_status' => isset($data['status']) ? $data['status'] : 'succeeded',
                            'receipt' => 'free coupon',
                            'user_id' => $authuser->id,
                        ]
                    );
                }

                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    $userCoupon         = new UserCoupon();
                    $userCoupon->user   = $authuser->id;
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
            } catch (\Exception $e) {


                return redirect()->route('plans')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->route('plans')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetPaymentStatus(Request $request, $plan_id)
    {
        $this->paymentConfig();
        $user = Auth::user();
        $plan = Plan::find($plan_id);
        if ($plan) {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            $payment_id = Session::get('paypal_payment_id');
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

            if ($request->has('coupon_id') && $request->coupon_id != '') {
                $coupons = Coupon::find($request->coupon_id);
                if (!empty($coupons)) {
                    $userCoupon         = new UserCoupon();
                    $userCoupon->user   = $user->id;
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

            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                $order                 = new Order();
                $order->order_id       = $orderID;

                $order->name           = $user->name;
                $order->card_number    = '';
                $order->card_exp_month = '';
                $order->card_exp_year  = '';
                $order->plan_name      = $plan->name;
                $order->plan_id        = $plan->id;
                // $order->price         = $result['transactions'][0]['amount']['total'];
                $order->price = $plan->monthly_price;
                $order->price_currency = "USD";

                //$order->txn_id         = $payment_id;
                $order->txn_id = '';
                $order->payment_type   = __('PAYPAL');
                // $order->payment_status = $result['state'];
                $order->payment_status = 'COMPLETED';
                $order->receipt        = '';
                $order->user_id        = $user->id;
                $order->save();
                $assignPlan = $this->assignPlan($plan->id, $user->id, $request->frequency);

                Utility::add_referal_settings($plan);
                if ($assignPlan['is_success']) {
                    return redirect()->route('plans')->with('success', __('Plan activated Successfully.'));
                } else {
                    return redirect()->route('plans')->with('error', __($assignPlan['error']));
                }
                return redirect()
                    ->route('plans')
                    ->with('success', 'Transaction complete.');
            } else {
                return redirect()
                    ->route('plans')
                    ->with('error', $response['message'] ?? 'Something went wrong.');
            }
        } else {
            return redirect()->route('plans')->with('error', __('Plan is deleted.'));
        }
    }
}
