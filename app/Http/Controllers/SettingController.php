<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\EmailTest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use App\Models\Coupon;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use App\Models\Settings;
use App\Models\Site;
use App\Models\User;
use App\Models\PlanRequest;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        if (\Auth::user()->user_type == 'owner' || \Auth::user()->user_type == 'super admin') {
            $settings = Utility::settings();
            $payment = Utility::set_payment_settings();
            $path = storage_path() . '/' . 'framework/';
            $size = \File::size(storage_path('/framework'));
            $file_size = 0;
            foreach (\File::allFiles(storage_path('/framework')) as $file) {
                $file_size += $file->getSize();
            }
            $file_size = number_format($file_size / 1000000, 4);



            return view('setting', compact('settings', 'payment', 'file_size'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {

        $user = Auth::user();
        $post = $request->all();
        if ($user->user_type == 'super admin') {

            if ($request->favicon) {
                $request->validate(
                    [
                        'favicon' => 'image',
                    ]
                );
                $validation = [
                    'mimes:' . 'png,jpg',
                    'max:' . '20480',
                ];
                $logoName = 'favicon.png';
                $dir = 'logo/';

                $path = Utility::upload_file($request, 'favicon', $logoName, $dir, []);
                if ($path['flag'] == 1) {
                    $favicon = $path['url'];
                    Settings::updateOrCreate(
                        ['created_by' => Auth::user()->id, 'name' => 'favicon'],
                        ['created_by' => Auth::user()->id, 'name' => 'favicon', 'value' => $logoName]
                    );
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }


            if ($request->dark_logo) {

                $request->validate(
                    [
                        'dark_logo' => 'image|mimes:png|max:20480',
                    ]
                );
                $logoName = 'logo-dark.png';
                $dir = 'logo/';

                $validation = [
                    'mimes:' . 'png,jpg',
                    'max:' . '20480',
                ];

                $path = Utility::upload_file($request, 'dark_logo', $logoName, $dir, $validation);

                if ($path['flag'] == 1) {
                    $dark_logo = $path['url'];
                    Settings::updateOrCreate(
                        ['created_by' => Auth::user()->id, 'name' => 'dark_logo'],
                        ['created_by' => Auth::user()->id, 'name' => 'dark_logo', 'value' => $logoName]
                    );
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            if ($request->light_logo) {

                $request->validate(
                    [
                        'light_logo' => 'image',
                    ]
                );
                $logoName     = 'logo-light.png';
                $dir = 'logo/';
                $validation = [
                    'mimes:' . 'png,jpg',
                    'max:' . '20480',
                ];
                $path = Utility::upload_file($request, 'light_logo', $logoName, $dir, $validation);
                if ($path['flag'] == 1) {
                    $light_logo = $path['url'];

                    Settings::updateOrCreate(
                        ['created_by' => Auth::user()->id, 'name' => 'light_logo'],
                        ['created_by' => Auth::user()->id, 'name' => 'light_logo', 'value' => $logoName]
                    );
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            $rules = [

                'footer_text' => 'string|max:50',
            ];

            $request->validate($rules);

            $request->is_sidebar_transperent;

            if (
                !empty($request->header_text) || !empty($request->SITE_RTL) || !empty($request->footer_text)  ||  isset($request->display_landing) || !isset($request->color) || !empty($request->cust_theme_bg) ||
                !empty($request->cust_darklayout || !empty($request->email_verification))
            ) {

                $post = $request->all();
                unset($post['_token']);
                if ($request->light_logo) {
                    unset($post['light_logo']);
                }
                if ($request->dark_logo) {
                    unset($post['dark_logo']);
                }
                if ($request->favicon) {
                    unset($post['favicon']);
                }
                $post['cust_theme_bg'] = (!empty($request->cust_theme_bg) && $request->cust_theme_bg == 'on') ? $request->cust_theme_bg : 'on';
                $post['cust_darklayout'] = (!empty($request->cust_darklayout) && $request->cust_darklayout == 'on') ? $request->cust_darklayout : 'off';
                // $post['color'] = (!empty($request->color) &&  $request->has('color')) ? $request->color : 'theme-3';
                if (isset($request->color) && $request->color_flag == 'false') {
                    $post['color'] = $request->color;
                } else {
                    $post['color'] = $request->custom_color;
                }


                $post['SITE_RTL'] = (!empty($request->SITE_RTL)) ? 'on' : 'off';
                $post['email_verification'] = (!empty($request->email_verification)) ? 'on' : 'off';

                if (!isset($request->SIGNUP)) {
                    $post['SIGNUP'] = 'off';
                }

                if (!isset($request->display_landing)) {

                    $post['display_landing'] = 'off';
                }
                if (!isset($request->cust_theme_bg)) {
                    $cust_theme_bg         = (isset($request->cust_theme_bg)) ? 'on' : 'off';
                    $post['cust_theme_bg'] = $cust_theme_bg;
                }

                if (!isset($request->cust_darklayout)) {
                    $post['cust_darklayout'] = 'off';
                }
                $settings = Utility::settings();
                foreach ($post as $key => $data) {

                    if (in_array($key, array_keys($settings))) {
                        $setting = Settings::updateOrCreate(
                            ['name' => $key],
                            ['name' => $key, 'value' => $data, 'created_by' => \Auth::user()->id]
                        )->get();
                    }
                }
            }

            return redirect()->back()->with('success', __('Setting updated successfully'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }


    public function emailSettingStore(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type == 'super admin') {
            $rules = [
                'mail_driver' => 'required',
                'mail_host' => 'required',
                'mail_port' => 'required',
                'mail_username' => 'required',
                'mail_password' => 'required',
                'mail_encryption' => 'required',
                'mail_from_address' => 'required',
                'mail_from_name' => 'required',
            ];

            $post = $request->all();
            $settings = Utility::settings();
            unset($post['_token']);
            foreach ($post as $key => $data) {
                if (in_array($key, array_keys($settings)) && !empty($data)) {
                    if (!empty($data)) {
                        \DB::insert(
                            'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                            [
                                $data,
                                $key,
                                \Auth::user()->creatorId(),
                            ]
                        );
                    }
                }
            }

            return redirect()->back()->with('success', __('Setting updated successfully'));

            // not store .env file new task 16-10-23
            // $arrEnv = [
            //     'MAIL_DRIVER' => $request->mail_driver,
            //     'MAIL_HOST' => $request->mail_host,
            //     'MAIL_PORT' => $request->mail_port,
            //     'MAIL_USERNAME' => $request->mail_username,
            //     'MAIL_PASSWORD' => $request->mail_password,
            //     'MAIL_ENCRYPTION' => $request->mail_encryption,
            //     'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            //     'MAIL_FROM_NAME' => $request->mail_from_name,
            // ];

            // if ($this->setEnvironmentValue($arrEnv)) {
            //     return redirect()->back()->with('success', __('Setting updated successfully'));
            // } else {
            //     return redirect()->back()->with('error', __('Something is wrong'));
            // }
        } else {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }


    public function savePaymentSettings(Request $request)
    {
        $user = \Auth::user();

        $validator = \Validator::make(
            $request->all(),
            [
                'currency' => 'required|string|max:255',
                'currency_symbol' => 'required|string|max:255',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        } else {

            if ($user->user_type == 'super admin') {
                $arrEnv['CURRENCY_SYMBOL'] = $request->currency_symbol;
                $arrEnv['CURRENCY'] = $request->currency;

                $env = Utility::setEnvironmentValue($arrEnv);
            }

            $post['currency_symbol'] = $request->currency_symbol;
            $post['currency'] = $request->currency;
        }

        if (isset($request->is_stripe_enabled) && $request->is_stripe_enabled == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'stripe_key' => 'required|string',
                    'stripe_secret' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_stripe_enabled']     = $request->is_stripe_enabled;
            $post['stripe_secret']         = $request->stripe_secret;
            $post['stripe_key']            = $request->stripe_key;
        } else {
            $post['is_stripe_enabled'] = 'off';
        }

        if (isset($request->is_toyyibpay_enabled) && $request->is_toyyibpay_enabled == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'toyyibpay_secret_key' => 'required|string',
                    'category_code' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_toyyibpay_enabled']     = $request->is_toyyibpay_enabled;
            $post['category_code']         = $request->category_code;
            $post['toyyibpay_secret_key']            = $request->toyyibpay_secret_key;
        } else {
            $post['is_toyyibpay_enabled'] = 'off';
        }




        if (isset($request->is_payfast_enabled) && $request->is_payfast_enabled == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'payfast_merchant_id' => 'required|string',
                    'payfast_merchant_key' => 'required|string',
                    'payfast_signature' => 'required|string',
                    'payfast_mode' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_payfast_enabled']     = $request->is_payfast_enabled;
            $post['payfast_mode']     = $request->payfast_mode;
            $post['payfast_signature']         = $request->payfast_signature;
            $post['payfast_merchant_key']            = $request->payfast_merchant_key;

            $post['payfast_merchant_id']            = $request->payfast_merchant_id;
        } else {
            $post['is_payfast_enabled'] = 'off';
        }


        if (isset($request->is_paypal_enabled) && $request->is_paypal_enabled == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'paypal_mode' => 'required|string',
                    'paypal_client_id' => 'required|string',
                    'paypal_secret_key' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paypal_enabled'] = $request->is_paypal_enabled;
            $post['paypal_mode']       = $request->paypal_mode;
            $post['paypal_client_id']  = $request->paypal_client_id;
            $post['paypal_secret_key'] = $request->paypal_secret_key;
        } else {
            $post['is_paypal_enabled'] = 'off';
        }

        if (isset($request->is_paystack_enabled) && $request->is_paystack_enabled == 'on') {

            $validator = \Validator::make(
                $request->all(),
                [
                    'paystack_public_key' => 'required|string',
                    'paystack_secret_key' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paystack_enabled'] = $request->is_paystack_enabled;
            $post['paystack_public_key'] = $request->paystack_public_key;
            $post['paystack_secret_key'] = $request->paystack_secret_key;
        } else {
            $post['is_paystack_enabled'] = 'off';
        }

        if (isset($request->is_flutterwave_enabled) && $request->is_flutterwave_enabled == 'on') {

            $validator = \Validator::make(
                $request->all(),
                [
                    'flutterwave_public_key' => 'required|string',
                    'flutterwave_secret_key' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_flutterwave_enabled'] = $request->is_flutterwave_enabled;
            $post['flutterwave_public_key'] = $request->flutterwave_public_key;
            $post['flutterwave_secret_key'] = $request->flutterwave_secret_key;
        } else {
            $post['is_flutterwave_enabled'] = 'off';
        }

        if (isset($request->is_razorpay_enabled) && $request->is_razorpay_enabled == 'on') {

            $validator = \Validator::make(
                $request->all(),
                [
                    'razorpay_public_key' => 'required|string',
                    'razorpay_secret_key' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_razorpay_enabled'] = $request->is_razorpay_enabled;
            $post['razorpay_public_key'] = $request->razorpay_public_key;
            $post['razorpay_secret_key'] = $request->razorpay_secret_key;
        } else {
            $post['is_razorpay_enabled'] = 'off';
        }

        if (isset($request->is_mercado_enabled) && $request->is_mercado_enabled == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'mercado_access_token' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_mercado_enabled'] = $request->is_mercado_enabled;
            $post['mercado_access_token']     = $request->mercado_access_token;
            $post['mercado_mode'] = $request->mercado_mode;
        } else {
            $post['is_mercado_enabled'] = 'off';
        }

        if (isset($request->is_paytm_enabled) && $request->is_paytm_enabled == 'on') {

            $validator = \Validator::make(
                $request->all(),
                [
                    'paytm_mode' => 'required',
                    'paytm_merchant_id' => 'required|string',
                    'paytm_merchant_key' => 'required|string',
                    'paytm_industry_type' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paytm_enabled']    = $request->is_paytm_enabled;
            $post['paytm_mode']          = $request->paytm_mode;
            $post['paytm_merchant_id']   = $request->paytm_merchant_id;
            $post['paytm_merchant_key']  = $request->paytm_merchant_key;
            $post['paytm_industry_type'] = $request->paytm_industry_type;
        } else {
            $post['is_paytm_enabled'] = 'off';
        }

        if (isset($request->is_mollie_enabled) && $request->is_mollie_enabled == 'on') {


            $validator = \Validator::make(
                $request->all(),
                [
                    'mollie_api_key' => 'required|string',
                    'mollie_profile_id' => 'required|string',
                    'mollie_partner_id' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_mollie_enabled'] = $request->is_mollie_enabled;
            $post['mollie_api_key']    = $request->mollie_api_key;
            $post['mollie_profile_id'] = $request->mollie_profile_id;
            $post['mollie_partner_id'] = $request->mollie_partner_id;
        } else {
            $post['is_mollie_enabled'] = 'off';
        }

        if (isset($request->is_skrill_enabled) && $request->is_skrill_enabled == 'on') {



            $validator = \Validator::make(
                $request->all(),
                [
                    'skrill_email' => 'required|email',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_skrill_enabled'] = $request->is_skrill_enabled;
            $post['skrill_email']      = $request->skrill_email;
        } else {
            $post['is_skrill_enabled'] = 'off';
        }

        if (isset($request->is_coingate_enabled) && $request->is_coingate_enabled == 'on') {


            $validator = \Validator::make(
                $request->all(),
                [
                    'coingate_mode' => 'required|string',
                    'coingate_auth_token' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_coingate_enabled'] = $request->is_coingate_enabled;
            $post['coingate_mode']       = $request->coingate_mode;
            $post['coingate_auth_token'] = $request->coingate_auth_token;
        } else {
            $post['is_coingate_enabled'] = 'off';
        }

        if (isset($request->is_paymentwall_enabled) && $request->is_paymentwall_enabled == 'on') {



            $validator = \Validator::make(
                $request->all(),
                [
                    'paymentwall_public_key' => 'required',
                    'paymentwall_private_key' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paymentwall_enabled'] = $request->is_paymentwall_enabled;
            $post['paymentwall_public_key']      = $request->paymentwall_public_key;
            $post['paymentwall_private_key']       =  $request->paymentwall_private_key;
        } else {
            $post['is_paymentwall_enabled'] = 'off';
        }

        if (isset($request->is_manual_enabled) && $request->is_manual_enabled == 'on') {

            $post['is_manual_enabled'] = $request->is_manual_enabled;
        } else {
            $post['is_manual_enabled'] = 'off';
        }

        if (isset($request->is_banktransfer_enabled) && $request->is_banktransfer_enabled == 'on') {


            $validator = \Validator::make(
                $request->all(),
                [
                    'bank_details' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_banktransfer_enabled'] = $request->is_banktransfer_enabled;
            $post['bank_details'] = $request->bank_details;
        } else {
            $post['is_banktransfer_enabled'] = 'off';
        }

        if (isset($request->is_sspay_enabled) && $request->is_sspay_enabled == 'on') {

            $validator = \Validator::make(
                $request->all(),
                [
                    'sspay_secret_key' => 'required',
                    'sspay_category_code' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_sspay_enabled'] = $request->is_sspay_enabled;
            $post['sspay_secret_key'] = $request->sspay_secret_key;
            $post['sspay_category_code'] = $request->sspay_category_code;
        } else {
            $post['is_sspay_enabled'] = 'off';
        }


        if (isset($request->is_iyzipay_enabled) && $request->is_iyzipay_enabled == 'on') {

            $validator = \Validator::make(
                $request->all(),
                [
                    'iyzipay_mode' => 'required',
                    'iyzipay_key' => 'required',
                    'iyzipay_secret' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_iyzipay_enabled'] = $request->is_iyzipay_enabled;
            $post['iyzipay_mode'] = $request->iyzipay_mode;
            $post['iyzipay_key'] = $request->iyzipay_key;
            $post['iyzipay_secret'] = $request->iyzipay_secret;
        } else {
            $post['is_iyzipay_enabled'] = 'off';
        }
        if (isset($request->is_paytab_enabled) && $request->is_paytab_enabled == 'on') {

            $validator = \Validator::make(
                $request->all(),
                [
                    'paytab_profile_id' => 'required',
                    'paytab_server_key' => 'required',
                    'paytab_region' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paytab_enabled'] = $request->is_paytab_enabled;
            $post['paytab_profile_id'] = $request->paytab_profile_id;
            $post['paytab_server_key'] = $request->paytab_server_key;
            $post['paytab_region'] = $request->paytab_region;
        } else {
            $post['is_paytab_enabled'] = 'off';
        }

        if (isset($request->is_benefit_enabled) && $request->is_benefit_enabled == 'on') {

            $validator = \Validator::make(
                $request->all(),
                [
                    'benefit_api_key' => 'required',
                    'benefit_secret_key' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_benefit_enabled'] = $request->is_benefit_enabled;
            $post['benefit_api_key'] = $request->benefit_api_key;
            $post['benefit_secret_key'] = $request->benefit_secret_key;
        } else {
            $post['is_benefit_enabled'] = 'off';
        }

        if (isset($request->is_cashfree_enabled) && $request->is_cashfree_enabled == 'on') {

            $validator = \Validator::make(
                $request->all(),
                [
                    'cashfree_key' => 'required',
                    'cashfree_secret' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_cashfree_enabled'] = $request->is_cashfree_enabled;
            $post['cashfree_key'] = $request->cashfree_key;
            $post['cashfree_secret'] = $request->cashfree_secret;
        } else {
            $post['is_cashfree_enabled'] = 'off';
        }

        if (isset($request->is_aamarpay_enabled) && $request->is_aamarpay_enabled == 'on') {

            $validator = \Validator::make(
                $request->all(),
                [
                    'aamarpay_store_id' => 'required',
                    'aamarpay_signature_key' => 'required',
                    'aamarpay_description' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_aamarpay_enabled'] = $request->is_aamarpay_enabled;
            $post['aamarpay_store_id'] = $request->aamarpay_store_id;
            $post['aamarpay_signature_key'] = $request->aamarpay_signature_key;
            $post['aamarpay_description'] = $request->aamarpay_description;
        } else {
            $post['is_aamarpay_enabled'] = 'off';
        }

        if (isset($request->is_paytr_enabled) && $request->is_paytr_enabled == 'on') {

            $validator = \Validator::make(
                $request->all(),
                [
                    'paytr_merchant_id' => 'required',
                    'paytr_merchant_key' => 'required',
                    'paytr_merchant_salt' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paytr_enabled'] = $request->is_paytr_enabled;
            $post['paytr_merchant_id'] = $request->paytr_merchant_id;
            $post['paytr_merchant_key'] = $request->paytr_merchant_key;
            $post['paytr_merchant_salt'] = $request->paytr_merchant_salt;
        } else {
            $post['is_paytr_enabled'] = 'off';
        }
        if (isset($request->is_yookassa_enabled) && $request->is_yookassa_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_yookassa_enabled' => 'required',
                    'yookassa_shop_id' => 'required',
                    'yookassa_secret' => 'required',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_yookassa_enabled'] = $request->is_yookassa_enabled;
            $post['yookassa_shop_id'] = $request->yookassa_shop_id;
            $post['yookassa_secret'] = $request->yookassa_secret;
        } else {
            $post['is_yookassa_enabled'] = 'off';
        }
        if (isset($request->is_midtrans_enabled) && $request->is_midtrans_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'midtrans_mode' => 'required',
                    'is_midtrans_enabled' => 'required',
                    'midtrans_secret' => 'required',

                ]
            );
            // 'midtrans_mode' => 'required',

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_midtrans_enabled'] = $request->is_midtrans_enabled;
            $post['midtrans_mode'] = $request->midtrans_mode;

            $post['midtrans_secret'] = $request->midtrans_secret;
        } else {
            $post['is_midtrans_enabled'] = 'off';
        }
        if (isset($request->is_xendit_enabled) && $request->is_xendit_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'is_xendit_enabled' => 'required',
                    'xendit_api' => 'required',
                    'xendit_token' => 'required',

                ]
            );
            // 'midtrans_mode' => 'required',

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_xendit_enabled'] = $request->is_xendit_enabled;
            // $post['midtrans_mode'] = $request->midtrans_mode;
            $post['xendit_token'] = $request->xendit_token;
            $post['xendit_api'] = $request->xendit_api;
        } else {
            $post['is_xendit_enabled'] = 'off';
        }
        if (isset($request->is_payhere_enabled) && $request->is_payhere_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'payhere_mode' => 'required',
                    'payhere_merchant_id' => 'required',
                    'payhere_merchant_secret' => 'required',
                    'payhere_app_id' => 'required',
                    'payhere_app_secret' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_payhere_enabled'] = $request->is_payhere_enabled;
            $post['payhere_mode'] = $request->payhere_mode;
            $post['payhere_merchant_id']       = $request->payhere_merchant_id;
            $post['payhere_merchant_secret'] = $request->payhere_merchant_secret;
            $post['payhere_app_id'] = $request->payhere_app_id;
            $post['payhere_app_secret'] = $request->payhere_app_secret;
        } else {
            $post['is_payhere_enabled'] = 'off';
        }
        if (isset($request->is_paiementpro_enabled) && $request->is_paiementpro_enabled == 'on') {

            $validator = \Validator::make(
                $request->all(),
                [
                    'paiementpro_merchant_id' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paiementpro_enabled']    = $request->is_paiementpro_enabled;
            $post['paiementpro_merchant_id']   = $request->paiementpro_merchant_id;
        } else {
            $post['is_paiementpro_enabled'] = 'off';
        }
        if (isset($request->is_nepalste_enabled) && $request->is_nepalste_enabled == 'on') {
            $request->validate(
                [
                    'nepalste_public_key' => 'required|string',
                    'nepalste_secret_key' => 'required|string',
                ]
            );
            $post['is_nepalste_enabled'] = $request->is_nepalste_enabled;
            $post['nepalste_mode'] = $request->nepalste_mode;
            $post['nepalste_public_key'] = $request->nepalste_public_key;
            $post['nepalste_secret_key'] = $request->nepalste_secret_key;
        } else {
            $post['is_nepalste_enabled'] = 'off';
        }
        if (isset($request->is_cinetpay_enabled) && $request->is_cinetpay_enabled == 'on') {
            $request->validate(
                [
                    'cinetpay_api_key' => 'required|string',
                    'cinetpay_site_id' => 'required|string',
                    ]
                );
            $post['is_cinetpay_enabled'] = $request->is_cinetpay_enabled;
            $post['cinetpay_api_key'] = $request->cinetpay_api_key;
            $post['cinetpay_site_id'] = $request->cinetpay_site_id;
        } else {
            $post['is_cinetpay_enabled'] = 'off';
        }
         if (isset($request->is_fedapay_enabled) && $request->is_fedapay_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'company_fedapay_mode' => 'required',
                    'fedapay_public_key' => 'required',
                    'fedapay_secret_key' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_fedapay_enabled'] = $request->is_fedapay_enabled;
            $post['company_fedapay_mode'] = $request->company_fedapay_mode;
            $post['fedapay_public_key'] = $request->fedapay_public_key;
            $post['fedapay_secret_key'] = $request->fedapay_secret_key;

        } else {
            $post['is_fedapay_enabled'] = 'off';
        }
        if (isset($request->is_tap_enabled) && $request->is_tap_enabled == 'on') {
            $request->validate(
                [
                    'tap_secret' => 'required|string',
                    ]
                );
            $post['is_tap_enabled'] = $request->is_tap_enabled;
            $post['tap_secret'] = $request->tap_secret;
        } else {
            $post['is_tap_enabled'] = 'off';
        }
        if (isset($request->is_authorizenet_enabled) && $request->is_authorizenet_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'authorizenet_mode' => 'required',
                    'authorizenet_client_id' => 'required',
                    'authorizenet_secret_key' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_authorizenet_enabled'] = $request->is_authorizenet_enabled;
            $post['authorizenet_mode'] = $request->authorizenet_mode;
            $post['authorizenet_client_id'] = $request->authorizenet_client_id;
            $post['authorizenet_secret_key'] = $request->authorizenet_secret_key;

        } else {
            $post['is_authorizenet_enabled'] = 'off';
        }
        if (isset($request->is_ozow_enabled) && $request->is_ozow_enabled == 'on') {
            $validator = Validator::make(
                $request->all(),
                [
                    'ozow_mode' => 'required',
                    'ozow_site_key' => 'required',
                    'ozow_private_key' => 'required',
                    'ozow_api_key' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_ozow_enabled'] = $request->is_ozow_enabled;
            $post['ozow_mode'] = $request->ozow_mode;
            $post['ozow_site_key'] = $request->ozow_site_key;
            $post['ozow_private_key'] = $request->ozow_private_key;
            $post['ozow_api_key'] = $request->ozow_api_key;

        } else {
            $post['is_ozow_enabled'] = 'off';
        }
            if (isset($request->is_khalti_enabled) && $request->is_khalti_enabled == 'on') {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'khalti_public_key' => 'required',
                        'khalti_secret_key' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $post['is_khalti_enabled'] = $request->is_khalti_enabled;
                $post['khalti_public_key'] = $request->khalti_public_key;
                $post['khalti_secret_key'] = $request->khalti_secret_key;
            } else {
                $post['is_khalti_enabled'] = 'off';
            }
        foreach ($post as $key => $data) {
            $arr = [
                $data,
                $key,
                \Auth::user()->id,
            ];
            $insert_payment_setting = \DB::insert(
                'insert into admin_payment_settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }

        return redirect()->back()->with('success', __('Settings updated successfully.'));
    }

    public function pusherSettingStore(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type == 'super admin') {
            $rules = [];

            if ($request->enable_chat == 'yes') {
                $rules['pusher_app_id']      = 'required|string|max:50';
                $rules['pusher_app_key']     = 'required|string|max:50';
                $rules['pusher_app_secret']  = 'required|string|max:50';
                $rules['pusher_app_cluster'] = 'required|string|max:50';
            }

            $request->validate($rules);

            $arrEnv = [
                'CHAT_MODULE' => $request->enable_chat,
                'PUSHER_APP_ID' => $request->pusher_app_id,
                'PUSHER_APP_KEY' => $request->pusher_app_key,
                'PUSHER_APP_SECRET' => $request->pusher_app_secret,
                'PUSHER_APP_CLUSTER' => $request->pusher_app_cluster,
            ];

            if ($this->setEnvironmentValue($arrEnv)) {
                return redirect()->back()->with('success', __('Setting updated successfully'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong'));
            }
        } else {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $keyPosition       = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}='{$envValue}'\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }
        $str = substr($str, 0, -1);
        $str .= "\n";

        return file_put_contents($envFile, $str) ? true : false;
    }

    public function testEmail(Request $request)
    {
        $user = Auth::user();

        if ($user->user_type == 'super admin') {
            $data                      = [];
            $data['mail_driver']       = $request->mail_driver;
            $data['mail_host']         = $request->mail_host;
            $data['mail_port']         = $request->mail_port;
            $data['mail_username']     = $request->mail_username;
            $data['mail_password']     = $request->mail_password;
            $data['mail_encryption']   = $request->mail_encryption;
            $data['mail_from_address'] = $request->mail_from_address;
            $data['mail_from_name']    = $request->mail_from_name;

            return view('test_email', compact('data'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function testEmailSend(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'mail_driver' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_from_address' => 'required',
            'mail_from_name' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        try {
            config([
                'mail.driver' => $request->mail_driver,
                'mail.host' => $request->mail_host,
                'mail.port' => $request->mail_port,
                'mail.encryption' => $request->mail_encryption,
                'mail.username' => $request->mail_username,
                'mail.password' => $request->mail_password,
                'mail.from.address' => $request->mail_from_address,
                'mail.from.name' => $request->mail_from_name,
            ]);
            Mail::to($request->email)->send(new EmailTest());
        } catch (\Exception $e) {
            return response()->json([
                'is_success' => false,
                'message' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'is_success' => true,
            'message' => __('Email send Successfully'),
        ]);
    }

    public function storageSettingStore(Request $request)
    {

        if (isset($request->storage_setting) && $request->storage_setting == 'local') {

            $request->validate(
                [

                    'local_storage_validation' => 'required',
                    'local_storage_max_upload_size' => 'required',
                ]
            );

            $post['storage_setting'] = $request->storage_setting;
            $local_storage_validation = implode(',', $request->local_storage_validation);
            $post['local_storage_validation'] = $local_storage_validation;
            $post['local_storage_max_upload_size'] = $request->local_storage_max_upload_size;
        }

        if (isset($request->storage_setting) && $request->storage_setting == 's3') {
            $request->validate(
                [
                    's3_key'                  => 'required',
                    's3_secret'               => 'required',
                    's3_region'               => 'required',
                    's3_bucket'               => 'required',
                    's3_url'                  => 'required',
                    's3_endpoint'             => 'required',
                    's3_max_upload_size'      => 'required',
                    's3_storage_validation'   => 'required',
                ]
            );
            $post['storage_setting']            = $request->storage_setting;
            $post['s3_key']                     = $request->s3_key;
            $post['s3_secret']                  = $request->s3_secret;
            $post['s3_region']                  = $request->s3_region;
            $post['s3_bucket']                  = $request->s3_bucket;
            $post['s3_url']                     = $request->s3_url;
            $post['s3_endpoint']                = $request->s3_endpoint;
            $post['s3_max_upload_size']         = $request->s3_max_upload_size;
            $s3_storage_validation              = implode(',', $request->s3_storage_validation);
            $post['s3_storage_validation']      = $s3_storage_validation;
        }

        if (isset($request->storage_setting) && $request->storage_setting == 'wasabi') {
            $request->validate(
                [
                    'wasabi_key'                    => 'required',
                    'wasabi_secret'                 => 'required',
                    'wasabi_region'                 => 'required',
                    'wasabi_bucket'                 => 'required',
                    'wasabi_url'                    => 'required',
                    'wasabi_root'                   => 'required',
                    'wasabi_max_upload_size'        => 'required',
                    'wasabi_storage_validation'     => 'required',
                ]
            );
            $post['storage_setting']            = $request->storage_setting;
            $post['wasabi_key']                 = $request->wasabi_key;
            $post['wasabi_secret']              = $request->wasabi_secret;
            $post['wasabi_region']              = $request->wasabi_region;
            $post['wasabi_bucket']              = $request->wasabi_bucket;
            $post['wasabi_url']                 = $request->wasabi_url;
            $post['wasabi_root']                = $request->wasabi_root;
            $post['wasabi_max_upload_size']     = $request->wasabi_max_upload_size;
            $wasabi_storage_validation          = implode(',', $request->wasabi_storage_validation);
            $post['wasabi_storage_validation']  = $wasabi_storage_validation;
        }

        foreach ($post as $key => $data) {
            $arr = [
                $data,
                $key,
                \Auth::user()->id,
            ];

            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }

        return redirect()->back()->with('success',  __('Storage setting successfully updated.'));
    }





    public function saveSEOSettings(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'meta_keywords' => 'required',
                'meta_description' => 'required',
                // 'meta_image' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        if (!empty($request->meta_image)) {

            if ($request->meta_image) {
                $path = storage_path('logo/' . Utility::settings()['meta_image']);
                if (!empty($path)) {
                    if (file_exists($path)) {
                        \File::delete($path);
                    }
                }
            }
            $img_name = 'meta-image.png';
            $dir = 'logo/';
            $validation = [
                'max:' . '20480',
            ];
            $path = Utility::upload_file($request, 'meta_image', $img_name, $dir, $validation);

            if ($path['flag'] == 1) {
                $logo_dark = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
            $post['meta_image']  = $img_name;
        }
        $post['meta_keywords']            = $request->meta_keywords;
        $post['meta_description']            = $request->meta_description;
        foreach ($post as $key => $data) {
            $arr = [
                $data,
                $key,
                \Auth::user()->id,
            ];
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }
        return redirect()->back()->with('success', __('SEO setting successfully updated.'));
    }


    public function CookieConsent(Request $request)
    {

        if ($request['cookie']) {
            $settings = Utility::cookies();

            if ($settings['enable_cookie'] == "on" && $settings['cookie_logging'] == "on") {
                $allowed_levels = ['necessary', 'analytics', 'targeting'];
                $levels = array_filter($request['cookie'], function ($level) use ($allowed_levels) {
                    return in_array($level, $allowed_levels);
                });
                $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
                // Generate new CSV line
                $browser_name = $whichbrowser->browser->name ?? null;
                $os_name = $whichbrowser->os->name ?? null;
                $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
                $device_type = Utility::get_device_type($_SERVER['HTTP_USER_AGENT']);

                $ip = $_SERVER['REMOTE_ADDR'];
                //$ip = '49.36.83.154';
                $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));


                $date = (new \DateTime())->format('Y-m-d');
                $time = (new \DateTime())->format('H:i:s') . ' UTC';


                $new_line = implode(',', [
                    $ip, $date, $time, json_encode($request['cookie']), $device_type, $browser_language, $browser_name, $os_name,
                    isset($query) ? $query['country'] : '', isset($query) ? $query['region'] : '', isset($query) ? $query['regionName'] : '', isset($query) ? $query['city'] : '', isset($query) ? $query['zip'] : '', isset($query) ? $query['lat'] : '', isset($query) ? $query['lon'] : ''
                ]);

                if (!file_exists(storage_path() . '/uploads/sample/data.csv')) {

                    $first_line = 'IP,Date,Time,Accepted cookies,Device type,Browser language,Browser name,OS Name,Country,Region,RegionName,City,Zipcode,Lat,Lon';
                    file_put_contents(storage_path() . '/uploads/sample/data.csv', $first_line . PHP_EOL, FILE_APPEND | LOCK_EX);
                }
                file_put_contents(storage_path() . '/uploads/sample/data.csv', $new_line . PHP_EOL, FILE_APPEND | LOCK_EX);

                return response()->json('success');
            }
            return response()->json('error');
        }
        return redirect()->back();
    }

    public function saveCookieSettings(Request $request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'cookie_title' => 'required',
                'cookie_description' => 'required',
                'strictly_cookie_title' => 'required',
                'strictly_cookie_description' => 'required',
                'more_information_description' => 'required',
                'contactus_url' => 'required',
            ]
        );

        $post = $request->all();

        unset($post['_token']);

        if ($request->enable_cookie) {
            $post['enable_cookie'] = 'on';
        } else {
            $post['enable_cookie'] = 'off';
        }
        if ($request->cookie_logging && $request->enable_cookie) {
            $post['cookie_logging'] = 'on';
        } else {
            $post['cookie_logging'] = 'off';
        }

        if ($post['enable_cookie'] == "on") {
            $post['cookie_title']            = $request->cookie_title;
            $post['cookie_description']            = $request->cookie_description;
            $post['strictly_cookie_title']            = $request->strictly_cookie_title;
            $post['strictly_cookie_description']            = $request->strictly_cookie_description;
            $post['more_information_description']            = $request->more_information_description;
            $post['contactus_url']            = $request->contactus_url;
        }

        $settings = Utility::cookies();
        foreach ($post as $key => $data) {

            if (in_array($key, array_keys($settings))) {
                $setting = Settings::updateOrCreate(
                    ['name' => $key],
                    ['name' => $key, 'value' => $data, 'created_by' => \Auth::user()->id]
                )->get();
            }
        }
        return redirect()->back()->with('success', __('Cookie setting successfully saved.'));
    }


    public function cacheSettings()
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize:clear');

        return redirect()->back()->with('success', 'Cache clear Successfully');
    }

    public function recaptcha(Request $request)
    {
        if (\Auth::user()->user_type == 'super admin') {
            $user = \Auth::user();
            $rules = [];

            if ($request->recaptcha_module == 'on') {
                $rules['google_recaptcha_key'] = 'required|string|max:50';
                $rules['google_recaptcha_secret'] = 'required|string|max:50';
            }

            $validator = \Validator::make(
                $request->all(),
                $rules
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $post = $request->all();
            $post['recaptcha_module'] = $request->recaptcha_module ?? 'no';
            $post['google_recaptcha_key'] = $request->google_recaptcha_key;
            $post['google_recaptcha_secret'] = $request->google_recaptcha_secret;
            $post['google_recaptcha_version']   = $request->google_recaptcha_version;

            unset($post['_token']);
            if (!isset($request->recaptcha_module)) {
                $post['recaptcha_module'] = 'off';
            }

            $settings = Utility::Settings();
            foreach ($post as $key => $data) {

                $setting = Settings::updateOrCreate(
                    ['name' => $key],
                    ['name' => $key, 'value' => $data, 'created_by' => \Auth::user()->id]
                )->get();
            }
            return redirect()->back()->with('success', __('Recaptcha Settings updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
