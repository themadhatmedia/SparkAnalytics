<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use App\Package\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TapPaymentController extends Controller
{
    public function planPayWithTap(Request $request)
    {
        $payment_setting    = Utility::getAdminPaymentSetting();
        $currency           = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';
        $tap_secret_key     = isset($payment_setting['tap_secret']) ? $payment_setting['tap_secret'] : '';
        $planID             = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan               = Plan::find($planID);
        $orderID            = strtoupper(str_replace('.', '', uniqid('', true)));

        if ($plan) {
            $get_amount = $plan->{$request->tap_payment_frequency . "_price"};
            $price = (float)$plan->{$request->tap_payment_frequency . '_price'};

            if (!empty($request->coupon)) {
                $coupons    = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

                if (!empty($coupons)) {
                    $usedCoupun     = $coupons->used_coupon();
                    $discount_value = ($price / 100) * $coupons->discount;
                    $get_amount         =   $price - $discount_value;

                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    if ($get_amount <= 0) {
                        $authuser   = \Auth::user();
                        $authuser->plan = $plan->id;
                        $authuser->save();
                        $assignPlan = $authuser->assignPlan($plan->id, $authuser->id, $request->tap_payment_frequency);
                        if ($assignPlan['is_success'] == true && !empty($plan)) {

                            $orderID    = strtoupper(str_replace('.', '', uniqid('', true)));
                            $userCoupon = new UserCoupon();

                            $userCoupon->user   = $authuser->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order  = $orderID;
                            $userCoupon->save();
                            Order::create(
                                [
                                    'order_id'          => $orderID,
                                    'name'              => null,
                                    'email'             => null,
                                    'card_number'       => null,
                                    'card_exp_month'    => null,
                                    'card_exp_year'     => null,
                                    'plan_name'         => $plan->name,
                                    'plan_id'           => $plan->id,
                                    'price'             => $get_amount == null ? 0 : $get_amount,
                                    'price_currency'    => $currency,
                                    'txn_id'            => '',
                                    'payment_type'      => 'Tap',
                                    'payment_status'    => 'success',
                                    'receipt'           => null,
                                    'user_id'           => $authuser->id,
                                ]
                            );
                            $assignPlan     = $authuser->assignPlan($plan->id, $authuser->id, $request->tap_payment_frequency);
                            return redirect()->route('plans')->with('success', __('Plan Successfully Activated'));
                        }
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            if (!empty($request->coupon)) {
                $response = ['get_amount' => $get_amount, 'plan' => $plan->id, 'coupon_id' => $coupons->id];
            } else {
                $response = ['get_amount' => $get_amount, 'plan' => $plan->id];
            }

            try {
                if (in_array($currency, ['KWD', 'SAR', 'AED', 'QAR', 'BHD', 'OMR', 'JOD', 'EGP', 'USD', 'EUR', 'GBP'])) {
                    $TapPay = new Payment(['company_tap_secret_key' => $tap_secret_key]);
                    return $TapPay->charge([
                        'amount'        => $get_amount,
                        'currency'      => $currency,
                        'threeDSecure'  => 'true',
                        'description'   => 'test description',
                        'statement_descriptor' => 'sample',
                        'customer'      => [
                            'first_name'    => \Auth::user()->name ?? '',
                            'email'         => \Auth::user()->email ?? '',
                        ],
                        'source' => [
                            'id' => 'src_card'
                        ],
                        'post' => [
                            'url' => null
                        ],
                        // 'merchant' => [
                        //    'id' => 'YOUR-MERCHANT-ID'  //Include this when you are going to live
                        // ],
                        'redirect' => [
                            'url' => route('tap.status', [
                                'data' => $response,
                            ])
                        ]
                    ], true);
                } else {
                    return redirect()->route('plans')->with('error', __('The selected currency is not supported.'));
                }
            } catch (\Exception $e) {
                \Log::debug($e->getMessage());
                return redirect()->route('plans')->with('error', __('Plan is deleted or something went wrong.'));
            }
        } else {
            return redirect()->route('plans')->with('error', __('Plan is deleted.'));
        }
    }

    public function planTapStatus(Request $request)
    {
        $user = \Auth::user();
        $plan = Plan::find($request->data['plan']);
        if ($plan) {
            $payment_setting    = Utility::getAdminPaymentSetting();
            $currency           = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';
            $tap_secret_key     = isset($payment_setting['tap_secret']) ? $payment_setting['tap_secret'] : '';
            $orderID            = strtoupper(str_replace('.', '', uniqid('', true)));
            $TapPay = new Payment(['company_tap_secret_key' => $tap_secret_key]);
            $tap_payment_id = $request->tap_id;
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.tap.company/v2/charges/$tap_payment_id");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Authorization: Bearer $tap_secret_key"
                ]);
                $result = curl_exec($ch);
                curl_close($ch);
                $paymentDetails = json_decode($result, true);
                if ($paymentDetails && $paymentDetails['status'] == 'CAPTURED') {
                    $order = Order::create(
                        [
                            'order_id'          => $orderID,
                            'name'              => null,
                            'email'             => null,
                            'card_number'       => null,
                            'card_exp_month'    => null,
                            'card_exp_year'     => null,
                            'plan_name'         =>  !empty($plan->name) ? $plan->name : 'Basic Package',
                            'plan_id'           => $plan->id,
                            'price'             => !empty($request->data['get_amount']) ? $request->data['get_amount'] : 0,
                            'price_currency'    => $currency,
                            'txn_id'            => '',
                            'payment_type'      => __('Tap'),
                            'payment_status'    => 'success',
                            'receipt'           => null,
                            'user_id'            => $user->id,
                        ]
                    );

                    $assignPlan = $user->assignPlan($plan->id, $user->id, $request->tap_payment_frequency);

                    if (!empty($request->data['coupon_id'])) {
                        $coupons = Coupon::find($request->data['coupon_id']);
                        if (!empty($request->data['coupon_id'])) {
                            if (!empty($coupons)) {
                                $userCoupon = new UserCoupon();
                                $userCoupon->user = $user->id;
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
                    }

                    Utility::add_referal_settings($plan);

                    if ($assignPlan['is_success']) {
                        return redirect()->route('plans')->with('success', __('Plan activated Successfully.'));
                    } else {
                        return redirect()->route('plans')->with('error', __($assignPlan['error']));
                    }
                } else {
                    return redirect()->route('plans')->with('error', __('Transaction failed .'));
                }
            } catch (\Exception $e) {
                return redirect()->route('plans')->with('error', __('Transaction has been failed.'));
            }
        } else {
            return redirect()->route('plans')->with('error', __('Plan is deleted.'));
        }
    }
}
