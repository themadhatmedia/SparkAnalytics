<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Utility;
use App\Models\InvoicePayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCoupon;
use Illuminate\Support\Facades\Validator;

class PaymentWallController extends Controller
{

	public function planpay(Request $request)
    {
    	$data=$request->all();

    	 $admin_payment_setting = Utility::getAdminPaymentSetting();

    	return view('admin.plan.planpay',compact('data','admin_payment_setting'));

    }


    public function planerror(Request $request,$flag)
    {
          if($flag == 1){
            return redirect()->route("plans")->with('success', __('Plan activated Successfully! '));
        }else{
                return redirect()->route("plans")->with('error', __('Transaction has been failed! '));
        }

    }

   public function planPayWithPaymentWall(Request $request,$plan_id)
   {
        $admin_payment_setting = Utility::payment_settings();
        $this->planpaymentSetting();

        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($plan_id);
        $plan           = Plan::find($planID);

        $authuser       = Auth::user();
        $coupon_id ='';
        if($plan)
        {

            /* Check for code usage */
            $plan->discounted_price = false;
            $price                  = $plan->price;

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

                $assignPlan = $this->assignPlan($plan->id , $authuser->id , $request->paymentwall_payment_frequency);

                if($assignPlan['is_success'] == true && !empty($plan))
                {

                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
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
                            'price' => $price,
                            'price_currency' => !empty($this->currancy) ? $this->currancy : 'usd',
                            'txn_id' => '',
                            'payment_type' => 'PaymentWall',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    Utility::add_referal_settings($plan);
                    $res['msg'] = __("Plan successfully upgraded.");
                    $res['flag'] = 1;


                    return $res;
                }
                else
                {
                    $res['msg'] = __("Plan successfully upgraded.");
                    $res['flag'] = 2;


                    return $res;
                }
            }
            else
            {

                \Paymentwall_Config::getInstance()->set(array(

                    'private_key' => $admin_payment_setting['paymentwall_private_key']
                ));

                $parameters = $request->all();

                $chargeInfo = array(
                    'email' => $parameters['email'],
                    'history[registration_date]' => '1489655092',
                    'amount' => $price,
                    'currency' => !empty($this->currancy) ? $this->currancy : 'USD',
                    'token' => $parameters['brick_token'],
                    'fingerprint' => $parameters['brick_fingerprint'],
                    'description' => 'Order #123'
                );

                $charge = new \Paymentwall_Charge();
                $charge->create($chargeInfo);
                $responseData = json_decode($charge->getRawResponseData(),true);
                $response = $charge->getPublicData();

                if ($charge->isSuccessful() AND empty($responseData['secure'])) {
                    if ($charge->isCaptured()) {
                       if($request->has('coupon') && $request->coupon != '')
                        {
                            $coupons = Coupon::find($request->coupon);
                            if(!empty($coupons))
                            {
                                $userCoupon            = new UserCoupon();
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
                        }

                        $order                 = new Order();
                        $order->order_id       = $orderID;
                        $order->name           = $authuser->name;
                        $order->card_number    = '';
                        $order->card_exp_month = '';
                        $order->card_exp_year  = '';
                        $order->plan_name      = $plan->name;
                        $order->plan_id        = $plan->id;
                        $order->price          = isset($paydata['amount']) ? $paydata['amount'] : $price;
                        $order->price_currency = $this->currancy;
                        $order->txn_id         = isset($paydata['txid']) ? $paydata['txid'] : 0;
                        $order->payment_type   = __('PaymentWall');
                        $order->payment_status = 'success';
                        $order->receipt        = '';
                        $order->user_id        = $authuser->id;
                        $order->save();

                        $assignPlan = $this->assignPlan($plan->id , $authuser->id , $request->paymentwall_payment_frequency);

                        if($assignPlan['is_success'])
                        {



                             $res['flag'] = 1;
                             return $res;

                        }
                    } elseif ($charge->isUnderReview()) {
                          $res['flag'] = 2;
                             return $res;
                    }
                } elseif (!empty($responseData['secure'])) {
                    $response = json_encode(array('secure' => $responseData['secure']));
                } else {
                    $errors = json_decode($response, true);
                    $res['msg'] = __("Trasnsaction has been Fail.");

                    $res['flag'] = 2;
                    return $res;
                }

            }

            $res['flag'] = 2;
            return $res;
        }
        else
        {
            $res['flag'] = 2;
            return $res;
        }
    }

    public function planpaymentSetting()
    {
        $payment_setting = Utility::payment_settings();

        $this->currancy = isset($payment_setting['currency'])?$payment_setting['currency']:'';

        $this->secret_key = isset($payment_setting['paymentwall_private_key'])?$payment_setting['paymentwall_private_key']:'';
        $this->public_key = isset($payment_setting['paymentwall_public_key'])?$payment_setting['paymentwall_public_key']:'';
        $this->is_enabled = isset($payment_setting['is_paymentwall_enabled'])?$payment_setting['is_paymentwall_enabled']:'off';
        return $this;
    }
}
