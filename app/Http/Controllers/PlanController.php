<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\User;
use App\Models\Plan;
use App\Models\PlanRequest;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage plan')) {
            $data = Auth::user();
            $admin_payment_setting   = Utility::getAdminPaymentSetting();
            if (\Auth::user()->user_type == 'super admin') {
                $plan = Plan::get();
                return view('admin.plan.admin')->with('plans', $plan, 'admin_payment_setting');
            } elseif (\Auth::user()->user_type == 'company') {
                $plan = Plan::where('status', '1')->get();
                return view('admin.plan.company')->with('plans', $plan, 'admin_payment_setting');
            } else {
                return redirect()->route('dashboard');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function save_plan(Request $request)
    {
        if ($request->get('plan_id') == 0) {
            $validation                   =  [];
            $validation['name']           =  'required|unique:plans';
            $validation['monthly_price']  =  'required|numeric|min:0';
            $validation['annual_price']   =  'required|numeric|min:0';
            $validation['max_site']  =  'required|numeric';
            $validation['max_widget']      =  'required|numeric';
            $validation['max_user']      =  'required|numeric';


            $validator = \Validator::make(
                $request->all(),
                $validation
            );

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            if (\Auth::user()->can('create plan')) {
                $store = new Plan();
                $store->status = 1;
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            if (\Auth::user()->can('edit plan')) {
                $store = Plan::where('id', $request->get('plan_id'))->first();
                if ($store) {
                    $validation         = [];
                    $validation['name'] = 'required|unique:plans,name,' . $request->get('plan_id');
                    if ($store->id != 1) {
                        $validation['monthly_price'] = 'required|numeric|min:0';
                        $validation['annual_price']  = 'required|numeric|min:0';
                    }
                    $validation['max_site']    = 'required|numeric';
                    $validation['max_widget']        = 'required|numeric';
                    $validation['max_user']        = 'required|numeric';


                    $validator = \Validator::make(
                        $request->all(),
                        $validation
                    );

                    if ($validator->fails()) {
                        return redirect()->back()->with('error', $validator->errors()->first());
                    }
                } else {
                    return redirect()->back()->with('error', __('Something is wrong.'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        if ($request->monthly_price >= 0 || $request->annual_price >= 0) {
            $paymentSetting = Utility::getAdminPaymentSetting();

            // if ((isset($paymentSetting['is_stripe_enabled']) && $paymentSetting['is_stripe_enabled'] == 'on') || (isset($paymentSetting['is_paypal_enabled']) && $paymentSetting['is_paypal_enabled'] == 'on') || (isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on') || (isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on') || (isset($paymentSetting['is_razorpay_enabled']) && $paymentSetting['is_razorpay_enabled'] == 'on') || (isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on') || (isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on') || (isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on') || (isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on') || (isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on')) {
            $monthly_price = $request->get('annual_price');
            $annual_price = $request->get('annual_price');
            // } else {
            //     return redirect()->back()->with('error', __('Please set stripe/paypal api key & secret key for add new plan'));
            // }
        }
        $store->name = $request->get('name');
        $store->monthly_price = $request->get('monthly_price');
        $store->annual_price = $request->get('annual_price');
        if ($request->trial == 1) {
            $store->trial = $request->trial;
            $store->trial_days = !empty($request->get('trial_days')) ? $request->get('trial_days') : 0;
        } else {
            $store->trial = 0;
            $store->trial_days = 0;
        }

        $store->max_site = $request->get('max_site');
        $store->max_widget = $request->get('max_widget');
        $store->max_user = $request->get('max_user');
        $store->custom = $request->has('custom') ? 1 : 0;
        $store->analytics = $request->has('analytics') ? 1 : 0;
        $store->description = $request->get('description');
        $store->save();

        if ($store) {

            if ($request->get('plan_id') == 0) {
                return redirect()->route('plans')->with('success', __('Plan Added Successfully.'));
            } else {
                return redirect()->route('plans')->with('success', __('Plan Updated Successfully.'));
            }
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }

    public function edit_plan($id)
    {
        $data = Plan::where("id", $id)->first();
        return $data;
    }
    public function take_plan_trial(Request $request, $plan_id)
    {

        $plan = Plan::find($plan_id);
        $user = Auth::user();
        if ($plan && $user->user_type == 'company' && $user->is_trial_done == 0) {
            $assignPlan = $this->assignPlan($plan->id, $user->id);

            if ($assignPlan['is_success']) {
                $days                   = $plan->trial_days == '-1' ? '36500' : $plan->trial_days;
                $user->is_trial_done    = 1;
                $user->plan             = $plan->id;
                $user->plan_expire_date = Carbon::now()->addDays($days)->isoFormat('YYYY-MM-DD');
                $user->save();

                return redirect()->route('dashboard')->with('success', __('Your trial has been started'));
            } else {
                return redirect()->route('dashboard')->with('error', __('Your trial can not be started'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function payment(Request $request, $frequency, $code)
    {
        try {
            $admin_payment_setting = Utility::getAdminPaymentSetting();
            if (\Auth::user()->user_type == 'company') {
                if (!empty($admin_payment_setting) && collect($admin_payment_setting)->contains('on')) {
                    $planID = \Illuminate\Support\Facades\Crypt::decrypt($code);
                    $plan = Plan::find($planID);

                    if ($plan) {
                        $currencySymbol = env('CURRENCY_SYMBOL', '$');
                        $plan->price = $currencySymbol . ($frequency == 'monthly' ? $plan->monthly_price : $plan->annual_price);
                        $plan->subscription_type = $frequency == 'monthly' ? __('Per month') : __('Per Year');
                        return view('admin.plan.payment', compact('plan', 'frequency', 'admin_payment_setting'));
                    }
                    return redirect()->back()->with('error', __('Plan is deleted.'));
                } else {
                    return redirect()->back()->with('error', __('The admin has not set the payment method.'));
                }
            } else {
                return redirect()->route('dashboard')->with('error', __('Permission Denied'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Plan Not Found');
        }
    }
    public function PlanTrial($id)
    {

        if (\Auth::user()->can('buy plan') && \Auth::user()->type != 'super admin') {
            if (\Auth::user()->is_trial_done == false) {
                try {
                    $id       = Crypt::decrypt($id);
                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', __('Plan Not Found.'));
                }

                $plan = Plan::find($id);
                $user = User::where('id', \Auth::user()->id)->first();
                $currentDate = date('Y-m-d');
                $numberOfDaysToAdd = $plan->trial_days;
                $newDate = date('Y-m-d', strtotime($currentDate . ' + ' . $numberOfDaysToAdd . ' days'));

                if (!empty($plan->trial) == 1) {

                    $this->assignPlan($plan->id, $user->id);

                    $user->trial_plan = $id;
                    $user->trial_expire_date = $newDate;
                    $user->save();
                }
                return redirect()->back()->with('success', 'Your trial has been started.');
            } else {
                return redirect()->back()->with('error', __('Your Plan trial already done.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function destroy($id)
    {
        $userPlan = User::where('plan', $id)->first();
        if ($userPlan != null) {
            return redirect()->back()->with('error', __('The company has subscribed to this plan, so it cannot be deleted.'));
        }
        $plan = Plan::find($id);
        if ($plan->id == $id) {
            $plan->delete();

            return redirect()->back()->with('success', __('Plan deleted successfully'));
        } else {
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }
    public function planDisable(Request $request)
    {
        $userPlan = User::where('plan', $request->id)->first();
        if ($userPlan != null) {
            return response()->json(['error' => __('The company has subscribed to this plan, so it cannot be disabled.')]);
        }

        Plan::where('id', $request->id)->update(['status' => $request->status]);

        if ($request->status == 1) {
            return response()->json(['success' => __('Plan successfully enable.')]);
        } else {
            return response()->json(['success' => __('Plan successfully disable.')]);
        }
    }
}
