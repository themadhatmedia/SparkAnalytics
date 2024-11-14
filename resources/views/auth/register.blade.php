<?php $ln = $lang; ?>
@extends('layouts.auth')
@section('page-title')
    {{ __('Register') }}
@endsection
@php
    $currantLang = basename(App::getLocale());
    $lang_name = \App\Models\Utility::get_lang_name($currantLang);
    $landingPageSettings = Modules\LandingPage\Entities\LandingPageSetting::settings();
    $setting = \App\Models\Utility::settings();
@endphp


@section('lang-selectbox')
    <li class="dropdown dash-h-item drp-language">
        <a class="dash-head-link dropdown-toggle btn " href="#" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="drp-text">{{ Str::ucfirst($lang_name) }}
            </span>
        </a>
        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end " data-bs-popper="static">
            @foreach (\App\Models\Utility::languages() as $code => $language)
                <a href="{{ url('register', [$ref, $code]) }}" tabindex="0"
                    @if ($lang == $code) selected @endif class="dropdown-item">
                    <span>{{ ucfirst($language) }}</span>
                </a>
            @endforeach
        </div>
    </li>
@endsection


@section('content')
    <div class="card-body">
        <div>
            <h2 class="mb-3 f-w-600">{{ __('Register') }}</h2>
        </div>
        {{ Form::open(['route' => array('store',['plan'=>$plan]), 'method' => 'post', 'id' => 'loginForm', 'class'=>'needs-validation','novalidate']) }}

        @if (session('status'))
            <div class="mb-4 font-medium text-lg text-green-600 text-danger">
                {{ __('Email SMTP settings does not configured so please contact to your site admin.') }}
            </div>
        @endif

        @if (session('Invalidererral'))
            <div class="mb-4 font-medium text-lg text-green-600 text-danger">
                {{ __('Invalid Refferal Link') }}
            </div>
        @endif

        <div class="custom-login-form">
            <div class="form-group mb-2">
                <label class="form-label">{{ __('Full Name') }}</label>
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Username'), 'required'=>'required']) }}
            </div>
            @error('name')
                <span class="error invalid-name text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="form-group mb-2">
                <label class="form-label">{{ __('Email') }}</label>
                {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Email address'),'required'=>'required']) }}
            </div>
            @error('email')
                <span class="error invalid-email text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="form-group mb-2">
                <label class="form-label">{{ __('Password') }}</label>
                {{ Form::password('password', ['class' => 'form-control', 'id' => 'input-password', 'placeholder' => __('Password'),'required'=>'required']) }}
            </div>
            @error('password')
                <span class="error invalid-password text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="form-group mb-2">
                <label class="form-control-label mb-2">{{ __('Confirm password') }}</label>
                {{ Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'confirm-input-password', 'placeholder' => __('Confirm Password'),'required'=>'required']) }}

                @error('password_confirmation')
                    <span class="error invalid-password_confirmation text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-check custom-checkbox">
                <input type="checkbox" class="form-check-input" id="termsCheckbox" name="terms" required>
                <label class="form-check-label text-sm" for="termsCheckbox">{{ __('I agree to the ') }}
                    @if (is_array(json_decode($landingPageSettings['menubar_page'])) ||
                            is_object(json_decode($landingPageSettings['menubar_page'])))
                        @foreach (json_decode($landingPageSettings['menubar_page']) as $key => $value)
                            @if (in_array($value->page_slug, ['terms_and_conditions']) && isset($value->template_name))
                                <a href="{{ $value->template_name == 'page_content' ? route('custom.page', $value->page_slug) : $value->page_url }}"
                                    target="_blank">{{ $value->menubar_page_name }}</a>
                            @endif
                        @endforeach
                        {{ __('and the ') }}
                        @foreach (json_decode($landingPageSettings['menubar_page']) as $key => $value)
                            @if (in_array($value->page_slug, ['privacy_policy']) && isset($value->template_name))
                                <a href="{{ $value->template_name == 'page_content' ? route('custom.page', $value->page_slug) : $value->page_url }}"
                                    target="_blank">{{ $value->menubar_page_name }}</a>
                            @endif
                        @endforeach
                    @endif
                </label>
            </div>

            @if ($setting['recaptcha_module'] == 'on')
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
            {{-- <script> //use for another way to get code from url
                // Function to extract parameter from URL
                function getUrlParameter(name) {
                    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                    var results = regex.exec(location.search);
                    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
                };
                // Function to populate referral code into input field
                function populateReferralCode() {
                    var referralCode = getUrlParameter('ref'); // Change 'ref' to the actual parameter name used in your URL
                    if (referralCode) {
                        var referralCodeInput = document.querySelector('input[name="referrance_code"]');
                        if (referralCodeInput) {
                            referralCodeInput.placeholder = referralCode;
                            referralCodeInput.value = referralCode;
                        }
                    }
                }

                // Call the function when the page is ready
                document.addEventListener('DOMContentLoaded', function() {
                    populateReferralCode();
                });
            </script> --}}
            {{-- <input type="text" name="referrance_code" class="form-control"> --}}

            <div class="form-group mb-2">
                <input type="hidden" name="used_referrance" class="form-control" value="{{ request()->segment(2) }}">
            </div>

            <div class="d-grid">
                <button class="btn btn-primary btn-block mt-2">{{ __('Register') }}</button>
            </div>

        </div>
        <p class="mb-2 my-4 text-center">{{ __('Already have an account?') }} <a href="{{ url('login/' . "$ln") }}"
                class="my-4 text-primary">{{ __('Login') }}</a></p>
    </div>
@endsection
@push('custom-scripts')
    @if (isset($setting['recaptcha_module']) && $setting['recaptcha_module'] == 'on')
        @if (isset($setting['google_recaptcha_version']) && $setting['google_recaptcha_version'] == 'v2')
            {!! NoCaptcha::renderJs() !!}
        @else
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
    <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
@endpush
