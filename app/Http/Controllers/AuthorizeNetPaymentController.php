<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AuthorizeNetPaymentController extends Controller
{
    public function planPayWithAuthorizeNet(Request $request)
    {
        $payment_setting    = Utility::getAdminPaymentSetting();
        $user               = \Auth::user();
        $currency           = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';
        $planID             = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan               = Plan::find($planID);
        $orderID            = strtoupper(str_replace('.', '', uniqid('', true)));

        if ($plan) {
            $get_amount = $plan->{$request->authorizenet_payment_frequency . "_price"};
            $price = (float)$plan->{$request->authorizenet_payment_frequency . '_price'};
            $coupons    = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
            if (!empty($request->coupon)) {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun     = $coupons->used_coupon();
                    $discount_value = ($price / 100) * $coupons->discount;

                    $get_amount = $price - $discount_value;
                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    if ($get_amount <= 0) {
                        $authuser   = \Auth::user();
                        $authuser->plan = $plan->id;
                        $authuser->save();
                        $assignPlan = $authuser->assignPlan($plan->id,$authuser->id, $request->authorizenet_payment_frequency);
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
                            $assignPlan = $authuser->assignPlan($plan->id,$authuser->id, $request->authorizenet_payment_frequency);
                            return redirect()->route('plan.index')->with('success', __('Plan Successfully Activated'));
                        }
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            try {
                $data = [
                    'id'        =>  $plan->id,
                    'amount'    =>  $get_amount,
                    'coupon_id' =>  $coupons->id ?? '',
                ];

                return view('AuthorizeNet.request', compact('plan', 'get_amount', 'data', 'currency'));

            } catch (\Exception $e) {
                \Log::debug($e->getMessage());
                return redirect()->route('plans')->with('error', __('Plan is deleted.'));
            }
        } else {
            return redirect()->route('plans')->with('error', __('Plan is deleted.'));
        }
    }

    public function planAuthorizeNetStatus(Request $request)
    {
        $input          = $request->all();
        $admin_settings = Utility::getAdminPaymentSetting();
        $data           = json_decode($input['data'], true);
        $coupon_id      = $data['coupon_id'];
        $plan           = Plan::find($data['id']);
        $authuser       = \Auth::user();
        $orderID        = strtoupper(str_replace('.', '', uniqid('', true)));
        $admin_currancy = !empty($admin_settings['currency']) ? $admin_settings['currency'] : 'USD';


        try {
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
            $merchantAuthentication->setName($admin_settings['authorizenet_client_id']);
            $merchantAuthentication->setTransactionKey($admin_settings['authorizenet_secret_key']);
            $refId                  = 'ref' . time();
            // Create the payment data for a credit card
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($input['cardNumber']);
            $creditCard->setExpirationDate($input['year'] . '-' . $input['month']);
            $creditCard->setCardCode($input['cvv']);

            $paymentOne             = new AnetAPI\PaymentType();
            $paymentOne->setCreditCard($creditCard);
            // Create a TransactionRequestType object and add the previous objects to it
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction");
            $transactionRequestType->setAmount($data['amount']);
            $transactionRequestType->setPayment($paymentOne);
            // Assemble the complete transaction request
            $requestNet             = new AnetAPI\CreateTransactionRequest();
            $requestNet->setMerchantAuthentication($merchantAuthentication);
            $requestNet->setRefId($refId);
            $requestNet->setTransactionRequest($transactionRequestType);
        } catch (\Exception $e) {
            return redirect()->route('plan.index')->with('error', __('something Went wrong!'));
        }
        $controller = new AnetController\CreateTransactionController($requestNet);
        if (!empty($admin_settings['authorizenet_mode']) && $admin_settings['authorizenet_mode'] == 'live') {

            $response   = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION); // change SANDBOX to PRODUCTION in live mode

        } else {

            $response   = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX); // change SANDBOX to PRODUCTION in live mode
        }
        if ($response != null) {
            if ($response->getMessages()->getResultCode() == "Ok") {
                $tresponse      = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getMessages() != null) {
                    $order = Order::create([
                        'order_id'          => $orderID,
                        'name'              => $authuser->name ?? '',
                        'email'             => $authuser->email ?? '',
                        'card_number'       => '',
                        'card_exp_month'    => '',
                        'card_exp_year'     => '',
                        'plan_name'         => $plan->name ?? '',
                        'plan_id'           => $plan->id ?? '',
                        'price'             => $data['amount'] ?? 0,
                        'price_currency'    => $admin_currancy ?? '',
                        'txn_id'            => '',
                        'payment_type'      => __('Authorizenet'),
                        'payment_status'    => 'success',
                        'receipt'           => '',
                        'user_id'           => $authuser->id,
                    ]);
                    $assignPlan         = $authuser->assignPlan($plan->id,$authuser->id, $request->authorizenet_payment_frequency);

                    if (!empty($coupon_id) && $coupon_id) {
                        $coupons = Coupon::find($coupon_id);

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
                    if ($tresponse->getErrors() != null) {
                        return redirect()->route('plans')->with('error', __('Transaction Failed!'));
                    }
                }
            } else {
                $tresponse      = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    return redirect()->route('plans')->with('error', __('Transaction Failed!'));
                } else {
                    return redirect()->route('plans')->with('error', __('No response returned!'));
                }
            }
        } else {
            return redirect()->route('plans')->with('error', __('No response returned!'));
        }
    }
}
