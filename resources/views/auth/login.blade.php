<?php $ln = $lang; ?>
@extends('layouts.auth')
@section('page-title')
    {{ __('Login') }}
@endsection
@php
    use App\Models\Utility;
    $logo = \App\Models\Utility::get_file('uploads/logo');
    $settings = Utility::settings();
    $company_logo = $settings['company_logo'] ?? '';
@endphp

@push('custom-scripts')
    @if ($settings['recaptcha_module'] == 'on')
        {!! NoCaptcha::renderJs() !!}
    @endif
@endpush

@php
    $currantLang = basename(App::getLocale());
    $lang_name = \App\Models\Utility::get_lang_name($currantLang);
    $setting = \App\Models\Utility::settings();
    if (isset($setting['recaptcha_module']) && $setting['recaptcha_module'] == 'on') {
        config([
            'captcha.secret' => $setting['google_recaptcha_secret'],
            'captcha.sitekey' => $setting['google_recaptcha_key'],
            'options' => [
                'timeout' => 30,
            ],
        ]);
    }
@endphp


@section('lang-selectbox')
    <li class="dropdown dash-h-item drp-language">
        <a class="dash-head-link dropdown-toggle btn " href="#" data-bs-toggle="dropdown" aria-expanded="false  ">
            <span class="drp-text">{{ Str::ucfirst($lang_name) }}
            </span>
        </a>
        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end " data-bs-popper="static">
            @foreach (\App\Models\Utility::languages() as $code => $language)
                <a href="{{ url('login/' . $code) }}" tabindex="0" @if ($lang == $code) selected @endif
                    class="dropdown-item">
                    <span>{{ Str::ucfirst($language) }}</span>
                </a>
            @endforeach
        </div>
    </li>
@endsection


@section('content')
    <div class="card-body">
        <div>
            <h2 class="mb-3 f-w-600">{{ __('Login') }}</h2>
        </div>

        @if (Session::has('error'))
            <span class="error invalid-email text-danger" role="alert">
                <strong>{!! session('error') !!}</strong>
            </span>
            {{ Session::forget('error') }}
        @endif

        {{ Form::open(['route' => 'login', 'method' => 'post', 'id' => 'loginForm', 'class' => 'login-form needs-validation', 'novalidate']) }}
        <div class="custom-login-form">
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Email') }}</label>
                {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Your Email'), 'id' => 'email', 'required' => 'required']) }}
                @error('email')
                    <span class="error invalid-email text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required placeholder="{{ __('Enter Your Password') }}">
                @error('password')
                    <span class="error invalid-password text-danger" role="alert">
                        <small>{{ $message }}</small>
                    </span>
                @enderror
                @if (Route::has('password.request'))
                    <div class="mb-2 ms-2 mt-3">
                        <a href="{{ url('forgot-password/' . "$ln") }}"
                            class="text-primary text-underline--dashed border-primary">
                            {{ __('Forgot Your Password?') }}</a>
                    </div>
                @endif
                @error('password')
                    <span class="error invalid-password text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
           
            @if (isset($setting['recaptcha_module']) && $setting['recaptcha_module'] == 'on')
                <div class="form-group col-lg-12 col-md-12 mt-3">
                    @if (isset($setting['google_recaptcha_version']) && $setting['google_recaptcha_version'] == 'v2')
                        <div class="form-group col-lg-12 col-md-12 mt-3">
                            {!! NoCaptcha::display() !!}
                            @error('g-recaptcha-response')
                                <span class="error small text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @else
                        <div class="form-group col-lg-12 col-md-12 mt-3">
                            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response"
                                class="form-control">
                            @error('g-recaptcha-response')
                                <span class="error small text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @endif
                </div>
            @endif
            <div class="d-grid">
                {{ Form::submit(__('Login'), ['class' => 'btn btn-primary btn-block mt-2', 'id' => 'saveBtn']) }}
            </div>
            @if ($setting['SIGNUP'] == 'on')
                <p class="my-4 text-center">{{ __('Dont have an account?') }}
                    <a href="{{ url('register/') }}" class="my-4 text-primary">{{ __('Register') }}</a>
                </p>
            @endif
            {{ Form::close() }}
        </div>
    </div>
@endsection


@push('custom-scripts')
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#loginForm").submit(function(e) {
                $("#saveBtn").attr("disabled", true);
                return true;
            });
        });
    </script>
    @if (isset($setting['recaptcha_module']) && $setting['recaptcha_module'] == 'on')
        @if (isset($setting['google_recaptcha_version']) && $setting['google_recaptcha_version'] == 'v2')
            {!! NoCaptcha::renderJs() !!}
        @else
            @if (isset($setting['google_recaptcha_key']))
                <script src="https://www.google.com/recaptcha/api.js?render={{ $setting['google_recaptcha_key'] }}"></script>
                <script>
                    $(document).ready(function() {
                        grecaptcha.ready(function() {
                            grecaptcha.execute('{{ $setting['google_recaptcha_key'] }}', {
                                action: 'submit'
                            }).then(function(token) {
                                $('#g-recaptcha-response').val(token);
                            });
                        });
                    });
                </script>
            @endif
        @endif
    @endif
@endpush
