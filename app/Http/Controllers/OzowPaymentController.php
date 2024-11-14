<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OzowPaymentController extends Controller
{
    function generate_request_hash_check($inputString)
    {
        $stringToHash = strtolower($inputString);
        return $this->get_sha512_hash($stringToHash);
    }

    function get_sha512_hash($stringToHash)
    {
        return hash('sha512', $stringToHash);
    }
    public function planPayWithozow(Request $request)
    {
        $payment_setting    = Utility::getAdminPaymentSetting();
        $user               = \Auth::user();
        $currency           = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';
        $planID             = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $currency           = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';

        if ($currency !== 'ZAR') {
            return redirect()->back()->with('error', __('Transaction currency must be ZAR.'));
        }
        $plan       = Plan::find($planID);
        $orderID    = strtoupper(str_replace('.', '', uniqid('', true)));

        if ($plan) {
            $get_amount = $plan->{$request->ozow_payment_frequency . "_price"};
            $price = (float)$plan->{$request->ozow_payment_frequency . '_price'};
            $coupons    = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
            if (!empty($request->coupon)) {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun     = $coupons->used_coupon();
                    $discount_value = ($price / 100) * $coupons->discount;

                    $get_amount     = $price - $discount_value;
                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    if ($get_amount <= 0) {
                        $authuser       = \Auth::user();
                        $authuser->plan = $plan->id;
                        $authuser->save();
                        $assignPlan     = $authuser->assignPlan($plan->id, $authuser->id, $request->ozow_payment_frequency);
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
                                    'payment_type'      => 'Paiement Pro',
                                    'payment_status'    => 'success',
                                    'receipt'           => null,
                                    'user_id'           => $authuser->id,
                                ]
                            );
                            $assignPlan = $authuser->assignPlan($plan->id, $authuser->id, $request->ozow_payment_frequency);
                            return redirect()->route('plans')->with('success', __('Plan Successfully Activated'));
                        }
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            try {
                $siteCode       = isset($payment_setting['ozow_site_key']) ? $payment_setting['ozow_site_key'] : '';
                $privateKey     = isset($payment_setting['ozow_private_key']) ? $payment_setting['ozow_private_key'] : '';
                $apiKey         = isset($payment_setting['ozow_api_key']) ? $payment_setting['ozow_api_key'] : '';
                $isTest         = isset($payment_setting['ozow_mode']) && $payment_setting['ozow_mode'] == 'sandbox'  ? 'true' : 'false';
                $plan_id        = $plan->id;


                $countryCode    = "ZA";
                $currencyCode   = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';
                $amount         = $get_amount;
                $bankReference  = time() . 'FKU';
                $transactionReference = time();

                $cancelUrl  = route('ozow.status', [
                    'plan_id'       => $plan_id,
                    'amount'        => $get_amount,
                    'coupon_code'   => $request->coupon,
                ]);
                $errorUrl   = route('ozow.status', [
                    'plan_id'       => $plan_id,
                    'amount'        => $get_amount,
                    'coupon_code'   => $request->coupon,
                ]);
                $successUrl = route('ozow.status', [
                    'plan_id'       => $plan_id,
                    'amount'        => $get_amount,
                    'coupon_code'   => $request->coupon,
                ]);
                $notifyUrl  = route('ozow.status', [
                    'plan_id'       => $plan_id,
                    'amount'        => $get_amount,
                    'coupon_code'   => $request->coupon,
                ]);

                // Calculate the hash with the exact same data being sent
                $inputString    = $siteCode . $countryCode . $currencyCode . $amount . $transactionReference . $bankReference . $cancelUrl . $errorUrl . $successUrl . $notifyUrl . $isTest . $privateKey;
                $hashCheck      = $this->generate_request_hash_check($inputString);

                $data = [
                    "countryCode"           => $countryCode,
                    "amount"                => $amount,
                    "transactionReference"  => $transactionReference,
                    "bankReference"         => $bankReference,
                    "cancelUrl"             => $cancelUrl,
                    "currencyCode"          => $currencyCode,
                    "errorUrl"              => $errorUrl,
                    "isTest"                => $isTest, // boolean value here is okay
                    "notifyUrl"             => $notifyUrl,
                    "siteCode"              => $siteCode,
                    "successUrl"            => $successUrl,
                    "hashCheck"             => $hashCheck,
                ];


                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL             => 'https://api.ozow.com/postpaymentrequest',
                    CURLOPT_RETURNTRANSFER  => true,
                    CURLOPT_ENCODING        => '',
                    CURLOPT_MAXREDIRS       => 10,
                    CURLOPT_TIMEOUT         => 0,
                    CURLOPT_FOLLOWLOCATION  => true,
                    CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST   => 'POST',
                    CURLOPT_POSTFIELDS      => json_encode($data),
                    CURLOPT_HTTPHEADER      => array(
                        'Accept: application/json',
                        'ApiKey: ' . $apiKey,
                        'Content-Type: application/json'
                    ),
                ));

                $response   = curl_exec($curl);
                curl_close($curl);
                $json_attendance = json_decode($response, true);

                if (isset($json_attendance['url']) && $json_attendance['url'] != null) {
                    return redirect()->away($json_attendance['url']);
                } else {
                    return redirect()->back()->with('error',  $json_attendance['errorMessage'] ?? 'Something went wrong.');
                }
            } catch (\Exception $e) {
                \Log::debug($e->getMessage());
                return redirect()->route('plans')->with('error', __('Plan is deleted.'));
            }
        } else {
            return redirect()->route('plans')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetozowStatus(Request $request)
    {
        $authuser = \Auth::user();
        $plan = Plan::find($request->plan_id);

        if ($plan) {

            $admin_settings = Utility::getAdminPaymentSetting();
            $currency       = isset($admin_settings['currency']) ? $admin_settings['currency'] : '';
            $orderID        = strtoupper(str_replace('.', '', uniqid('', true)));
            try {
                if (isset($request['Status']) && $request['Status'] == 'Complete') {

                    Utility::add_referal_settings($plan);

                    $order = Order::create(
                        [
                            'order_id'          => $orderID ?? '',
                            'name'              => $authuser->name ?? '',
                            'email'             => $authuser->email ?? '',
                            'card_number'       => null,
                            'card_exp_month'    => null,
                            'card_exp_year'     => null,
                            'plan_name'         =>  !empty($plan->name) ? $plan->name : 'Basic Package',
                            'plan_id'           => $plan->id,
                            'price'             => !empty($request->amount) ? $request->amount : 0,
                            'price_currency'    => $currency ?? 'USD',
                            'txn_id'            => '',
                            'payment_type'      => __('Ozow'),
                            'payment_status'    => 'succeeded',
                            'receipt'           => null,
                            'user_id'           => $authuser->id,
                        ]
                    );

                    $assignPlan     = $authuser->assignPlan($plan->id, $authuser->id, $request->ozow_payment_frequency);
                    if ($request->coupon_code) {
                        $coupons = Coupon::where('code',$request->coupon_code)->get()->first();
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
                    if ($assignPlan['is_success']) {
                        return redirect()->route('plans')->with('success', __('Plan activated Successfully.'));
                    } else {
                        return redirect()->route('plans')->with('error', __($assignPlan['error']) ?? __('Somrthing Went Wrong'));
                    }
                } else {
                    return redirect()->route('plans')->with('error', __('Transaction has been failed.'));
                }
            } catch (\Exception $e) {
                return redirect()->route('plans')->with('error', __('Transaction has been failed.'));
            }
        } else {
            return redirect()->route('plans')->with('error', __('Plan is deleted.'));
        }
    }
}
