<?php

namespace App\Http\Controllers\Auth;

use App\Events\VerifyReCaptchaToken;
use App\Http\Controllers\Controller;
use App\Mail\SendUser;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use App\Models\Utility;
use App\Models\Settings;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Modules\LandingPage\Entities\LandingPageSetting;

class RegisteredUserController extends Controller
{
    public function __construct()
    {
        if (!file_exists(storage_path() . "/installed")) {
            header('location:install');
            die;
        }

        $settings = Utility::settings();

        if ($settings['recaptcha_module'] == 'on') {
            config(['captcha.secret' => $settings['google_recaptcha_secret']]);
            config(['captcha.sitekey' => $settings['google_recaptcha_key']]);
        }
    }
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function generateReferralCode()
    {

        $referrance_code = random_int(100000, 999999);
        while (User::where('referrance_code', $referrance_code)->exists()) {
            $referrance_code = random_int(100000, 999999); // Generate new referral code until unique
        }
        return $referrance_code;
    }
    public function store(Request $request, $frequency  = ''  ,$code  = '' )
    {
        if(isset($request->plan)){
            try {
                $plan = \Illuminate\Support\Facades\Crypt::decrypt($request->plan);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                $plan = 0;
            }
        }
        $referrance_code = $this->generateReferralCode();
        $setting = \App\Models\Utility::settings();

        if(isset($setting['recaptcha_module']) && $setting['recaptcha_module'] == 'on')
        {
            if($setting['google_recaptcha_version'] == 'v2'){
                $validation['g-recaptcha-response'] = 'required';
            }
            elseif($setting['google_recaptcha_version'] == 'v3')
            {
                $result = event(new VerifyReCaptchaToken($request));
                if (!isset($result[0]['status']) || $result[0]['status'] != true) {
                    $key = 'g-recaptcha-response';
                    $request->merge([$key => null]); // Set the key to null

                    $validation['g-recaptcha-response'] = 'required';
                }
            }else{
                $validation = [];
            }
        }else{
            $validation = [];
        }
        $this->validate($request, $validation);


        $settings = Utility::settings();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        if ($settings['email_verification'] == 'on') {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => 'company',
                'created_by' => 1,
                'plan' => 1,
                'plan_expire_date' => Carbon::now()->addDays(5),
                'referrance_code' => $referrance_code,
                'used_referrance' => $request->used_referrance,
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => 'company',
                'created_by' => 1,
                'plan' => 1,
                'email_verified_at' => Carbon::now(),
                'plan_expire_date' => Carbon::now()->addDays(5),
                'referrance_code' => $referrance_code,
                'used_referrance' => $request->used_referrance
            ]);
        }
        try {
            Utility::getSMTPDetails(1);
            event(new Registered($user));
            $role_r = Role::findByName('company');
            $user->assignRole($role_r);
        } catch (\Exception $e) {
            $user->delete();
            return redirect('/register/lang?')->with('status', __('Email SMTP settings does not configure so please contact to your site admin.'));
        }

        Auth::login($user);

        if ($settings['email_verification'] == 'off') {
            try {
                Utility::getSMTPDetails(1);
                Mail::to($user)->send(new SendUser($request->email, $request->password));
            } catch (\Throwable $th) {
                $smtp_error['status'] = false;
                $smtp_error['msg'] = $th->getMessage();
            }
        }

        if(isset($plan) && !empty($plan)){
            return redirect()->route('payment',['monthly',\Illuminate\Support\Facades\Crypt::encrypt($plan)]);
        }
        return redirect(RouteServiceProvider::HOME);
    }

    public function showregisterForm(Request $request ,$ref = '' , $lang = '')
    {

        if ($lang == '') {
            $settings = Utility::settings();
            $lang = $settings['default_language'];
        }

        if ($lang == 'ar' || $lang == 'he') {
            $value = 'on';
        } else {
            $value = "off";
        }

        $setting = Settings::updateOrCreate(
            ['name' => 'SITE_RTL', 'created_by' => 1],
            ['name' => 'SITE_RTL', 'value' => $value, 'created_by' => 1]
        )->get();

        \App::setLocale($lang);

        $landingPageSettings = LandingPageSetting::settings();
        $keyArray = [];

        if (
            is_array(json_decode($landingPageSettings['menubar_page'])) ||
            is_object(json_decode($landingPageSettings['menubar_page']))
        ) {
            foreach (json_decode($landingPageSettings['menubar_page']) as $key => $value) {
                if (in_array($value->menubar_page_name, ['Terms and Conditions']) || in_array($value->menubar_page_name, ['Privacy Policy'])) {
                    $keyArray[] = $value->menubar_page_name;
                }
            }
        }

        $plan = null;
        if($request->value){
            $plan = isset($request->value) ? $request->value : null;
        }

        if($ref == ''){
            $ref = 0;
        }
        
        if ($ref) {
            $hasRef = $ref;
            $validRef = User::where('referrance_code', $hasRef)->count();
            if ($validRef > 0) {
                return view('auth.register', compact('lang', 'ref','plan'))->with(['landingPageSettings' => $landingPageSettings, 'keyArray' => $keyArray]);
            } else {
                return redirect('/register/' . $lang)->with('Invalidererral', __('Invalide referral code'));
            }
            if (Utility::getValByName('signup') == 'on') {
                return view('auth.register', compact('lang','plan', 'ref', 'landingPageSettings','keyArray'));
            }
        } else {
            return view('auth.register', compact('lang','plan', 'ref'));
        }

        return view('auth.register', compact('lang','plan', 'ref'));
    }
}
