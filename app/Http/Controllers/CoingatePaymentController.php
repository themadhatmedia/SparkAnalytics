<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use App\Coingate\Coingate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CoingatePaymentController extends Controller
{
    public $mode;
    public $coingate_auth_token;
    public $is_enabled;
    public $currancy;

    public function planPayWithCoingate(Request $request)
    {
        $this->planpaymentSetting();

        $authuser   = Auth::user();
        $planID     = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan       = Plan::find($planID);

        if($plan)
        {
            $coupons_id = '';
            $plan->discounted_price = false;
            $price                  = $plan->{$request->coingate_payment_frequency . '_price'};
            if(isset($request->coupon) && !empty($request->coupon))
            {
                $request->coupon = trim($request->coupon);
                $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

                if(!empty($coupons))
                {
                    $usedCoupun             = $coupons->used_coupon();
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;

                    if($usedCoupun >= $coupons->limit)
                    {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    $price      = $price - $discount_value;

                    $coupons_id = $coupons->id;
                }
                else
                {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            if($price <= 0)
            {
                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $this->assignPlan($plan->id,$authuser->id, $request->coingate_payment_frequency);

                if($assignPlan['is_success'] == true && !empty($plan))
                {
                    if(!empty($authuser->payment_subscription_id) && $authuser->payment_subscription_id != '')
                    {
                        try
                        {
                            $authuser->cancel_subscription($authuser->id);
                        }
                        catch(\Exception $exception)
                        {
                            \Log::debug($exception->getMessage());
                        }
                    }

                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                    if(!empty($coupons))
                            {
                                $userCoupon         = new UserCoupon();
                                $userCoupon->user   = $authuser->id;
                                $userCoupon->coupon = $coupons->id;
                                $userCoupon->order  = $orderID;
                                $userCoupon->save();

                                $usedCoupun = $coupons->used_coupon();
                                if($coupons->limit <= $usedCoupun)
                                {
                                    $coupons->is_active = 0;
                                    $coupons->save();
                                }
                            }

                    Order::create(
                        [
                            'order_id' => $orderID,
                            'name' => null,
                            'email' => null,
                            'card_number' => null,
                            'card_exp_month' => null,
                            'card_exp_year' => null,
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $price == null ? 0 : $price,
                            'price_currency' => $this->currancy,
                            'txn_id' => '',
                            'payment_type' => __('Zero Price'),
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );

                    Utility::add_referal_settings($plan);
                    return redirect()->back()->with('success', __('Plan activated Successfully!'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Plan fail to upgrade.'));
                }
            }

            Coingate::config(
                array(
                    'environment' => $this->mode,
                    'auth_token' => $this->coingate_auth_token,
                    'curlopt_ssl_verifypeer' => FALSE,
                )
            );
            $post_params = array(
                'order_id' => time(),
                'price_amount' => $price,
                'price_currency' => $this->currancy,
                'receive_currency' => $this->currancy,
                'callback_url' => route(
                    'plan.coingate', [
                                       $request->plan_id,
                                       'payment_frequency=' . $request->coingate_payment_frequency,
                                       'coupon_id=' . $coupons_id,
                                   ]
                ),
                'cancel_url' => route('plan.coingate', [$request->plan_id]),
                'success_url' => route(
                    'plan.coingate', [
                                       $request->plan_id,
                                       'payment_frequency=' . $request->coingate_payment_frequency,
                                       'coupon_id=' . $coupons_id,
                                   ]
                ),
                'title' => 'Plan #' . time(),
            );

            // $order = \App\Coingate\Merchant\Order::create($post_params);
            $order = Coingate::coingatePayment($post_params, 'POST');
            if($order['status_code'] === 200) { 
                $response = $order['response']; 
                return redirect($response['payment_url']); 
                }                
            else
            {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Plan is deleted.');
        }

    }

    public function getPaymentStatus(Request $request, $plan)
    {
        $this->planpaymentSetting();

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan   = Plan::find($planID);
        $price  = $plan->{$request->payment_frequency . '_price'};
        $user   = Auth::user();

        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        if($plan)
        {
            try
            {
                if($request->has('payment_frequency'))
                {
                    if($request->has('coupon_id') && $request->coupon_id != '')
                    {
                        $coupons = Coupon::find($request->coupon_id);
                        if(!empty($coupons))
                        {
                            $userCoupon         = new UserCoupon();
                            $userCoupon->user   = $user->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order  = $orderID;
                            $userCoupon->save();
                            $discount_value = ($price / 100) * $coupons->discount;
                            $price          = $price - $discount_value;
                            $usedCoupun     = $coupons->used_coupon();
                            if($coupons->limit <= $usedCoupun)
                            {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                        }
                    }

                    if(!empty($user->payment_subscription_id) && $user->payment_subscription_id != '')
                    {
                        try
                        {
                            $user->cancel_subscription($user->id);
                        }
                        catch(\Exception $exception)
                        {
                            \Log::debug($exception->getMessage());
                        }
                    }

                    $order                 = new Order();
                    $order->order_id       = $orderID;
                    $order->name           = $user->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = $price;
                    $order->price_currency = $this->currancy;
                    $order->txn_id         = isset($request->transaction_id) ? $request->transaction_id : '';
                    $order->payment_type   = 'Coingate';
                    $order->payment_status = 'succeeded';
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();

                    $assignPlan = $this->assignPlan($plan->id,$user->id, $request->payment_frequency);

                    if($assignPlan['is_success'])
                    {
                        return redirect()->route('plans')->with('success', __('Plan activated Successfully!'));
                    }
                    else
                    {
                        return redirect()->route('plans')->with('error', __($assignPlan['error']));
                    }
                }
                else
                {
                    return redirect()->route('plans')->with('error', __('Transaction has been failed.'));
                }
            }
            catch(\Exception $e)
            {
                return redirect()->route('plans')->with('error', __('Plan not found!'));
            }
        }
    }

    public function planpaymentSetting()
    {
        $user = Auth::user();

        $payment_setting = Utility::getAdminPaymentSetting();
        $this->currancy  = !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD';


        $this->coingate_auth_token = isset($payment_setting['coingate_auth_token']) ? $payment_setting['coingate_auth_token'] : '';
        $this->mode                = isset($payment_setting['coingate_mode']) ? $payment_setting['coingate_mode'] : 'off';
        $this->is_enabled          = isset($payment_setting['is_coingate_enabled']) ? $payment_setting['is_coingate_enabled'] : 'off';
    }
}
