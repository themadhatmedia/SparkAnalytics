<?php
//require_once 'google-api-php-client--PHP7.4/vendor/autoload.php';

namespace App\Http\Controllers\Auth;

use App\Events\VerifyReCaptchaToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use App\Models\Utility;
use App\Models\LoginDetails;
use Illuminate\Support\Facades\Http;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
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
    public function create($lang = '')
    {
        if ($lang == '') {
            $settings = Utility::settings();
            $lang = $settings['default_language'];
        }

        \App::setLocale($lang);

        return view('auth.login',compact('lang'));
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        
        $setting = \App\Models\Utility::settings();
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $setting['google_recaptcha_key'],
            'response' => $request['g-recaptcha-response'],
        ]);
        // dd($response->body());
        $responseBody = json_decode($response->body());

        if ($setting['recaptcha_module'] == 'on') {
            if(isset($setting['google_recaptcha_version']) == 'v2'){
                $validation['g-recaptcha-response'] = 'required';
            }
            elseif(isset($setting['google_recaptcha_version']) == 'v3')
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
        } else {
            $validation = [];
        }
        $this->validate($request, $validation);

        $request->authenticate();

        $setting = Utility::settings();

        // if(isset($setting['email_verification']) && $setting['email_verification'] == "on"){
        //     // if ($request->user()->hasVerifiedEmail()) {
        //     //     return redirect()->intended(RouteServiceProvider::HOME);
        //     // }

        //     $request->user()->sendEmailVerificationNotification();
        // }

        $request->session()->regenerate();

        if (\Auth::user()->user_status == 0) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->with('error', 'Your Account is disable,please contact your Administrate.');
        }
        $ip = $_SERVER['REMOTE_ADDR'];

        // $ip = '49.36.83.154'; // This is static ip address

        $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));

        $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
        if($whichbrowser->device->type == 'bot') {
            return;
        }

        $referrer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : null;

        /* Detect extra details about the user */
        $query['browser_name'] = $whichbrowser->browser->name ?? null;
        $query['os_name'] = $whichbrowser->os->name ?? null;
        $query['browser_language'] =isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
        $query['device_type'] = Utility::get_device_type($_SERVER['HTTP_USER_AGENT']);
        $query['referrer_host'] = !empty($referrer['host']);
        $query['referrer_path'] = !empty($referrer['path']);


        isset($query['timezone']) ? date_default_timezone_set($query['timezone']) : '';


        $json = json_encode($query);

        $user=\Auth::user();



        $currentlocation = $user->current_location;

        if($user->user_type != 'company' && $user->user_type != 'super admin')
        {


        $login_detail = LoginDetails::create([
            'user_id' =>  $user->id,
            'ip' => $ip,
            'date' => date('Y-m-d H:i:s'),
            'details' => $json,
            'created_by'=> $user->creatorId(),
            'location_id'=>$currentlocation,
        ]);
    }

        return redirect()->intended(RouteServiceProvider::HOME);
    }




     public function showLoginForm($lang = '')
     {
         if ($lang == '') {
             $settings = Utility::settings();
             $lang = $settings['default_language'];
            }

            if($lang == 'ar' || $lang =='he'){
                $value = 'on';
            }
            else
            {
                $value = "off";
            }

        $setting = Settings::updateOrCreate(
            ['name' => 'SITE_RTL', 'created_by' => 1],
            ['name' => 'SITE_RTL', 'value' => $value, 'created_by' => 1]
        )->get();

        \App::setLocale($lang);

        return view('auth.login', compact('lang'));
    }
    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }


}
