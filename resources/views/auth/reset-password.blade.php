<?php $ln = $lang; ?>
@extends('layouts.auth')
@section('page-title')
    {{ __('Reset Password') }}
@endsection

@php
    $settings = App\Models\Utility::settings();
    config([
        'captcha.secret' => $settings['google_recaptcha_secret'],
        'captcha.sitekey' => $settings['google_recaptcha_key'],
        'options' => [
            'timeout' => 30,
        ],
    ]);
    $currantLang = basename(App::getLocale());
@endphp

@section('content')
    <div class="card-body">
        <div class="">
            <h2 class="mb-3 f-w-600">{{ __('Reset Password') }}</h2>
            @if (session('status'))
                <div class="alert alert-primary">
                    {{ session('status') }}
                </div>
            @endif

        </div>
        {{ Form::open(['route' => 'password.update', 'method' => 'post','class'=>'needs-validation','novalidate','id' => 'form_data']) }}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="">
            <div class="form-group mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email', $request->email) }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="error invalid-email text-danger" role="alert">
                        <small>{{ $message }}</small>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" value="{{ old('password') }}" required autocomplete="password" autofocus>
                @error('password')
                    <span class="error invalid-password text-danger" role="alert">
                        <small>{{ $message }}</small>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                    value="{{ old('password') }}" required autocomplete="password" autofocus>
                @error('password_confirmation')
                    <span class="invalid-password_confirmation text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            @if ($settings['recaptcha_module'] == 'on')
                <div class="form-group col-lg-12 col-md-12 mt-3">
                    @if (isset($settings['google_recaptcha_version']) && $settings['google_recaptcha_version'] == 'v2')
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
                <button type="submit"
                    class="btn btn-primary btn-submit btn-block mt-2">{{ __('Reset Password') }}</button>
            </div>
        </div>
        </form>
    </div>
@endsection
