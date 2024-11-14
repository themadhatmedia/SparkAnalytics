<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\CinetPay\Events\CinetPayPaymentStatus;

class CinetPayController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public $cinetpay_api_key;
    public $cinetpay_site_id;
    public $is_cinetpay_enabled;
    public $currancy;

    public function planPayWithCinetPay(Request $request)
    {
        $payment_setting =Utility::getAdminPaymentSetting();
        $authuser = Auth::user();
        $currancy = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'XOR';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $duration = !empty($request->cinetpay_payment_frequency) ? $request->cinetpay_payment_frequency : 'Month';
        $plan_price =$plan->{$request->cinetpay_payment_frequency . "_price"};
        $stripe_session = '';


        if ($plan) {
            $get_amount = $plan->{$request->cinetpay_payment_frequency . "_price"};
            $plan->discounted_price = false;
            if ($request->coupon_code) {
                $plan_price = CheckCoupon($request->coupon_code, $plan_price);
            }
            $price = (float)$plan->{$request->cinetpay_payment_frequency . '_price'};
            if ($price <= 0) {
                $assignPlan = $authuser->assignPlan($plan->id, $authuser->id, $request->cinetpay_payment_frequency);
                if ($assignPlan['is_success']) {
                    return redirect()->route('plans')->with('success', __('Plan activated Successfully!'));
                } else {
                    return redirect()->route('plans')->with('error', __('Something went wrong, Please try again,'));
                }
            }
            try {

                if (
                    $currancy != 'XOF' &&
                    $currancy != 'CDF' &&
                    $currancy != 'USD' &&
                    $currancy != 'KMF' &&
                    $currancy != 'GNF'
                ) {
                    return redirect()->route('plans')->with('error', __('Availabe currencies: XOF, CDF, USD, KMF, GNF'));
                }

                $cinetpay_data =  [
                    "amount" => $price,
                    "currency" => $currancy,
                    "apikey" => $payment_setting['cinetpay_api_key'],
                    "site_id" => $payment_setting['cinetpay_site_id'],
                    "transaction_id" => $orderID,
                    "description" => "Plan purchase",
                    "return_url" => route('cinetpay.status'),
                    "notify_url" => route('cinetpay.status'),
                    "metadata" => "user001",
                    'customer_name' => $authuser->name ?? '',
                    'customer_surname' => $authuser->name ?? '',
                    'customer_email' => $authuser->email ?? '',
                    'customer_phone_number' => '',
                    'customer_address' =>  '',
                    'customer_city' => 'texas',
                    'customer_country' => 'BF',
                    'customer_state' => 'USA',
                    'customer_zip_code' => '',
                ];

                $curl = curl_init();


                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api-checkout.cinetpay.com/v2/payment',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 45,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($cinetpay_data),
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTPHEADER => array(
                        "content-type:application/json"
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                //On recupère la réponse de CinetPay
                $response_body = json_decode($response, true);

                if ($response_body['code'] == '201') {
                    $cinetpaySession = [
                        'order_id' => $orderID,
                        'plan_id' => $plan->id,
                        'coupon_code' => $request->coupon_code
                    ];

                    $request->session()->put('cinetpaySession', $cinetpaySession);

                    Order::create(
                        [
                            'order_id' => $orderID,
                            'name' => $authuser->name,
                            'email' => $authuser->email,
                            'card_number' => '',
                            'card_exp_month' => '',
                            'card_exp_year' => '',
                            'plan_name' => $plan->name ?? '',
                            'plan_id' => $plan->id ?? '',
                            'price' => $get_amount ?? 0,
                            'price_currency' => $currancy,
                            'txn_id' => '',
                            'payment_type' => __('CinetPay'),
                            'payment_status' => 'pending',
                            'receipt' => '',
                            'user_id' => $authuser->id,
                        ]
                    );

                    $payment_link = $response_body["data"]["payment_url"]; // Retrieving the payment URL
                    return redirect($payment_link);
                } else {
                    return back()->with('error', $response_body["description"]);
                }
            } catch (\Exception $e) {
                Log::debug($e->getMessage());
                return redirect()->route('plans')->with('error', $e->getMessage());
            }
            return view('stripe::plan.request', compact('stripe_session'));
        } else {
            return redirect()->route('plans')->with('error', __('Plan is deleted.'));
        }
    }
    public function planCinetPayReturn(Request $request)
    {
        $cinetpaySession = $request->session()->get('cinetpaySession');
        $request->session()->forget('cinetpaySession');

        if (isset($request->transaction_id) || isset($request->token)) {
            $payment_setting = getAdminPaymentSetting();

            $cinetpay_check = [
                "apikey" => $payment_setting['cinetpay_api_key'],
                "site_id" => $payment_setting['cinetpay_site_id'],
                "transaction_id" => $request->transaction_id
            ];

            $response = $this->getPayStatus($cinetpay_check);

            $response_body = json_decode($response, true);

            if ($response_body['code'] == '00') {


                $Order = Order::where('order_id', $request->order_id)->first();
                $Order->payment_status = 'succeeded';
                $Order->save();

                $authuser = User::find(Auth::user()->id);
                $plan = Plan::find($request->plan_id);
                $assignPlan = $authuser->assignPlan($plan->id, $cinetpaySession['duration'], $cinetpaySession['user_module'], $cinetpaySession['counter']);
                if ($request->coupon_code) {
                    UserCoupon($request->coupon_code, $request->order_id);
                }
                $type = 'Subscription';

                if ($assignPlan['is_success']) {
                    Utility::add_referal_settings($plan);
                    return redirect()->route('plans')->with('success', __('Plan activated Successfully!'));
                } else {
                    return redirect()->route('plans')->with('error', __($assignPlan['error']));
                }
            } else {

                return redirect()->route('plans')->with('error', __('Your Payment has failed!'));
            }
        } else {
            return redirect()->route('plans')->with('error', __('Your Payment has failed!'));
        }
    }
}
