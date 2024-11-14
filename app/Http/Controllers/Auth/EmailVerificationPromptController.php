<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Models\Settings;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME)
                    : view('auth.verify-email');
    }

    public function showverifyform($lang = ''){  

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
        return view('auth.verify-email',compact('lang'));
    }
}
