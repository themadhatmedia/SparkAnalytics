<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use Exception;
use Illuminate\Http\Request;
use App\Models\Utility;


class ToyyibpayController extends Controller
{
    public $secretKey, $callBackUrl, $returnUrl, $categoryCode, $is_enabled, $invoiceData;
	public $currancy;
    public function __construct()
    {
        // if (\Auth::user()->type == 'company') {
        //     $payment_setting = Utility::getAdminPaymentSetting();
        // } else {
        //     $payment_setting = Utility::getCompanyPaymentSetting($this->invoiceData);
        // }


        $payment_setting = Utility::getAdminPaymentSetting();

        $this->currancy  = !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD';
        $this->secretKey = isset($payment_setting['toyyibpay_secret_key']) ? $payment_setting['toyyibpay_secret_key'] : '';
        $this->categoryCode = isset($payment_setting['category_code']) ? $payment_setting['category_code'] : '';
        $this->is_enabled = isset($payment_setting['is_toyyibpay_enabled']) ? $payment_setting['is_toyyibpay_enabled'] : 'off';
    }

    public function index()
    {
        return view('payment');
    }

    public function charge(Request $request)
    {
    	$authuser   = auth()->user();
        try {
            $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
            $plan   = Plan::find($planID);


            if ($plan) {
                $price = $plan->{$request->toyyibpay_payment_frequency . '_price'};
                $get_amount = $plan->{$request->toyyibpay_payment_frequency . '_price'};



                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun     = $coupons->used_coupon();
                        $discount_value = ( $price / 100) * $coupons->discount;
                        $get_amount          =  $price - $discount_value;

                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
                if($get_amount > 0.0)
                {
                $coupon = (empty($request->coupon)) ? "0" : $request->coupon;
                $this->callBackUrl = route('plan.status', [$plan->id, $get_amount, $coupon,$request->toyyibpay_payment_frequency]);
                $this->returnUrl = route('plan.status', [$plan->id, $get_amount, $coupon,$request->toyyibpay_payment_frequency]);

                $Date = date('d-m-Y');
                $ammount = $get_amount;
                $billName = $plan->name;
                $description = $plan->name;
                $billExpiryDays = 3;
                $billExpiryDate = date('d-m-Y', strtotime($Date . ' + 3 days'));
                $billContentEmail = "Thank you for purchasing our product!";
                $some_data = array(
                    'userSecretKey' => $this->secretKey,
                    'categoryCode' => $this->categoryCode,
                    'billName' => $billName,
                    'billDescription' => $description,
                    'billPriceSetting' => 1,
                    'billPayorInfo' => 1,
                    'billAmount' => 100 *$ammount,
                    'billReturnUrl' => $this->returnUrl,
                    'price_currency' => $this->currancy,
                    'billCallbackUrl' => $this->callBackUrl,
                    'billExternalReferenceNo' => 'AFR341DFI',
                    'billTo' => $authuser->name,
                    'billEmail' => $authuser->email,
                    'billPhone' => '0194342411',
                    'billSplitPayment' => 0,
                    'billSplitPaymentArgs' => '',
                    'billPaymentChannel' => '0',
                    'billContentEmail' => $billContentEmail,
                    'billChargeToCustomer' => 1,
                    'billExpiryDate' => $billExpiryDate,
                    'billExpiryDays' => $billExpiryDays
                );
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_URL, 'https://toyyibpay.com/index.php/api/createBill');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
                $result = curl_exec($curl);
                $info = curl_getinfo($curl);
                curl_close($curl);
                $obj = json_decode($result);


               if(isset($obj->status) && $obj->status=="error")
               {

               		return redirect()->back()->with('error', $obj->msg);
               }
                return redirect('https://toyyibpay.com/' . $obj[0]->BillCode);
            }
            else{

                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $this->assignPlan($plan->id,$authuser->id, $request->toyyibpay_payment_frequency);


                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

                    if (!empty($request->coupon)) {
                        if (!empty($coupons)) {
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
                    }

                    $statuses = 'Success';
                    $order                 = new Order();
                    $order->order_id       = $orderID;
                    $order->name           = $authuser->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = $get_amount;
                    $order->price_currency = !empty(env('CURRENCY')) ? env('CURRENCY') : '$';
                    $order->txn_id         = isset($request->transaction_id) ? $request->transaction_id : '';
                    $order->payment_type   = __('Toyyibpay');
                    $order->payment_status = $statuses;
                    $order->receipt        = '';
                    $order->user_id        = $authuser->id;
                    $order->save();

                    return redirect()->route('plans')->with('success', __('Plan Successfully Activated'));
            }

            } else {
                return redirect()->route('plans')->with('error', __('Plan is deleted.'));
            }

        } catch (Exception $e) {
            return redirect()->route('plans')->with('error', __($e->getMessage()));
        }
    }
    public function status(Request $request, $planId, $getAmount, $couponCode,$frequency)
    {
        if ($couponCode != 0) {
            $coupons = Coupon::where('code', strtoupper($couponCode))->where('is_active', '1')->first();
            $request['coupon_id'] = $coupons->id;
        } else {
            $coupons = null;
        }

        $plan = Plan::find($planId);
        $user = auth()->user();
        //$request['status_id'] = 1;

        // 1=success, 2=pending, 3=fail
        try {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            if ($request->status_id == 3) {
                $statuses = 'Fail';
                $order                 = new Order();
                $order->order_id       = $orderID;
                $order->name           = $user->name;
                $order->card_number    = '';
                $order->card_exp_month = '';
                $order->card_exp_year  = '';
                $order->plan_name      = $plan->name;
                $order->plan_id        = $plan->id;
                $order->price          = $getAmount;
                $order->price_currency = env('CURRENCY');
                $order->txn_id         = isset($request->transaction_id) ? $request->transaction_id : '';
                $order->payment_type   = __('Toyyibpay');
                $order->payment_status = $statuses;
                $order->receipt        = '';
                $order->user_id        = $user->id;
                $order->save();
                return redirect()->route('plans')->with('success', __('Your Transaction is fail please try again'));
            } else if ($request->status_id == 2) {
                $statuses = 'pandding';
                $order                 = new Order();
                $order->order_id       = $orderID;
                $order->name           = $user->name;
                $order->card_number    = '';
                $order->card_exp_month = '';
                $order->card_exp_year  = '';
                $order->txn_id         = isset($request->transaction_id) ? $request->transaction_id : '';
                $order->plan_name      = $plan->name;
                $order->plan_id        = $plan->id;
                $order->price          = $getAmount;
                $order->price_currency = env('CURRENCY');
                $order->payment_type   = __('Toyyibpay');
                $order->payment_status = $statuses;
                $order->receipt        = '';
                $order->user_id        = $user->id;
                $order->save();
                return redirect()->route('plans')->with('success', __('Your transaction on pandding'));
            } else if ($request->status_id == 1) {
                $statuses = 'success';
                $order                 = new Order();
                $order->order_id       = $orderID;
                $order->name           = $user->name;
                $order->card_number    = '';
                $order->card_exp_month = '';
                $order->card_exp_year  = '';
                $order->plan_name      = $plan->name;
                $order->plan_id        = $plan->id;
                $order->price          = $getAmount;
                $order->price_currency = env('CURRENCY');
                $order->txn_id         = isset($request->transaction_id) ? $request->transaction_id : '';
                $order->payment_type   = __('Toyyibpay');
                $order->payment_status = $statuses;
                $order->receipt        = '';
                $order->user_id        = $user->id;
                $order->save();

                $assignPlan = $this->assignPlan($plan->id,$user->id, $frequency);
                $coupons = Coupon::find($request->coupon_id);
                if (!empty($request->coupon_id)) {
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
                if ($assignPlan['is_success']) {
                    return redirect()->route('plans')->with('success', __('Plan activated Successfully.'));
                } else {
                    return redirect()->route('plans')->with('error', __($assignPlan['error']));
                }
            } else {
                return redirect()->route('plans')->with('error', __('Plan is deleted.'));
            }
        } catch (Exception $e) {
            return redirect()->route('plans')->with('error', __($e->getMessage()));
        }
    }


}
