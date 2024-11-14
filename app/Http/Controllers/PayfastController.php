<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PayfastController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            $payment_setting = Utility::getAdminPaymentSetting();
            $planID = Crypt::decrypt($request->plan_id);
            $plan = Plan::find($planID);

            if ($plan) {

                $plan_amount = ($plan->{$request->payfast_payment_frequency . '_price'});

                $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
                $user = Auth::user();
                if ($request->coupon_amount >= 0 && $request->coupon_code != null) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon_code))->where('is_active', '1')->first();
                    if (!empty($coupons)) {

                        $userCoupon = new UserCoupon();
                        $userCoupon->user = $user->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order = $order_id;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();

                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }

                        $discount_value = ($plan->{$request->payfast_payment_frequency . '_price'} / 100) * $coupons->discount;
                        $plan_amount         = $plan_amount - $discount_value;

                    }
                }

                $success = Crypt::encrypt([
                    'plan' => $plan->toArray(),
                    'order_id' => $order_id,
                    'plan_amount' => $plan_amount,
                    'frequency' => $request->payfast_payment_frequency,
                ]);

                $data = array(
                    // Merchant details
                    'merchant_id' => !empty($payment_setting['payfast_merchant_id']) ? $payment_setting['payfast_merchant_id'] : '',
                    'merchant_key' => !empty($payment_setting['payfast_merchant_key']) ? $payment_setting['payfast_merchant_key'] : '',
                    'return_url' => route('payfast.payment.success',$success),
                    'cancel_url' => route('plans'),
                    'notify_url' => route('plans'),
                    // Buyer details
                    'name_first' => $user->name,
                    'name_last' => '',
                    'email_address' => $user->email,
                    // Transaction details
                    'm_payment_id' => $order_id, //Unique payment ID to pass through to notify_url
                    'amount' => $plan_amount,
                    'item_name' => $plan->name,
                );
                $passphrase = !empty($payment_setting['payfast_signature']) ? $payment_setting['payfast_signature'] : '';
                $signature = $this->generateSignature($data, $passphrase);
                $data['signature'] = $signature;

                $htmlForm = '';

                foreach ($data as $name => $value) {
                    $htmlForm .= '<input name="' . $name . '" type="hidden" value=\'' . $value . '\' />';
                }

                return response()->json([
                    'success' => true,
                    'inputs' => $htmlForm,
                ]);

            }
        }
    }
    public function generateSignature($data, $passPhrase = null)
    {
        $pfOutput = '';
        foreach ($data as $key => $val) {
            if ($val !== '') {
                $pfOutput .= $key . '=' . urlencode(trim($val)) . '&';
            }
        }

        $getString = substr($pfOutput, 0, -1);
        if ($passPhrase !== null) {
            $getString .= '&passphrase=' . urlencode(trim($passPhrase));
        }
        return md5($getString);
    }
    public function success(Request $request, $success){
        try{
            $user = Auth::user();
            $data = Crypt::decrypt($success);
            $planID = Crypt::decrypt($request->plan_id);
            $plan = Plan::find($planID);

            $order = new Order();
            $order->order_id = $data['order_id'];
            $order->name = $user->name;
            $order->card_number = '';
            $order->card_exp_month = '';
            $order->card_exp_year = '';
            $order->plan_name = $data['plan']['name'];
            $order->plan_id = $data['plan']['id'];
            $order->price = $data['plan_amount'];
            $order->price_currency = !empty(env('CURRENCY')) ? env('CURRENCY') : '$';
            $order->txn_id = $data['order_id'];
            $order->payment_type = __('PayFast');
            $order->payment_status = 'success';

            $order->receipt = "";
            $order->user_id = $user->id;

            $order->save();

            Utility::add_referal_settings($plan);
            $assignPlan = $this->assignPlan($data['plan']['id'],$user->id,$data['frequency']);
            if ($assignPlan['is_success']) {
                return redirect()->route('plans')->with('success', __('Plan activated Successfully.'));
            } else {
                return redirect()->route('plans')->with('error', __($assignPlan['error']));
            }
        }catch (Exception $e) {
            return redirect()->route('plans')->with('error', __($e->getMessage()));
        }
    }
}
