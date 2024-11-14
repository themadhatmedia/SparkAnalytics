<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;
use App\Models\User;
use App\Models\Plan;
use App\Models\Order;
use Modules\Fedapay\Events\FedapayPaymentStatus;

class FedapayController extends Controller
{
    public function planPayWithFedapay(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $fedapay = !empty($payment_setting['fedapay_secret_key']) ? $payment_setting['fedapay_secret_key'] : '';
        $fedapay_mode = !empty($payment_setting['company_fedapay_mode']) ? $payment_setting['company_fedapay_mode'] : 'sandbox';
        $currency           = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';

        if ($currency !== 'XOF') {
            return redirect()->back()->with('error', __('Transaction currency must be XOF.'));
        }

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $authuser = \Auth::user();

        if ($plan) {
            /* Check for code usage */
            $integerValue = $plan->{$request->fedapay_payment_frequency . "_price"};
            $get_amount = intval($integerValue);
            $price = (float)$plan->{$request->fedapay_payment_frequency . '_price'};

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
                        $authuser->plan = $plan->id;
                        $authuser->save();
                        $assignPlan =$authuser->assignPlan($plan->id, $authuser->id, $request->fedapay_payment_frequency);

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
                                    'payment_type' => __('Fedapay'),
                                    'payment_status' => 'success',
                                    'receipt' => null,
                                    'user_id' => $authuser->id,
                                ]
                            );
                        $assignPlan = $authuser->assignPlan($plan->id, $authuser->id, $request->fedapay_payment_frequency);
                            return redirect()->route('plans')->with('success', __('Plan Successfully Activated'));
                        }
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            try {
                \FedaPay\FedaPay::setApiKey($fedapay);
                \FedaPay\FedaPay::setEnvironment($fedapay_mode);
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                $transaction = \FedaPay\Transaction::create([
                    "description" => "Fedapay Payment",
                    "amount" => $get_amount,
                    "currency" => ["iso" => $currency],

                    "callback_url" => route('fedapay.status', [
                        'order_id' => $orderID,
                        'plan_id' => $plan->id,
                        "amount" => $get_amount,
                        "coupon_id" => !empty($coupons->id) ? $coupons->id : '',
                        'coupon_code' => !empty($request->coupon) ? $request->coupon : '',
                    ]),
                    "cancel_url" => route('fedapay.status', [
                        'order_id' => $orderID,
                        'plan_id' => $plan->id,
                        "amount" => $get_amount,
                        "coupon_id" => !empty($coupons->id) ? $coupons->id : '',
                        'coupon_code' => !empty($request->coupon) ? $request->coupon : '',
                    ]),

                ]);

                $token = $transaction->generateToken();

                return redirect($token->url);
            } catch (\Exception $e) {
                return redirect()->route('plans')->with('error', $e->getMessage());
            }
        } else {
            return redirect()->route('plans')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetFedapayStatus(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';

        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        $getAmount = $request->amount;
        $authuser = Auth::user();
        $plan = Plan::find($request->plan_id);

        try {

            if ($request->status == 'approved') {

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
                $order->payment_type = __('Fedapay');
                $order->payment_status = 'success';
                $order->receipt = '';
                $order->user_id = $authuser->id;
                $order->save();
                $assignPlan =$authuser->assignPlan($plan->id, $authuser->id, $request->fedapay_payment_frequency);
            } else {
                return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
            }
            if (!empty($authuser->referral_user)) {
                Utility::transaction($order);
            }
            $coupons = Coupon::where('code', $request->coupon_code)->first();

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
                return redirect()->route('plans')->with('success', __('Plan activated Successfully!'));
            } else {
                return redirect()->route('plans')->with('error', __($assignPlan['error']));
            }
        } catch (\Exception $e) {
            return redirect()->route('plans')->with('error', $e->getMessage());
        }
    }
}
