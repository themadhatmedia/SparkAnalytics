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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Xendit\Xendit;
use Illuminate\Support\Str;

class XenditPaymentController extends Controller
{
    public function planPayWithXendit(Request $request)
    {

        $payment_setting = Utility::getAdminPaymentSetting();
        $xendit_api = $payment_setting['xendit_api'];
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $user = Auth::user();
        if ($plan) {
            $get_amount = $plan->{$request->xendit_payment_frequency."_price"};

            if (!empty($request->coupon)) {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    $discount_value = ($plan->{$request->xendit_payment_frequency."_price"} / 100) * $coupons->discount;
                    $get_amount = $plan->{$request->xendit_payment_frequency."_price"} - $discount_value;
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
            $response = ['orderId' => $orderID, 'user' => $user, 'get_amount' => $get_amount, 'plan' => $plan, 'currency' => $currency,'frequency'=>$request->xendit_payment_frequency];
            Xendit::setApiKey($xendit_api);
            $params = [
                'external_id' => $orderID,
                'payer_email' => Auth::user()->email,
                'description' => 'Payment for order ' . $orderID,
                'amount' => $get_amount,
                'callback_url' =>  route('plan.xendit.status'),
                'success_redirect_url' => route('plan.xendit.status', $response),
                'failure_redirect_url' => route('plans'),
            ];

            $invoice =\App\Xendit\Invoice::create($params);
            Session::put('invoice',$invoice);

            return redirect($invoice['invoice_url']);
        }
    }

    public function planGetXenditStatus(Request $request)
    {

        $payment_setting = Utility::getAdminPaymentSetting();
        $xendit_api = $payment_setting['xendit_api'];
        Xendit::setApiKey($xendit_api);

        $session = Session::get('invoice');
        $getInvoice = \App\Xendit\Invoice::retrieve($session['id']);

        $authuser = User::find($request->user);
        $plan = Plan::find($request->plan);

        if($getInvoice['status'] == 'PAID'){

            Order::create(
                [
                    'order_id' => $request->orderId,
                    'name' => null,
                    'email' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $request->get_amount == null ? 0 : $request->get_amount,
                    'price_currency' => $request->currency,
                    'txn_id' => '',
                    'payment_type' => __('Xendit'),
                    'payment_status' => 'succeeded',
                    'receipt' => null,
                    'user_id' => $request->user,
                ]
            );
            Utility::add_referal_settings($plan);
            $assignPlan = $this->assignPlan($plan->id,$authuser->id,$request->frequency);

            if($assignPlan['is_success'])
            {
                return redirect()->route('plans')->with('success', __('Plan activated Successfully!'));
            }
            else
            {
                return redirect()->route('plans')->with('error', __($assignPlan['error']));
            }
        }
    }
}
