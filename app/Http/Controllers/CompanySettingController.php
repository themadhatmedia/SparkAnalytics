<?php

namespace App\Http\Controllers;

use App\Mail\EmailTest;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use App\Models\Settings;
use App\Models\Site;
use App\Models\User;
use Artisan;
use Validator;
use App\Models\PlanRequest;
use App\Models\ReportSetting;

use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CompanySettingController extends Controller
{
    public function settings()
    {

        $objUser          = Auth::user();
        
        $com_setting=Site::where('created_by', $objUser->id)->first();
        $setting = Utility::settings();
        $report_setting =  ReportSetting::where('created_by', $objUser->id)->first();

        if(\Auth::user()->can('manage company settings'))
        {
            return view('admin.user.setting', compact('setting','report_setting'));
        } else {
            return redirect()->route('dashboard')->with('error', __("You can not access site Settings!"));
        }
    }

    public function settingsStore(Request $request)
    {
       
        $objUser          = Auth::user();
        $Settings = Settings::where('created_by',$objUser->id)->first();

        if(\Auth::user()->can('manage company settings')) {
            $validate      = [];
            $validator = Validator::make(
                $request->all(),
                $validate
            );

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $post = $request->all();
            unset($post['_token']);

            if ($request->header_text) {
                $header_text = $request->header_text;
                $setting = Settings::updateOrCreate(
                    ['name' => 'header_text', 'created_by' => Auth::user()->id],
                    ['name' => 'header_text', 'value' => $header_text, 'created_by' => Auth::user()->id]
                )->get();
            }
            if ($request->footer_text) {
                $footer_text = $request->footer_text;
                $setting = Settings::updateOrCreate(
                    ['name' => 'footer_text', 'created_by' => Auth::user()->id],
                    ['name' => 'footer_text', 'value' => $footer_text, 'created_by' => Auth::user()->id]
                )->get();
            }

            if ($request->company_email) {
                $company_email = $request->company_email;
                $setting = Settings::updateOrCreate(
                    ['name' => 'company_email', 'created_by' => Auth::user()->id],
                    ['name' => 'company_email', 'value' => $company_email, 'created_by' => Auth::user()->id]
                )->get();
            }

            if ($request->company_email_from_name) {
                $company_email_from_name = $request->company_email_from_name;
                $setting = Settings::updateOrCreate(
                    ['name' => 'company_email_from_name', 'created_by' => Auth::user()->id],
                    ['name' => 'company_email_from_name', 'value' => $company_email_from_name, 'created_by' => Auth::user()->id]
                )->get();
            }
           if ($request->company_favicon) {
                    Artisan::call('cache:clear');
                    $request->validate(['company_favicon' => 'required']);
                    $logoName = 'company_favicon_' . Auth::user()->id . '.png';

                    $request->validate(
                        [
                            'company_favicon' => 'image',
                        ]
                    );

                    $validation = [
                        'mimes:' . 'png',
                        'max:' . '20480',
                    ];


                    $dir = 'logo/';
                    $path = Utility::upload_file($request, 'company_favicon', $logoName, $dir, $validation);

                    if ($path['flag'] == 1) {
                        $company_favicon = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    $company_favicon = Settings::updateOrCreate(
                        ['created_by' => Auth::user()->id, 'name' => 'company-favicon'],
                        ['created_by' => Auth::user()->id, 'name' => 'company-favicon', 'value' => $logoName]
                    );

                }

            if ($request->dark_logo) {
                Artisan::call('cache:clear');
                $request->validate(['dark_logo' => 'required']);
                $logoName = 'company_dark_logo_' . Auth::user()->id . '.png';

                $request->validate(
                    [
                        'dark_logo' => 'image',
                    ]
                );

                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];


                $dir = 'logo/';
                $path = Utility::upload_file($request, 'dark_logo', $logoName, $dir, $validation);

                if ($path['flag'] == 1) {
                    $dark_logo = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
                $dark_logo = Settings::updateOrCreate(
                    ['created_by' => Auth::user()->id, 'name' => 'company_dark_logo'],
                    ['created_by' => Auth::user()->id, 'name' => 'company_dark_logo', 'value' => $logoName]
                );
            }
            if ($request->light_logo) {
                  Artisan::call('cache:clear');
                $request->validate(['light_logo' => 'required']);
                $logoName = 'company_light_logo_' . Auth::user()->id . '.png';

                $request->validate(
                    [
                        'light_logo' => 'image',
                    ]
                );

                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];


                $dir = 'logo/';
                $path = Utility::upload_file($request, 'light_logo', $logoName, $dir, $validation);

                if ($path['flag'] == 1) {
                    $light_logo = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
                // $request->light_logo->storeAs('logo', $logoName);
                $light_logo = Settings::updateOrCreate(
                    ['created_by' => Auth::user()->id, 'name' => 'company_light_logo'],
                    ['created_by' => Auth::user()->id, 'name' => 'company_light_logo', 'value' => $logoName]
                );
              
            }

             if ($request->light_logo) 
                {
                    unset($post['light_logo']);
                }
                if ($request->dark_logo) 
                {
                    unset($post['dark_logo']);
                }
                if ($request->favicon) 
                {
                    unset($post['favicon']);
                }
                foreach ($post as $key => $name) {
                $favicon = Settings::updateOrCreate(
                    ['created_by' => $objUser->id, 'name' => $key],
                    ['created_by' => $objUser->id, 'name' => $key, 'value' => $name]
                );
                if (isset($request->color) && $request->color_flag == 'false') {
                    $post['color'] = $request->color;
                } else {
                    $post['color'] = $request->custom_color;
                }
              
                if (!isset($request->color) || !empty($request->cust_theme_bg) || !empty($request->cust_darklayout)) {
                    

                    $post['cust_theme_bg'] = (!empty($request->cust_theme_bg) && $request->cust_theme_bg == 'on') ? $request->cust_theme_bg : 'off';
                    $post['cust_darklayout'] = (!empty($request->cust_darklayout) && $request->cust_darklayout == 'on') ? $request->cust_darklayout : 'off';
                    // if (isset($request->color) && $request->color_flag == 'false') {
                    //     $post['color'] = $request->color;
                    // } else {
                    //     $post['color'] = $request->custom_color;
                    // }
                    if (!isset($request->cust_theme_bg)) {
                        $cust_theme_bg         = (isset($request->cust_theme_bg)) ? 'on' : 'off';
                        $post['cust_theme_bg'] = $cust_theme_bg;
                    }

                    if (!isset($request->cust_darklayout)) {
                        $cust_darklayout         = (isset($request->cust_darklayout)) ? 'on' : 'off';
                        $post['cust_darklayout'] = $cust_darklayout;
                    }
                    $settings = Utility::settings();
                    unset($post['_token'], $post['custom_color'], $post['light_logo'], $post['dark_logo'], $post['company_favicon'], $post['header_text'], $post['footer_text']);

                    foreach ($post as $key => $data) {
                        if (in_array($key, array_keys($settings))) {

                            $setting = Settings::updateOrCreate(
                                ['name' => $key, 'created_by' => $objUser->id],
                                ['name' => $key, 'value' => $data, 'created_by' => $objUser->id]
                            )->get();

                        }
                    }
                }

                $post['SITE_RTL'] = (!empty($request->SITE_RTL) && $request->SITE_RTL == 'on') ? $request->SITE_RTL : 'off';

                unset($post['header_text'], $post['footer_text']);

                if ($request->SITE_RTL != NULL) {
                    $SITE_RTL = $request->has('SITE_RTL') ? $request->SITE_RTL : 'off';
                    $post['SITE_RTL'] = $SITE_RTL;
                }
                foreach ($post as $key => $data) {
                    if (in_array($key, array_keys($settings))) {

                        $setting = Settings::updateOrCreate(
                            ['name' => $key, 'created_by' => Auth::user()->id],
                            ['name' => $key, 'value' => $data, 'created_by' => Auth::user()->id]
                        )->get();

                    }
                }
            }
            return redirect()->back()->with('success', __('Settings Save Successfully.!'));
        } else {
            return redirect()->route('dashboard')->with('error', __("You can't access Location settings!"));
        }
    }

    public function SystemsettingsStore(Request $request){
        $post = $request->all();
        $settings = Utility::settings();
        unset($post['_token']);

        foreach ($post as $key => $data) {

                $setting = Settings::updateOrCreate(
                    ['name' => $key, 'created_by' => Auth::user()->id],
                    ['name' => $key, 'value' => $data, 'created_by' => Auth::user()->id]
                );
            
        }

        return redirect()->back()->with('success', __('Settings Save Successfully.!'));
    }



    public function emailSettingStore(Request $request)
    {
        $user = Auth::user();
        
        $locations =  Settings::where('created_by', $user->id)->pluck('value', 'name')->toArray();
        if ($user->user_type == 'company') {
            $post = $request->all();
            
            unset($post['_token']);
            
            foreach ($post as $key => $data) {

                    $company_email_setting = Settings::updateOrCreate(
                        ['name' => $key , 'created_by' => Auth::user()->id],
                        ['name' => $key, 'value' => $data, 'created_by' => Auth::user()->id]
                    );
                }
            return redirect()->back()->with('success', __('Email Settings Save Successfully'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }
    public function testmail(Request $request)
    {

        $user = Auth::user();

        if ($user->user_type == 'company') {
            $data                      = [];
            $data['mail_driver']       = $request->mail_driver;
            $data['mail_host']         = $request->mail_host;
            $data['mail_port']         = $request->mail_port;
            $data['mail_username']     = $request->mail_username;
            $data['mail_password']     = $request->mail_password;
            $data['mail_encryption']   = $request->mail_encryption;
            $data['mail_from_address'] = $request->mail_from_address;
            $data['mail_from_name']    = $request->mail_from_name;

            return view('admin.user.test_email', compact('data'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }
    public function testmailstore(Request $request)
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
    public function saveSlackSettings(Request $request)
    {
        $post = $request->all();
        $settings = Utility::settings();
        unset($post['_token']);

        foreach ($post as $key => $data) {

                $setting = Settings::updateOrCreate(
                    ['name' => $key, 'created_by' => Auth::user()->id],
                    ['name' => $key, 'value' => $data, 'created_by' => Auth::user()->id]
                );
            
        }

        return redirect()->back()->with('success', __('Settings Save Successfully.!'));
    }

    public function savereportSettings(Request $request)
    {
        if($request->has('email_notifiation'))
        {
            $user= Auth::user();
            $check=ReportSetting::where('created_by',$user->id)->first();
            if($check)
            {
                $store=ReportSetting::where('created_by',$user->id)->first();
            }
            else
            {
                 $store=new ReportSetting();
            }
           
            $store->email_notification =$request->has('email_notifiation') ? 1 : 0;
            $store->slack_notification =$request->has('slack_notifiation') ? 1 : 0;
            $store->is_daily =$request->has('is_daily') ? 1 : 0;
            $store->is_weekly =$request->has('is_weekly') ? 1 : 0;
            $store->is_monthly =$request->has('is_monthly') ? 1 : 0;
            $store->created_by =$user->id;
            $store->save();
            return redirect()->back()->with('success', __('Settings Save Successfully.!'));
        }
        else
        {
            return redirect()->back()->with('error', "Please enable email notification.");
        }
        
    }
}
