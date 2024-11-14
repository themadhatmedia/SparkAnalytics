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
use Lahirulhr\PayHere\PayHere;

class PayHereController extends Controller
{
    public function planPayWithPayHere(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $payhere_merchant_id = !empty($payment_setting['payhere_merchant_id']) ? $payment_setting['payhere_merchant_id'] : '';
        $payhere_merchant_secret = !empty($payment_setting['payhere_merchant_secret']) ? $payment_setting['payhere_merchant_secret'] : '';
        $payhere_app_id = !empty($payment_setting['payhere_app_id']) ? $payment_setting['payhere_app_id'] : '';
        $payhere_app_secret = !empty($payment_setting['payhere_app_secret']) ? $payment_setting['payhere_app_secret'] : '';
        $payhere_mode = !empty($payment_setting['payhere_mode']) ? $payment_setting['payhere_mode'] : 'sandbox';
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'XOF';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);

        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $authuser = Auth::user();
        
        if ($plan) {
            $get_amount = $plan->{$request->payhere_payment_frequency . "_price"};
            $price = (float)$plan->{$request->payhere_payment_frequency . '_price'};
            
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
                        $authuser = Auth::user();
                        $authuser->plan = $plan->id;
                        $authuser->save();
                        $assignPlan = $authuser->assignPlan($plan->id, $authuser->id, $request->payhere_payment_frequency);
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
                                    'price' => $get_amount ?? 0,
                                    'price_currency' => $currency,
                                    'txn_id' => '',
                                    'payment_type' => __('Paiement Pro'),
                                    'payment_status' => 'success',
                                    'receipt' => null,
                                    'user_id' => $authuser->id,
                                ]
                            );
                            $assignPlan = $authuser->assignPlan($plan->id, $authuser->id, $request->payhere_payment_frequency);
                            return redirect()->route('plans')->with('success', __('Plan Successfully Activated'));
                        }
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }


            try {

                $config = [
                    'payhere.api_endpoint' => $payhere_mode === 'sandbox'
                        ? 'https://sandbox.payhere.lk/'
                        : 'https://www.payhere.lk/',
                ];

                $config['payhere.merchant_id'] = $payhere_merchant_id ?? '';
                $config['payhere.merchant_secret'] = $payhere_merchant_secret ?? '';
                $config['payhere.app_secret'] = $payhere_app_secret ?? '';
                $config['payhere.app_id'] = $payhere_app_id ?? '';
                config($config);


                $hash = strtoupper(
                    md5(
                        $payhere_merchant_id .
                            $orderID .
                            number_format($get_amount, 2, '.', '') .
                            'LKR' .
                            strtoupper(md5($payhere_merchant_secret))
                    )
                );

                $data = [
                    'first_name' => $authuser->name ?? '',
                    'last_name' => $authuser->name ?? '',
                    'email' => $authuser->email ?? '',
                    'phone' => $authuser->mobile_no ?? '',
                    'address' => 'Main Rd',
                    'city' => 'Anuradhapura',
                    'country' => 'Sri lanka',
                    'order_id' => $orderID,
                    'items' => $plan->name ?? 'Add-on',
                    'currency' => $currency,
                    'amount' => $get_amount,
                    'hash' => $hash,
                ];
                return PayHere::checkOut()
                ->data($data)
                    ->successUrl(route('payhere.status', [
                        $plan->id,
                        'amount' => $get_amount,
                        'coupon_code' => !empty($request->coupon_code) ? $request->coupon_code : '',
                        'coupon_id' => !empty($coupons->id) ? $coupons->id : '',
                        ]))

                        ->failUrl(route('payhere.status', [
                            $plan->id,
                            'amount' => $get_amount,
                            'coupon_code' => !empty($request->coupon_code) ? $request->coupon_code : '',
                            'coupon_id' => !empty($coupons->id) ? $coupons->id : '',
                            ]))
                            ->renderView();
            } catch (\Exception $e) {
                \Log::debug($e->getMessage());
                return redirect()->route('plans')->with('error', $e->getMessage());
            }
        } else {
            return redirect()->route('plans')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetPayHereStatus(Request $request)
    {
        if ($request->success == 1) {
            $info = PayHere::retrieve()
                ->orderId($request->order_id) // order number that you use to charge from customer
                ->submit();

            if ($info['data'][0]['order_id'] == $request->order_id) {
                if ($info['data'][0]['status'] == "RECEIVED") {

                    $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
                    $plan = Plan::find($planID);
                    $authuser = Auth::user();

                    if ($request->has('coupon_id') && $request->coupon_id != '') {
                        $coupons = Coupon::find($request->coupon_id);
                        if (!empty($coupons)) {
                            $userCoupon            = new UserCoupon();
                            $userCoupon->user   = Auth::user()->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order  = $request->order_id;
                            $userCoupon->save();


                            $usedCoupun = $coupons->used_coupon();
                            if ($coupons->limit <= $usedCoupun) {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                        }
                    }
                    $order                 = new Order();
                    $order->order_id       = $request->order_id;
                    $order->name           = Auth::user()->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = isset($request->amount) ? $request->amount / 100 : 0;
                    $order->price_currency = 'LKR';
                    $order->txn_id         = app('App\Http\Controllers\BillController')->transactionNumber(Auth::user()->id);
                    $order->payment_type   = __('PayHere');
                    $order->payment_status = 'success';
                    $order->receipt        = '';
                    $order->user_id        = Auth::user()->id;
                    $order->save();

                    $assignPlan = $authuser->assignPlan($plan->id,$authuser->id,$request->payhere_payment_frequency);
                    Utility::add_referal_settings($plan);
                    if ($assignPlan['is_success']) {
                        return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                    } else {
                        return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                    }
                }
            }
        } else {
            return redirect()->back()->with('error', __('Oops! Something went wrong.'));
        }
    }
}
