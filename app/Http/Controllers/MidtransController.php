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
use Illuminate\Support\Facades\Crypt;

class MidtransController extends Controller
{
    public function planPayWithMidtrans(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $midtrans_secret = $payment_setting['midtrans_secret'];
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if ($plan) {
            $get_amount = round($plan->{$request->midtrans_payment_frequency . '_price'});

            if (!empty($request->coupon)) {

                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    $discount_value = ($plan->{$request->midtrans_payment_frequency . '_price'} / 100) * $coupons->discount;
                    $get_amount = $plan->{$request->midtrans_payment_frequency . '_price'} - $discount_value;

                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    $userCoupon = new UserCoupon();
                    $userCoupon->user = Auth::user()->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order = $orderID;
                    $userCoupon->save();
                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            \Midtrans\Config::$serverKey = $midtrans_secret;
            \Midtrans\Config::$isProduction = $payment_setting['midtrans_mode'] === 'live';
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderID,
                    'gross_amount' => round($get_amount),
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'last_name' => '',
                    'email' => Auth::user()->email,
                    'phone' => '8787878787',
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

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
                    'payment_type' => __('Midtrans'),
                    'payment_status' => 'pending',
                    'receipt' => null,
                    'user_id' => Auth::user()->id,
                ]
            );

            Utility::add_referal_settings($plan);

            $data = [
                'snap_token' => $snapToken,
                'midtrans_secret' => $midtrans_secret,
                'order_id' => $orderID,
                'plan_id' => $plan->id,
                'amount' => $get_amount,
                'frequency' => $request->midtrans_payment_frequency,
                'fallback_url' => 'plan.get.midtrans.status'
            ];

            return view('midtras.payment', compact('data'));
        } else {
            return redirect()->route('plans')->with('error', __('Plan is deleted.'));
        }
    }

    public function planMidtransStatus(Request $request)
    {
        $user = \Auth::user();
        $orderID = $request->order_id;
        $json = $request->json;
        $response = json_decode($json, true);

        // Example: Check response status from Midtrans
        if (isset($response['status_code']) && $response['status_code'] == '200') {
            $order = Order::where('order_id', $orderID)->first();

            if ($order) {
                // Check payment status from Midtrans response
                $paymentStatus = $response['transaction_status'] ?? 'failed';

                if ($paymentStatus === 'success' || $paymentStatus === 'capture') {
                    // Payment successful
                    $order->payment_status = 'success';
                    $order->txn_id = $response['transaction_id'];
                    $order->save();

                    $plan = Plan::find($order->plan_id);
                    $assignPlan = $user->assignPlan($plan->id, $user->id, $request->frequency);
                    Utility::add_referal_settings($plan);

                    if ($assignPlan['is_success']) {
                        return redirect()->route('plans')->with('success', __('Plan activated Successfully.'));
                    } else {
                        return redirect()->route('plans')->with('error', __($assignPlan['error']));
                    }
                } else {
                    // Payment failed or canceled
                    return redirect()->route('plans')->with('error', __('Transaction has been failed or canceled.'));
                }
            }
        } else {
            // Unexpected status code
            return redirect()->route('plans')->with('error', __('Transaction has been failed or canceled.'));
        }
    }
}
