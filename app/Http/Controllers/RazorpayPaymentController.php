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

class RazorpayPaymentController extends Controller
{
    public $secret_key;
    public $public_key;
    public $is_enabled;
    public $currancy;

    public function planPayWithRazorpay(Request $request){

        $this->planpaymentSetting();

        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan           = Plan::find($planID);
        $authuser       = Auth::user();
        $coupon_id = '';
        if($plan)
        {
            /* Check for code usage */
            $plan->discounted_price = false;
            $price = (float)$plan->{$request->razorpay_payment_frequency . '_price'};

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
                        return Utility::error_res( __('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                    $coupon_id = $coupons->id;
                }
                else
                {
                    return Utility::error_res( __('This coupon code is invalid or has expired.'));
                }
            }

            if($price <= 0)
            {
                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $authuser->assignPlan($plan->id);

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
                            'price' => $price==null?0:$price,
                            'price_currency' => !empty($this->currancy) ? $this->currancy : 'usd',
                            'txn_id' => '',
                            'payment_type' => 'Paystack',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    Utility::add_referal_settings($plan);
                    $res['msg'] = __("Plan successfully upgraded.");
                    $res['flag'] = 2;
                    return $res;
                }
                else
                {
                    return Utility::error_res( __('Plan fail to upgrade.'));
                }
            }

            $res_data['email'] = Auth::user()->email;
            $res_data['total_price'] = $price;
            $res_data['currency'] = $this->currancy;
            $res_data['flag'] = 1;
            $res_data['payment_frequency'] = $request->razorpay_payment_frequency;
            $res_data['coupon'] = $coupon_id;
            return $res_data;
        }
        else
        {
            return Utility::error_res( __('Plan is deleted.'));
        }

    }
    public function getPaymentStatus(Request $request,$pay_id,$plan){

        $this->planpaymentSetting();

        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan           = Plan::find($planID);
        $user = Auth::user();
        if($plan)
        {
            try
            {
                $orderID = time();
                $ch = curl_init('https://api.razorpay.com/v1/payments/' . $pay_id . '');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_USERPWD, $this->public_key . ':' . $this->secret_key); // Input your Razorpay Key Id and Secret Id here
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = json_decode(curl_exec($ch));
                // check that payment is authorized by razorpay or not

                if($response->status == 'authorized')
                {


                    if($request->has('coupon_id') && $request->coupon_id != '')
                    {
                        $coupons = Coupon::find($request->coupon_id);
                        if(!empty($coupons))
                        {
                            $userCoupon            = new UserCoupon();
                            $userCoupon->user   = $user->id;
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
                    }

                    $order                 = new Order();
                    $order->order_id       = $orderID;
                    $order->name           = $user->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = isset($response->amount) ? $response->amount/100 : 0;
                    $order->price_currency = $this->currancy;
                    $order->txn_id         = isset($response->id) ? $response->id : $pay_id;
                    $order->payment_type   = __('razorpay');
                    $order->payment_status = 'success';
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();

                    $assignPlan = $this->assignPlan($plan->id,$user->id,$request->payment_frequency);

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
                    return redirect()->route('plans')->with('error', __('Transaction has been failed! '));
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

        $payment_setting = Utility::getAdminPaymentSetting();
        $this->currancy = isset($payment_setting['currency'])?$payment_setting['currency']:'';
        $this->secret_key = isset($payment_setting['razorpay_secret_key'])?$payment_setting['razorpay_secret_key']:'';
        $this->public_key = isset($payment_setting['razorpay_public_key'])?$payment_setting['razorpay_public_key']:'';
        $this->is_enabled = isset($payment_setting['is_razorpay_enabled'])?$payment_setting['is_razorpay_enabled']:'off';
    }
}
