<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nwidart\Modules\Module;


class PaiementProController extends Controller
{
    public function planPayWithpaiementpro(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $paiementpro_merchant_id = isset($payment_setting['paiementpro_merchant_id']) ? $payment_setting['paiementpro_merchant_id'] : '';
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);


        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $authuser       = Auth::user();
        $coupons_id = '';
        if ($plan) {

            $get_amount = $plan->{$request->paiementpro_payment_frequency . "_price"};
            $price = (float)$plan->{$request->paiementpro_payment_frequency . '_price'};

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
                        $assignPlan = $authuser->assignPlan($plan->id, $authuser->id, $request->paiementpro_payment_frequency);

                        if ($assignPlan['is_success'] == true && !empty($plan)) {
                            if (!empty($authuser->payment_subscription_id) && $authuser->payment_subscription_id != '') {
                                try {
                                    $authuser->cancel_subscription($authuser->id);
                                } catch (\Exception $exception) {

                                    \Log::debug($exception->getMessage());
                                }
                            }

                            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

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
                                    'price_currency' => !empty($this->currancy) ? $this->currancy : 'USD',
                                    'txn_id' => '',
                                    'payment_type' => 'Paystack',
                                    'payment_status' => 'succeeded',
                                    'receipt' => null,
                                    'user_id' => $authuser->id,
                                ]
                            );
                            $res['msg'] = __("Plan successfully upgraded.");
                            $res['flag'] = 2;
                            return redirect()->route('plans')->with('success', __('Plan Successfully Activated'));
                        } else {
                            return Utility::error_res(__('Plan fail to upgrade.'));
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

        try {

            $call_back = route('paiementpro.status', $response);
            $data = array(
                'merchantId' => $paiementpro_merchant_id,
                'amount' =>  $get_amount,
                'description' => "Api PHP",
                'channel' => $request->channel,
                'countryCurrencyCode' => !empty($payment_setting['currency']) ? $payment_setting['currency'] : '',
                'referenceNumber' => "REF-" . time(),
                'customerEmail' => $authuser->email,
                'customerFirstName' => $authuser->name,
                'customerLastname' =>  $authuser->name,
                'customerPhoneNumber' => $request->mobile_number,
                'notificationURL' => $call_back,
                'returnURL' => $call_back,
                'returnContext' => json_encode([
                    'coupon_code' => $request->coupon_code,
                ]),

            );

            $data = json_encode($data);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.paiementpro.net/webservice/onlinepayment/init/curl-init.php");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response);
            // $response = json_decode($response);
            if (isset($response->success) && $response->success == true) {
                // redirect to approve href
                return redirect($response->url);

                return redirect()
                    ->route('plans', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))
                    ->with('error', 'Something went wrong. OR Unknown error occurred');
            } else {
                return redirect()
                    ->route('plans', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))
                    ->with('error', $response->message ?? 'Something went wrong.');
            }
        } catch (\Exception $e) {
            return redirect()->route('plans')->with('error', __($e));
        }
    }

    public function planGetpaiementproStatus(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';

        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        $getAmount = $request->get_amount;
        $authuser = \Auth::user();
        $plan = Plan::find($request->plan);

        $jsonData = $request->returnContext;
        $dataArray = json_decode($jsonData, true);
        if ($plan) {
            try {
                if ($request->responsecode == -1 || $request->responsecode == 0) {
                    $order = Order::create(
                        [
                            'order_id' => $orderID,
                            'name' => $authuser->name ?? '',
                            'email' => $authuser->email ?? '',
                            'card_number' => '',
                            'card_exp_month' => '',
                            'card_exp_year' => '',
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $getAmount ?? 0,
                            'price_currency' => $currency ?? 'USD',
                            'txn_id' => '',
                            'payment_type' => __('Paiement Pro'),
                            'payment_status' => 'success',
                            'receipt' => '',
                            'user_id' => $authuser->id,
                        ]
                    );
                } else {
                    return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
                }
                $data = json_encode($request->returnContext);
                $assignPlan = $authuser->assignPlan($plan->id, $authuser->id, $request->paiementpro_payment_frequency);


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
                    return redirect()->route('plans')->with('success', __('Plan activated Successfully!'));
                } else {
                    return redirect()->route('plans')->with('error', __($assignPlan['error']));
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
        }
    }
}
