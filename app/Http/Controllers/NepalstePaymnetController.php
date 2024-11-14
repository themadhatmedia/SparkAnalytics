<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\Plan;
use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\User;
use App\Models\InvoicePayment;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class NepalstePaymnetController extends Controller
{
    public function planPayWithnepalste(Request $request)
    {

        $payment_setting = Utility::getAdminPaymentSetting();
        $api_key = isset($payment_setting['nepalste_public_key']) ? $payment_setting['nepalste_public_key'] : '';
        $nepalste_mode  = isset($payment_setting['nepalste_mode']) ? $payment_setting['nepalste_mode']  : '';
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'NPR';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $user = Auth::user();
        
        if ($plan) {
            $get_amount = $plan->{$request->nepalste_payment_frequency . "_price"};
            $price = (float)$plan->{$request->nepalste_payment_frequency . '_price'};
            
            if (!empty($request->coupon)) {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    $discount_value = ($price / 100) * $coupons->discount;
                    
                    $get_amount = $price - $discount_value;
                    
                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    if ($get_amount <= 0) {
                        $authuser = \Auth::user();
                        $authuser->plan = $plan->id;
                        $authuser->save();
                        $assignPlan = $authuser->assignPlan($plan->id, $authuser->id, $request->nepalste_payment_frequency);
                        if ($assignPlan['is_success'] == true && !empty($plan)) {

                            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                            $userCoupon = new UserCoupon();

                            $userCoupon->user = $authuser->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order = $orderID;
                            $userCoupon->save();
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
                                    'price' => $get_amount == null ? 0 : $get_amount,
                                    'price_currency' => $currency,
                                    'txn_id' => '',
                                    'payment_type' => 'Nepalste',
                                    'payment_status' => 'success',
                                    'receipt' => null,
                                    'user_id' => $authuser->id,
                                ]
                            );
                            $assignPlan = $authuser->assignPlan($plan->id, $authuser->id, $request->nepalste_payment_frequency);
                            return redirect()->route('plans')->with('success', __('Plan Successfully Activated'));
                        }
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }
        }
        if (!empty($request->coupon)) {
            $response = ['get_amount' => $get_amount, 'plan' => $plan, 'coupon_id' => $coupons->id];
        } else {
            $response = ['get_amount' => $get_amount, 'plan' => $plan];
        }

        $parameters = [
            'identifier' => 'DFU80XZIKS',
            'currency' => $currency,
            'amount' => $get_amount,
            'details' => $plan->name,
            'ipn_url' => route('nepalste.status', $response),
            'cancel_url' => route('nepalste.cancel'),
            'success_url' => route('nepalste.status', $response),
            'public_key' => $api_key,
            'site_logo' => 'https://nepalste.com.np/assets/images/logoIcon/logo.png',
            'checkout_theme' => 'dark',
            'customer_name' => 'John Doe',
            'customer_email' => 'john@mail.com',
        ];
        
        //live end point
        // $liveUrl = "https://nepalste.com.np/payment/initiate";
        //test end point
        // $sandboxUrl = "https://nepalste.com.np/sandbox/payment/initiate";
        if ($nepalste_mode == 'live') {
            $url = "https://nepalste.com.np/payment/initiate";
        } else {
            $url = "https://nepalste.com.np/sandbox/payment/initiate";
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($result, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            // JSON decode failed, handle error
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
        
        if (isset($result['success'])) {
            return redirect($result['url']);
        } else {
            // Add extra condition to avoid null values
            $errorMessage = isset($result['message']) ? $result['message'] : 'Unknown error occurred.';
            return redirect()->back()->with('error', __($errorMessage));
        }
        
    }

    public function planGetNepalsteStatus(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';

        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        $getAmount = $request->get_amount;
        $authuser = \Auth::user();
        $plan = Plan::find($request->plan);

        $order = new Order();
        $order->order_id = $orderID;
        $order->name = $authuser->name;
        $order->card_number = '';
        $order->card_exp_month = '';
        $order->card_exp_year = '';
        $order->plan_name = $plan->name;
        $order->plan_id = $plan->id;
        $order->price = $getAmount;
        $order->price_currency = $currency;
        $order->txn_id = $orderID;
        $order->payment_type = __('Neplaste');
        $order->payment_status = 'success';
        $order->txn_id = '';
        $order->receipt = '';
        $order->user_id = $authuser->id;
        $order->save();
        $assignPlan = $authuser->assignPlan($plan->id, $authuser->id, $request->nepalste_payment_frequency);


        $coupons = Coupon::find($request->coupon_id);
        if (!empty($request->coupon_id)) {
            if (!empty($coupons)) {
                $userCoupon = new UserCoupon();
                $userCoupon->user = $authuser->id;
                $userCoupon->coupon = $coupons->id;
                $userCoupon->order = $orderID;
                $userCoupon->save();
                $usedCoupun = $coupons->used_coupon();
                if ($coupons->limit <= $usedCoupun) {
                    $coupons->is_active = 0;
                    $coupons->save();
                }
            }
        }

        Utility::add_referal_settings($plan);
        if ($assignPlan['is_success']) {
            return redirect()->route('plans')->with('success', __('Plan activated Successfully.'));
        } else {
            return redirect()->route('plans')->with('error', __($assignPlan['error']));
        }
    }
    public function planGetNepalsteCancel(Request $request)
    {
        return redirect()->back()->with('error', __('Transaction has failed'));
    }
}
