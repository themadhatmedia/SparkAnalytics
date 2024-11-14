<?php $ln=$lang;?>
@extends('layouts.auth')
@section('page-title')
    {{ __('Forgot Password') }}
@endsection
@php
    $currantLang = basename(App::getLocale());
    $lang_name=\App\Models\Utility::get_lang_name($currantLang);
    $setting = \App\Models\Utility::settings();

@endphp
@push('custom-scripts')

@if(env('RECAPTCHA_MODULE') == 'on')
{!! NoCaptcha::renderJs() !!}
@endif
@endpush
@section('lang-selectbox')
    <li class="dropdown dash-h-item drp-language">
        <a class="dash-head-link dropdown-toggle btn " href="#" data-bs-toggle="dropdown" aria-expanded="false  ">
            <span class="drp-text">{{ Str::ucfirst($lang_name) }}
            </span>
        </a>
        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end " data-bs-popper="static">
            @foreach (\App\Models\Utility::languages() as $code => $language)
                <a href="{{ url('forgot-password/' . $code) }}" tabindex="0" @if ($lang == $code) selected @endif
                    class="dropdown-item">
                    <span>{{ Str::ucfirst($language) }}</span>
                </a>
            @endforeach
        </div>
    </li>
@endsection

@section('content')

<div class="card-body">
	<div class="d-flex">
		<h2 class="mb-3 f-w-600">{{ __('Forgot Password') }}</h2>
	</div>
	@if (session('error'))
		<div class="alert alert-danger" role="alert">
			{{ session('error') }}
		</div>
	@endif
	@if (session('success'))
		<div class="alert alert-success" role="alert">
			{{ session('success') }}
		</div>
	@endif
	{{Form::open(array('route'=>'password.email','method'=>'post','id'=>'loginForm','class'=> 'login-form','class'=>'needs-validation','novalidate'))}}
	<input type="hidden" name="lang" value="{{$ln}}">
	<div class="">
		<div class="form-group mb-3">
			<label class="form-label">{{ __('E-Mail') }}</label>
			{{Form::text('email',null,array('class'=>'form-control','required'=>'required','placeholder'=>__('Enter Your Email')))}}
			@error('email')
			<span class="error invalid-email text-danger" role="alert">
				<strong>{{ $message }}</strong>
			</span>
			@enderror

		</div>
		<div class="d-grid">
			{{Form::submit(__('Send Password Reset Link'),array('class'=>'btn btn-primary btn-block mt-2','id'=>'saveBtn'))}}
		</div>
		{{ Form::close() }}


		<p class="my-4 text-center">{{ __('Back to') }}

			<a href="{{ url('login/'."$ln") }}" class="my-4 text-primary">{{ __('Login') }}</a>
		</p>

	</div>

</div>

@endsection

