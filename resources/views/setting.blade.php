@extends('layouts.admin')
<?php
$logo = \App\Models\Utility::get_file('logo/');

$color = 'theme-3';
if (!empty($settings['color'])) {
    $color = $settings['color'];
}
$SITE_RTL = 'off';
if (!empty($settings['SITE_RTL'])) {
    $SITE_RTL = $settings['SITE_RTL'];
}
$user = Auth::user();
$file_type = config('files_types');

$local_storage_validation = $settings['local_storage_validation'];
$local_storage_validations = explode(',', $local_storage_validation);

$s3_storage_validation = $settings['s3_storage_validation'];
$s3_storage_validations = explode(',', $s3_storage_validation);

$wasabi_storage_validation = $settings['wasabi_storage_validation'];
$wasabi_storage_validations = explode(',', $wasabi_storage_validation);
$flag = !empty($settings['color_flag']) ? $settings['color_flag'] : '';

$google_recaptcha_version = ['v2-checkbox' => __('v2'), 'v3' => __('v3')];
?>

@section('page-title')
    {{ __('Settings') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page">{{ __('Settings') }}</li>
@endsection
@section('content')
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top" style="top:30px">
                    <div class="list-group list-group-flush" id="useradd-sidenav">
                        <a href="#useradd-1"
                            class="list-group-item list-group-item-action border-0">{{ __('Brand Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-2"
                            class="list-group-item list-group-item-action border-0">{{ __('Email Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-4"
                            class="list-group-item list-group-item-action border-0 ">{{ __('Storage Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-3"
                            class="list-group-item list-group-item-action border-0">{{ __('Payment Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-5"
                            class="list-group-item list-group-item-action border-0">{{ __('SEO Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-6"
                            class="list-group-item list-group-item-action border-0">{{ __('Cookie Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-7"
                            class="list-group-item list-group-item-action border-0">{{ __('Cache Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-8"
                            class="list-group-item list-group-item-action border-0">{{ __('ReCaptcha Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div id="useradd-1" class="card">
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header">
                            <h5>{{ __('Brand Settings') }}</h5>
                            <small class="text-muted">{{ __('Edit your brand details') }}</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- Light Logo --}}
                                <div class="col-sm-4">
                                    <div class="card logo_card">
                                        <div class="card-header">
                                            <h5>{{ __('Light Logo') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="setting-cards">
                                                <div class="logo-contents text-center py-2 mt-3">
                                                    <a href="{{ $logo . (isset($light_logo) && !empty($light_logo) ? $light_logo . '?' . time() : 'logo-light.png' . '?' . time()) }}"
                                                        target="_blank">
                                                        <img id="light"
                                                            src="{{ $logo . 'logo-light.png' . '?' . time() }}"
                                                            class=" img_settings big-logo"
                                                            style="filter: drop-shadow(2px 3px 7px #011c4b);">
                                                    </a>
                                                </div>
                                                <div class="col-12">
                                                    <div class="choose-files mt-5">
                                                        <label for="light_logo">
                                                            <div class=" bg-primary logo_updates">
                                                                <i
                                                                    class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                            </div>
                                                            <input type="file" name="light_logo" id="light_logo"
                                                                class="form-control file d-none"
                                                                onchange="document.getElementById('light').src = window.URL.createObjectURL(this.files[0])">
                                                        </label>
                                                    </div>
                                                </div>
                                                @error('light_logo')
                                                    <div class="row">
                                                        <span class="invalid-logo" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Dark Logo --}}
                                <div class="col-sm-4">
                                    <div class="card logo_card">
                                        <div class="card-header">
                                            <h5>{{ __('Dark Logo') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="setting-card">
                                                <div class="logo-content text-center py-2 mt-3">
                                                    <a href="{{ $logo . (isset($dark_logo) && !empty($dark_logo) ? $dark_logo . '?' . time() : 'logo-dark.png' . '?' . time()) }}"
                                                        target="_blank">
                                                        <img id="dark"src="{{ $logo . 'logo-dark.png' . '?' . time() }}"
                                                            class="big-logo"></a>
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="dark_logo">
                                                        <div class="form-label bg-primary logo_update"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="dark_logo" id="dark_logo"
                                                            class="custom-input-file d-none"
                                                            onchange="document.getElementById('dark').src = window.URL.createObjectURL(this.files[0])">
                                                    </label>
                                                </div>
                                                @error('dark_logo')
                                                    <div class="row">
                                                        <span class="invalid-logo" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Favicon Logo --}}
                                <div class="col-sm-4">
                                    <div class="card logo_card">
                                        <div class="card-header">
                                            <h5>{{ __('Favicon') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="setting-card">
                                                <div class="logo-content text-center py-2 mt-3">
                                                    <a href="{{ $logo . (isset($favicon) && !empty($favicon) ? $favicon . '?' . time() : 'favicon.png' . '?' . time()) }}"
                                                        target="_blank">
                                                        <img src="{{ $logo . 'favicon.png' . '?' . time() }}"
                                                            class="img_setting" width="50px" id="blah">
                                                    </a>
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="favicon">
                                                        <div class="form-label bg-primary"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="favicon" id="favicon"
                                                            class="custom-input-file d-none"
                                                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                                    </label>
                                                </div>
                                                @error('favicon')
                                                    <div class="row">
                                                        <span class="invalid-logo" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                @error('logo')
                                    <div class="row">
                                        <span class="invalid-logo" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    </div>
                                @enderror
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('header_text', __('Title Text'), ['class' => 'col-form-label text-dark']) }}
                                        {{ Form::text('header_text', isset($settings['header_text']) ? $settings['header_text'] : 'Google Analytics SaaS', ['class' => 'form-control', 'placeholder' => __('Enter Header Title Text')]) }}
                                        @error('header_text')
                                            <span class="invalid-header_text" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">

                                        {{ Form::label('footer_text', __('Footer Text'), ['class' => 'col-form-label text-dark']) }}
                                        @php $year=date('Y'); @endphp
                                        {{ Form::text('footer_text', isset($settings['footer_text']) ? $settings['footer_text'] : '© ' . $year . ' AnalyticsGo SaaS', ['class' => 'form-control', 'placeholder' => __('Enter Footer Text')]) }}
                                        @error('footer_text')
                                            <span class="invalid-footer_text" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        {{ Form::label('default_language', __('Default Language'), ['class' => 'col-form-label text-dark']) }}
                                        <select name="default_language" id="default_language"
                                            class="form-control select2">
                                            @foreach (\App\Models\Utility::languages() as $code => $language)
                                                <option @if ($settings['default_language'] == $code) selected @endif
                                                    value="{{ $code }}">{{ Str::ucfirst($language) }}</option>
                                            @endforeach
                                        </select>
                                        @error('default_language')
                                            <span class="invalid-default_language" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group ">
                                                <label class="form-label">{{ __('Enable RTL') }}</label>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" data-toggle="switchbutton"
                                                        data-onstyle="primary" class="" name="SITE_RTL"
                                                        value="on" id="SITE_RTL"
                                                        {{ $SITE_RTL == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label" for="SITE_RTL"></label>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="col">
                                            <div class="form-group ">
                                                <label class="form-label text-dark"
                                                    for="display_landing">{{ __('Enable Landing Page') }}</label><br>
                                                <input type="checkbox" name="display_landing" class="form-check-input"
                                                    id="display_landing" data-toggle="switchbutton"
                                                    {{ $settings['display_landing'] == 'on' ? 'checked="checked"' : '' }}
                                                    data-onstyle="primary">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="form-label text-dark"
                                                    for="SIGNUP">{{ __('Enable Sign-Up Page') }}</label>
                                                <div class="">
                                                    <input type="checkbox" name="SIGNUP" id="SIGNUP"
                                                        data-toggle="switchbutton"
                                                        {{ isset($settings['SIGNUP']) && $settings['SIGNUP'] == 'on' ? 'checked="checked"' : '' }}
                                                        data-onstyle="primary">
                                                    <label class="form-check-labe" for="SIGNUP"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="text-dark form-label"
                                                    for="email_verification">{{ __('Email Verification') }}</label>
                                                <div class="">
                                                    <input type="checkbox" name="email_verification"
                                                        id="email_verification" data-toggle="switchbutton"
                                                        {{ $settings['email_verification'] == 'on' ? 'checked="checked"' : '' }}
                                                        data-onstyle="primary">
                                                    <label class="form-check-label" for="email_verification"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <h4 class="small-title">{{ __('Theme Customizer') }}</h4>
                                <div class="setting-card setting-logo-box p-3">
                                    <div class="row">
                                        <div class="pct-body">
                                            <div class="row">
                                                <div class="col-lg-4 col-xl-4 col-md-4">
                                                    <h6 class="mt-2">
                                                        <i data-feather="credit-card"
                                                            class="me-2"></i>{{ __('Primary color settings') }}
                                                    </h6>

                                                    <hr class="my-2" />
                                                    <div class="color-wrp">
                                                        <div class="theme-color themes-color">
                                                            <a href="#!"
                                                                class="themes-color-change {{ $color == 'theme-1' ? 'active_color' : '' }}"
                                                                data-value="theme-1"></a>
                                                            <input type="radio" class="theme_color d-none"
                                                                name="color"
                                                                value="theme-1"{{ $color == 'theme-1' ? 'checked' : '' }}>
                                                            <a href="#!"
                                                                class="themes-color-change {{ $color == 'theme-2' ? 'active_color' : '' }}"
                                                                data-value="theme-2"></a>
                                                            <input type="radio" class="theme_color d-none"
                                                                name="color"
                                                                value="theme-2"{{ $color == 'theme-2' ? 'checked' : '' }}>
                                                            <a href="#!"
                                                                class="themes-color-change {{ $color == 'theme-3' ? 'active_color' : '' }}"
                                                                data-value="theme-3"></a>
                                                            <input type="radio" class="theme_color d-none"
                                                                name="color"
                                                                value="theme-3"{{ $color == 'theme-3' ? 'checked' : '' }}>
                                                            <a href="#!"
                                                                class="themes-color-change {{ $color == 'theme-4' ? 'active_color' : '' }}"
                                                                data-value="theme-4"></a>
                                                            <input type="radio" class="theme_color d-none"
                                                                name="color"
                                                                value="theme-4"{{ $color == 'theme-4' ? 'checked' : '' }}>
                                                            <a href="#!"
                                                                class="themes-color-change {{ $color == 'theme-5' ? 'active_color' : '' }}"
                                                                data-value="theme-5"></a>
                                                            <input type="radio" class="theme_color d-none"
                                                                name="color"
                                                                value="theme-5"{{ $color == 'theme-5' ? 'checked' : '' }}>
                                                            <br>
                                                            <a href="#!"
                                                                class="themes-color-change {{ $color == 'theme-6' ? 'active_color' : '' }}"
                                                                data-value="theme-6"></a>
                                                            <input type="radio" class="theme_color d-none"
                                                                name="color"
                                                                value="theme-6"{{ $color == 'theme-6' ? 'checked' : '' }}>
                                                            <a href="#!"
                                                                class="themes-color-change {{ $color == 'theme-7' ? 'active_color' : '' }}"
                                                                data-value="theme-7"></a>
                                                            <input type="radio" class="theme_color d-none"
                                                                name="color"
                                                                value="theme-7"{{ $color == 'theme-7' ? 'checked' : '' }}>
                                                            <a href="#!"
                                                                class="themes-color-change {{ $color == 'theme-8' ? 'active_color' : '' }}"
                                                                data-value="theme-8"></a>
                                                            <input type="radio" class="theme_color d-none"
                                                                name="color"
                                                                value="theme-8"{{ $color == 'theme-8' ? 'checked' : '' }}>
                                                            <a href="#!"
                                                                class="themes-color-change {{ $color == 'theme-9' ? 'active_color' : '' }}"
                                                                data-value="theme-9"></a>
                                                            <input type="radio" class="theme_color d-none"
                                                                name="color"
                                                                value="theme-9"{{ $color == 'theme-9' ? 'checked' : '' }}>
                                                            <a href="#!"
                                                                class="themes-color-change {{ $color == 'theme-10' ? 'active_color' : '' }}"
                                                                data-value="theme-10"></a>
                                                            <input type="radio" class="theme_color d-none"
                                                                name="color"
                                                                value="theme-10"{{ $color == 'theme-10' ? 'checked' : '' }}>
                                                        </div>
                                                        <div class="color-picker-wrp ">
                                                            <input type="color" value="{{ $color ? $color : '' }}"
                                                                class="colorPicker {{ isset($flag) && $flag == 'true' ? 'active_color' : '' }}"
                                                                name="custom_color" id="color-picker">
                                                            <input type='hidden' name="color_flag"
                                                                value={{ isset($flag) && $flag == 'true' ? 'true' : 'false' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-xl-4 col-md-4">
                                                    <h6 class="mt-2">
                                                        <i data-feather="layout"
                                                            class="me-2"></i>{{ __('Sidebar settings') }}
                                                    </h6>
                                                    <hr class="my-2" />
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="cust-theme-bg" name="cust_theme_bg"
                                                            {{ !empty($settings['cust_theme_bg']) && $settings['cust_theme_bg'] == 'on' ? 'checked' : '' }} />
                                                        <label class="form-check-label f-w-600 pl-1"
                                                            for="cust-theme-bg">{{ __('Transparent layout') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-xl-4 col-md-4">
                                                    <h6 class="mt-2">
                                                        <i data-feather="sun"
                                                            class="me-2"></i>{{ __('Layout settings') }}
                                                    </h6>
                                                    <hr class="my-2" />
                                                    <div class="form-check form-switch mt-2">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="cust-darklayout"
                                                            name="cust_darklayout"{{ !empty($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on' ? 'checked' : '' }} />
                                                        <label class="form-check-label f-w-600 pl-1"
                                                            for="cust-darklayout">{{ __('Dark Layout') }}</label>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer ">
                                <div class="row col-12">
                                    <div class="form-group col-md-6">
                                    </div>
                                    <div class="form-group col-md-6 text-end">
                                        {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-print-invoice  btn-primary']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--Email Setting-->
                <div id="useradd-2" class="card">
                    {{ Form::open(['route' => 'email.settings.store', 'method' => 'post']) }}
                    <div class="card-header">
                        <h5>{{ __('Email Settings') }}</h5>
                        <small
                            class="text-muted">{{ __('This SMTP will be used for system-level email sending. Additionally, if a company user does not set their SMTP, then this SMTP will be used for sending emails.') }}</small>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_driver', __('Mail Driver'), ['class' => 'col-form-label text-dark']) }}
                                {{ Form::text('mail_driver', $settings['mail_driver'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Driver')]) }}
                                @error('mail_driver')
                                    <span class="invalid-mail_driver" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_host', __('Mail Host'), ['class' => 'col-form-label text-dark']) }}
                                {{ Form::text('mail_host', $settings['mail_host'], ['class' => 'form-control ', 'placeholder' => __('Enter Mail Driver')]) }}
                                @error('mail_host')
                                    <span class="invalid-mail_driver" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_port', __('Mail Port'), ['class' => 'col-form-label text-dark']) }}
                                {{ Form::text('mail_port', $settings['mail_port'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Port')]) }}
                                @error('mail_port')
                                    <span class="invalid-mail_port" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_username', __('Mail Username'), ['class' => 'col-form-label text-dark']) }}
                                {{ Form::text('mail_username', $settings['mail_username'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')]) }}
                                @error('mail_username')
                                    <span class="invalid-mail_username" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_password', __('Mail Password'), ['class' => 'col-form-label text-dark']) }}
                                {{ Form::text('mail_password', $settings['mail_password'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Password')]) }}
                                @error('mail_password')
                                    <span class="invalid-mail_password" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'col-form-label text-dark']) }}
                                {{ Form::text('mail_encryption', $settings['mail_encryption'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption')]) }}
                                @error('mail_encryption')
                                    <span class="invalid-mail_encryption" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_from_address', __('Mail From Address'), ['class' => 'col-form-label text-dark']) }}
                                {{ Form::text('mail_from_address', $settings['mail_from_address'], ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')]) }}
                                @error('mail_from_address')
                                    <span class="invalid-mail_from_address" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'col-form-label text-dark']) }}
                                {{ Form::text('mail_from_name', $settings['mail_from_name'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption')]) }}
                                @error('mail_from_name')
                                    <span class="invalid-mail_from_name" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                        <div class="modal-footer ">
                            <div class="row col-12">
                                <div class="form-group col-md-6">
                                    <a href="#" data-url="{{ route('test.email') }}" id="test_email"
                                        data-title="{{ __('Send Test Mail') }}" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" class="btn  btn-primary send_email">
                                        {{ __('Send Test Mail') }}
                                    </a>
                                </div>
                                <div class="form-group col-md-6 text-end">
                                    <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
                                </div>
                            </div>
                        </div>

                    </div>

                    {{ Form::close() }}
                </div>


                <div id="useradd-4" class="card mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <h5 class="">{{ __('Storage Settings') }}</h5>
                                <small class="text-muted">{{ __('Edit storage Settings') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        {{ Form::open(['route' => 'storage.setting.store', 'enctype' => 'multipart/form-data']) }}
                        <div class="d-flex">
                            <div class="pe-2">
                                <input type="radio" class="btn-check" name="storage_setting" id="local-outlined"
                                    autocomplete="off" {{ $settings['storage_setting'] == 'local' ? 'checked' : '' }}
                                    value="local" checked>
                                <label class="btn btn-outline-primary" for="local-outlined">{{ __('Local') }}</label>
                            </div>
                            <div class="pe-2">
                                <input type="radio" class="btn-check" name="storage_setting" id="s3-outlined"
                                    autocomplete="off" {{ $settings['storage_setting'] == 's3' ? 'checked' : '' }}
                                    value="s3">
                                <label class="btn btn-outline-primary" for="s3-outlined">
                                    {{ __('AWS S3') }}</label>
                            </div>

                            <div class="pe-2">
                                <input type="radio" class="btn-check" name="storage_setting" id="wasabi-outlined"
                                    autocomplete="off" {{ $settings['storage_setting'] == 'wasabi' ? 'checked' : '' }}
                                    value="wasabi">
                                <label class="btn btn-outline-primary" for="wasabi-outlined">{{ __('Wasabi') }}</label>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div
                                class="local-setting row {{ $settings['storage_setting'] == 'local' ? ' ' : 'd-none' }}">
                                {{-- <h4 class="small-title">{{ __('Local Settings') }}</h4> --}}
                                <div class="form-group col-8 switch-width">
                                    {{ Form::label('local_storage_validation', __('Only Upload Files'), ['class' => 'mb-2']) }}
                                    <select name="local_storage_validation[]" class="multi-select choices__input"
                                        id="local_storage_validation" id='choices-multiple' multiple>
                                        @foreach ($file_type as $f)
                                            <option @if (in_array($f, $local_storage_validations)) selected @endif>{{ $f }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label"
                                            for="local_storage_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                                        <input type="number" name="local_storage_max_upload_size" class="form-control"
                                            value="{{ !isset($settings['local_storage_max_upload_size']) || is_null($settings['local_storage_max_upload_size']) ? '' : $settings['local_storage_max_upload_size'] }}"
                                            placeholder="{{ __('Max upload size') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="s3-setting row {{ $settings['storage_setting'] == 's3' ? ' ' : 'd-none' }}">

                                <div class=" row ">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_key">{{ __('S3 Key') }}</label>
                                            <input type="text" name="s3_key" class="form-control"
                                                value="{{ !isset($settings['s3_key']) || is_null($settings['s3_key']) ? '' : $settings['s3_key'] }}"
                                                placeholder="{{ __('S3 Key') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_secret">{{ __('S3 Secret') }}</label>
                                            <input type="text" name="s3_secret" class="form-control"
                                                value="{{ !isset($settings['s3_secret']) || is_null($settings['s3_secret']) ? '' : $settings['s3_secret'] }}"
                                                placeholder="{{ __('S3 Secret') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_region">{{ __('S3 Region') }}</label>
                                            <input type="text" name="s3_region" class="form-control"
                                                value="{{ !isset($settings['s3_region']) || is_null($settings['s3_region']) ? '' : $settings['s3_region'] }}"
                                                placeholder="{{ __('S3 Region') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_bucket">{{ __('S3 Bucket') }}</label>
                                            <input type="text" name="s3_bucket" class="form-control"
                                                value="{{ !isset($settings['s3_bucket']) || is_null($settings['s3_bucket']) ? '' : $settings['s3_bucket'] }}"
                                                placeholder="{{ __('S3 Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_url">{{ __('S3 URL') }}</label>
                                            <input type="text" name="s3_url" class="form-control"
                                                value="{{ !isset($settings['s3_url']) || is_null($settings['s3_url']) ? '' : $settings['s3_url'] }}"
                                                placeholder="{{ __('S3 URL') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_endpoint">{{ __('S3 Endpoint') }}</label>
                                            <input type="text" name="s3_endpoint" class="form-control"
                                                value="{{ !isset($settings['s3_endpoint']) || is_null($settings['s3_endpoint']) ? '' : $settings['s3_endpoint'] }}"
                                                placeholder="{{ __('S3 Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="form-group col-8 switch-width">
                                        {{ Form::label('s3_storage_validation', __('Only Upload Files'), ['class' => ' form-label']) }}
                                        <select name="s3_storage_validation[]" class="multi-select"
                                            id="s3_storage_validation" multiple>
                                            @foreach ($file_type as $f)
                                                <option @if (in_array($f, $s3_storage_validations)) selected @endif>
                                                    {{ $f }}</option>
                                            @endforeach


                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label"
                                                for="s3_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                                            <input type="number" name="s3_max_upload_size" class="form-control"
                                                value="{{ !isset($settings['s3_max_upload_size']) || is_null($settings['s3_max_upload_size']) ? '' : $settings['s3_max_upload_size'] }}"
                                                placeholder="{{ __('Max upload size') }}">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div
                                class="wasabi-setting row {{ $settings['storage_setting'] == 'wasabi' ? ' ' : 'd-none' }}">
                                <div class=" row ">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_key">{{ __('Wasabi Key') }}</label>
                                            <input type="text" name="wasabi_key" class="form-control"
                                                value="{{ !isset($settings['wasabi_key']) || is_null($settings['wasabi_key']) ? '' : $settings['wasabi_key'] }}"
                                                placeholder="{{ __('Wasabi Key') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_secret">{{ __('Wasabi Secret') }}</label>
                                            <input type="text" name="wasabi_secret" class="form-control"
                                                value="{{ !isset($settings['wasabi_secret']) || is_null($settings['wasabi_secret']) ? '' : $settings['wasabi_secret'] }}"
                                                placeholder="{{ __('Wasabi Secret') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_region">{{ __('Wasabi Region') }}</label>
                                            <input type="text" name="wasabi_region" class="form-control"
                                                value="{{ !isset($settings['wasabi_region']) || is_null($settings['wasabi_region']) ? '' : $settings['wasabi_region'] }}"
                                                placeholder="{{ __('Wasabi Region') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label"
                                                for="wasabi_bucket">{{ __('Wasabi Bucket') }}</label>
                                            <input type="text" name="wasabi_bucket" class="form-control"
                                                value="{{ !isset($settings['wasabi_bucket']) || is_null($settings['wasabi_bucket']) ? '' : $settings['wasabi_bucket'] }}"
                                                placeholder="{{ __('Wasabi Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="wasabi_url">{{ __('Wasabi URL') }}</label>
                                            <input type="text" name="wasabi_url" class="form-control"
                                                value="{{ !isset($settings['wasabi_url']) || is_null($settings['wasabi_url']) ? '' : $settings['wasabi_url'] }}"
                                                placeholder="{{ __('Wasabi URL') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="wasabi_root">{{ __('Wasabi Root') }}</label>
                                            <input type="text" name="wasabi_root" class="form-control"
                                                value="{{ !isset($settings['wasabi_root']) || is_null($settings['wasabi_root']) ? '' : $settings['wasabi_root'] }}"
                                                placeholder="{{ __('Wasabi Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="form-group col-8 switch-width">
                                        {{ Form::label('wasabi_storage_validation', __('Only Upload Files'), ['class' => 'form-label']) }}

                                        <select name="wasabi_storage_validation[]" class="multi-select"
                                            id="wasabi_storage_validation" multiple>
                                            @foreach ($file_type as $f)
                                                <option @if (in_array($f, $wasabi_storage_validations)) selected @endif>
                                                    {{ $f }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label"
                                                for="wasabi_root">{{ __('Max upload size ( In KB)') }}</label>
                                            <input type="number" name="wasabi_max_upload_size" class="form-control"
                                                value="{{ !isset($settings['wasabi_max_upload_size']) || is_null($settings['wasabi_max_upload_size']) ? '' : $settings['wasabi_max_upload_size'] }}"
                                                placeholder="{{ __('Max upload size') }}">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                                value="{{ __('Save Changes') }}">
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>

                <!--Payment Setting-->
                <div id="useradd-3" class="card">
                    <div id="payment-settings" class="faq">
                        <div class="card-header">
                            <h5>{{ __('Payment Settings') }}</h5>
                            <small
                                class="text-muted">{{ __('These details will be used to collect subscription plan payments. Each subscription plan will have a payment button based on the below configuration.') }}</small>
                        </div>
                        <div class="card-body">
                            <form id="setting-form" method="post" action="{{ route('payment.setting') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                        <label class="col-form-label">{{ __('Currency') }}</label>
                                                        <input type="text" name="currency" class="form-control"
                                                            id="currency"
                                                            value="{{ !isset($payment['currency']) || is_null($payment['currency']) ? '' : $payment['currency'] }}"
                                                            required>
                                                        <small class="text-xs">
                                                            {{ __('Note: Add currency code as per three-letter ISO code.') }}
                                                            <a href="https://stripe.com/docs/currencies"
                                                                target="_blank">{{ __('You can find out how to do that here.') }}</a>
                                                        </small>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                        <label for="currency_symbol"
                                                            class="col-form-label">{{ __('Currency Symbol') }}</label>
                                                        <input type="text" name="currency_symbol" class="form-control"
                                                            id="currency_symbol"
                                                            value="{{ !isset($payment['currency_symbol']) || is_null($payment['currency_symbol']) ? '' : $payment['currency_symbol'] }}"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="faq justify-content-center">
                                    <div class="col-sm-12 col-md-10 col-xxl-12">
                                        <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne13">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseOne13"
                                                        aria-expanded="false" aria-controls="collapseOne13">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Manually') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable : ') }}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_manual_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_manual_enabled" id="is_manual_enabled"
                                                                    {{ isset($payment['is_manual_enabled']) && $payment['is_manual_enabled'] == 'on' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseOne13" class="accordion-collapse collapse"
                                                    aria-labelledby="headingOne13" data-bs-parent="#accordionExample">
                                                    <div class="p-3">
                                                        <p class="text-muted">
                                                            {{ __('Requesting manual payment for the planned amount for the subscriptions plan.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne14">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseOne14"
                                                        aria-expanded="false" aria-controls="collapseOne14">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Bank Transfer') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable : ') }}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_banktransfer_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_banktransfer_enabled"
                                                                    id="is_banktransfer_enabled"
                                                                    {{ isset($payment['is_banktransfer_enabled']) && $payment['is_banktransfer_enabled'] == 'on' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>

                                                    </button>
                                                </h2>
                                                <div id="collapseOne14" class="accordion-collapse collapse"
                                                    aria-labelledby="headingOne14" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <label for="bank_details"
                                                                class="col-form-label"><b>{{ __('Bank Details') }}</b></label>
                                                            {{-- <textarea class="form-control" rows="6" name="bank_details">
                                                            {{ isset($payment['bank_details'])  ? $payment['bank_details'] : ''}}</textarea> --}}
                                                            @php
                                                                $bank_details = !empty($payment['bank_details'])
                                                                    ? $payment['bank_details']
                                                                    : '';
                                                            @endphp
                                                            {!! Form::textarea('bank_details', $bank_details, ['class' => 'form-control', 'rows' => '6']) !!}
                                                            <small class="text-xs">
                                                                {{ __('Example: Bank : Bank Name </br> Account Number : 0000 0000') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Strip -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-2">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse1"
                                                        aria-expanded="true"aria-controls="collapse1">
                                                        <span class="d-flex align-items-center">

                                                            {{ __('Stripe') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable') }}:</span>
                                                            <div class=" form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_stripe_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_stripe_enabled" id="is_stripe_enabled"
                                                                    {{ isset($payment['is_stripe_enabled']) && $payment['is_stripe_enabled'] == 'on' ? 'checked' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_stripe_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse1" class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-2" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="stripe_key">{{ __('Stripe Key') }}</label>
                                                                    <input class="form-control"
                                                                        placeholder="{{ __('Stripe Key') }}"
                                                                        name="stripe_key" type="text"
                                                                        value="{{ !isset($payment['stripe_key']) || is_null($payment['stripe_key']) ? '' : $payment['stripe_key'] }}"
                                                                        id="stripe_key">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="stripe_secret">{{ __('Stripe Secret') }}</label>
                                                                    <input class="form-control "
                                                                        placeholder="{{ __('Stripe Secret') }}"
                                                                        name="stripe_secret" type="text"
                                                                        value="{{ !isset($payment['stripe_secret']) || is_null($payment['stripe_secret']) ? '' : $payment['stripe_secret'] }}"
                                                                        id="stripe_secret">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Paypal -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-3">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse2"
                                                        aria-expanded="true" aria-controls="collapse2">
                                                        <span class="d-flex align-items-center">

                                                            {{ __('Paypal') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable') }}:</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paypal_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_paypal_enabled" id="is_paypal_enabled"
                                                                    {{ isset($payment['is_paypal_enabled']) && $payment['is_paypal_enabled'] == 'on' ? 'checked' : '' }}>
                                                                <label class="custom-control-label form-control-label"
                                                                    for="is_paypal_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse2"
                                                    class="accordion-collapse collapse"aria-labelledby="heading-2-3"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-6 py-2">
                                                                {{-- <h5 class="h5">{{ __('Paypal') }}</h5> --}}
                                                            </div>
                                                            <div class="col-6 py-2 text-end">

                                                            </div>
                                                            <div class="col-md-12 pb-4">
                                                                <label class="paypal-label col-form-label"
                                                                    for="paypal_mode">{{ __('Paypal Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="col-lg-3" style="margin-right: 15px;">
                                                                        <div class="border accordion-header p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="paypal_mode" value="sandbox"
                                                                                        class="form-check-input"{{ !isset($payment['paypal_mode']) || $payment['paypal_mode'] == '' || $payment['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3">
                                                                        <div class="border accordion-header p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="paypal_mode" value="live"
                                                                                        class="form-check-input"{{ isset($payment['paypal_mode']) && $payment['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="paypal_client_id">{{ __('Client ID') }}</label>
                                                                    <input type="text" name="paypal_client_id"
                                                                        id="paypal_client_id" class="form-control"
                                                                        value="{{ !isset($payment['paypal_client_id']) || is_null($payment['paypal_client_id']) ? '' : $payment['paypal_client_id'] }}"
                                                                        placeholder="{{ __('Client ID') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="paypal_secret_key">{{ __('Secret Key') }}</label>
                                                                    <input type="text" name="paypal_secret_key"
                                                                        id="paypal_secret_key" class="form-control"
                                                                        value="{{ !isset($payment['paypal_secret_key']) || is_null($payment['paypal_secret_key']) ? '' : $payment['paypal_secret_key'] }}"
                                                                        placeholder="{{ __('Secret Key') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Paystack -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-4">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse3"aria-expanded="true"
                                                        aria-controls="collapse3">
                                                        <span class="d-flex align-items-center">

                                                            {{ __('Paystack') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable') }}:</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paystack_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_paystack_enabled"
                                                                    id="is_paystack_enabled"{{ isset($payment['is_paystack_enabled']) && $payment['is_paystack_enabled'] == 'on' ? 'checked' : '' }}>
                                                                <label
                                                                    class="custom-control-label form-control-label"for="is_paystack_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse3"class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-4" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="paypal_client_id">{{ __('Public Key') }}</label>
                                                                    <input type="text" name="paystack_public_key"
                                                                        id="paystack_public_key" class="form-control"
                                                                        value="{{ !isset($payment['paystack_public_key']) || is_null($payment['paystack_public_key']) ? '' : $payment['paystack_public_key'] }}"placeholder="{{ __('Public Key') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="paystack_secret_key">{{ __('Secret Key') }}</label>
                                                                    <input type="text"
                                                                        name="paystack_secret_key"id="paystack_secret_key"
                                                                        class="form-control"value="{{ !isset($payment['paystack_secret_key']) || is_null($payment['paystack_secret_key']) ? '' : $payment['paystack_secret_key'] }}"
                                                                        placeholder="{{ __('Secret Key') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- FLUTTERWAVE -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-2">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse4"
                                                        aria-expanded="true" aria-controls="collapse4">
                                                        <span class="d-flex align-items-center">

                                                            {{ __('Flutterwave') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable') }}:</span>
                                                            <div class=" form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_flutterwave_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input"name="is_flutterwave_enabled"
                                                                    id="is_flutterwave_enabled"
                                                                    {{ isset($payment['is_flutterwave_enabled']) && $payment['is_flutterwave_enabled'] == 'on' ? 'checked' : '' }}>
                                                                <label
                                                                    class="custom-control-label form-control-label"for="is_flutterwave_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse4"class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-5" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="paypal_client_id">{{ __('Public Key') }}</label>
                                                                    <input type="text" name="flutterwave_public_key"
                                                                        id="flutterwave_public_key" class="form-control"
                                                                        value="{{ !isset($payment['flutterwave_public_key']) || is_null($payment['flutterwave_public_key']) ? '' : $payment['flutterwave_public_key'] }}"
                                                                        placeholder="Public Key">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="paystack_secret_key">{{ __('Secret Key') }}</label>
                                                                    <input type="text" name="flutterwave_secret_key"
                                                                        id="flutterwave_secret_key" class="form-control"
                                                                        value="{{ !isset($payment['flutterwave_secret_key']) || is_null($payment['flutterwave_secret_key']) ? '' : $payment['flutterwave_secret_key'] }}"
                                                                        placeholder="Secret Key">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Razorpay -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-6">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse5"
                                                        aria-expanded="true" aria-controls="collapse5">
                                                        <span class="d-flex align-items-center">

                                                            {{ __('Razorpay') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable') }}:</span>
                                                            <div class=" form-check form-switch custom-switch-v1">
                                                                <input type="hidden"
                                                                    name="is_razorpay_enabled"value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_razorpay_enabled" id="is_razorpay_enabled"
                                                                    {{ isset($payment['is_razorpay_enabled']) && $payment['is_razorpay_enabled'] == 'on' ? 'checked' : '' }}>
                                                                <label class="custom-control-label form-control-label"
                                                                    for="is_razorpay_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse5"
                                                    class="accordion-collapse collapse"aria-labelledby="heading-2-6"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paypal_client_id">Public Key</label>
                                                                    <input type="text" name="razorpay_public_key"
                                                                        id="razorpay_public_key" class="form-control"
                                                                        value="{{ !isset($payment['razorpay_public_key']) || is_null($payment['razorpay_public_key']) ? '' : $payment['razorpay_public_key'] }}"
                                                                        placeholder="Public Key">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paystack_secret_key">Secret Key</label>
                                                                    <input type="text" name="razorpay_secret_key"
                                                                        id="razorpay_secret_key" class="form-control"
                                                                        value="{{ !isset($payment['razorpay_secret_key']) || is_null($payment['razorpay_secret_key']) ? '' : $payment['razorpay_secret_key'] }}"
                                                                        placeholder="Secret Key">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Paytm -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-7">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse6"
                                                        aria-expanded="true" aria-controls="collapse6">
                                                        <span class="d-flex align-items-center">

                                                            {{ __('Paytm') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable') }}:</span>
                                                            <div class=" form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paytm_enabled"value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input"name="is_paytm_enabled"
                                                                    id="is_paytm_enabled"{{ isset($payment['is_paytm_enabled']) && $payment['is_paytm_enabled'] == 'on' ? 'checked' : '' }}>
                                                                <label
                                                                    class="custom-control-label form-control-label"for="is_paytm_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse6"class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-7" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="paypal-label col-form-label"
                                                                    for="paypal_mode">Paytm Environment</label> <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">

                                                                                    <input type="radio"
                                                                                        name="paytm_mode" value="local"
                                                                                        class="form-check-input"
                                                                                        {{ !isset($payment['paytm_mode']) || $payment['paytm_mode'] == '' || $payment['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>

                                                                                    {{ __('Local') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="paytm_mode"
                                                                                        value="production"
                                                                                        class="form-check-input"
                                                                                        {{ isset($payment['paytm_mode']) && $payment['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>

                                                                                    {{ __('Production') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="paytm_public_key">Merchant ID</label>
                                                                    <input type="text" name="paytm_merchant_id"
                                                                        id="paytm_merchant_id" class="form-control"
                                                                        value="{{ !isset($payment['paytm_merchant_id']) || is_null($payment['paytm_merchant_id']) ? '' : $payment['paytm_merchant_id'] }}"
                                                                        placeholder="Merchant ID">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="paytm_secret_key">Merchant Key</label>
                                                                    <input type="text" name="paytm_merchant_key"
                                                                        id="paytm_merchant_key" class="form-control"
                                                                        value="{{ !isset($payment['paytm_merchant_key']) || is_null($payment['paytm_merchant_key']) ? '' : $payment['paytm_merchant_key'] }}"
                                                                        placeholder="Merchant Key">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="paytm_industry_type">Industry Type</label>
                                                                    <input type="text"
                                                                        name="paytm_industry_type"id="paytm_industry_type"
                                                                        class="form-control"
                                                                        value="{{ !isset($payment['paytm_industry_type']) || is_null($payment['paytm_industry_type']) ? '' : $payment['paytm_industry_type'] }}"
                                                                        placeholder="Industry Type">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Mercado Pago-->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-8">
                                                    <button class="accordion-button"
                                                        type="button"data-bs-toggle="collapse"
                                                        data-bs-target="#collapse7"aria-expanded="true"
                                                        aria-controls="collapse7">
                                                        <span class="d-flex align-items-center">

                                                            {{ __('Mercado Pago') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable') }}:</span>
                                                            <div class=" form-check form-switch custom-switch-v1">
                                                                <input type="hidden"
                                                                    name="is_mercado_enabled"value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_mercado_enabled"
                                                                    id="is_mercado_enabled"{{ isset($payment['is_mercado_enabled']) && $payment['is_mercado_enabled'] == 'on' ? 'checked' : '' }}>
                                                                <label
                                                                    class="custom-control-label form-control-label"for="is_mercado_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse7"
                                                    class="accordion-collapse collapse"aria-labelledby="heading-2-8"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12 ">
                                                                <label class="coingate-label col-form-label"
                                                                    for="mercado_mode">{{ __('Mercado Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="mercado_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input"{{ (isset($payment['mercado_mode']) && $payment['mercado_mode'] == '') || (isset($payment['mercado_mode']) && $payment['mercado_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="mercado_mode"
                                                                                        value="live"
                                                                                        class="form-check-input"{{ isset($payment['mercado_mode']) && $payment['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="mercado_access_token">{{ __('Access Token') }}</label>
                                                                    <input type="text" name="mercado_access_token"
                                                                        id="mercado_access_token" class="form-control"
                                                                        value="{{ isset($payment['mercado_access_token']) ? $payment['mercado_access_token'] : '' }}"
                                                                        placeholder="{{ __('Access Token') }}" />
                                                                    @if ($errors->has('mercado_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('mercado_access_token') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Mollie -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-9">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse8"
                                                        aria-expanded="true" aria-controls="collapse8">
                                                        <span class="d-flex align-items-center">

                                                            {{ __('Mollie') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable') }}:</span>
                                                            <div class=" form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_mollie_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_mollie_enabled" id="is_mollie_enabled"
                                                                    {{ isset($payment['is_mollie_enabled']) && $payment['is_mollie_enabled'] == 'on' ? 'checked' : '' }}>
                                                                <label class="custom-control-label form-control-label"
                                                                    for="is_mollie_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse8"
                                                    class="accordion-collapse collapse"aria-labelledby="heading-2-9"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="mollie_api_key"
                                                                        class="col-form-label">{{ __('Mollie Api Key') }}</label>
                                                                    <input type="text" name="mollie_api_key"
                                                                        id="mollie_api_key" class="form-control"
                                                                        value="{{ !isset($payment['mollie_api_key']) || is_null($payment['mollie_api_key']) ? '' : $payment['mollie_api_key'] }}"
                                                                        placeholder="Mollie Api Key">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="mollie_profile_id"
                                                                        class="col-form-label">{{ __('Mollie Profile Id') }}</label>
                                                                    <input type="text" name="mollie_profile_id"
                                                                        id="mollie_profile_id" class="form-control"
                                                                        value="{{ !isset($payment['mollie_profile_id']) || is_null($payment['mollie_profile_id']) ? '' : $payment['mollie_profile_id'] }}"
                                                                        placeholder="Mollie Profile Id">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="mollie_partner_id"
                                                                        class="col-form-label">{{ __('Mollie Partner Id') }}</label>
                                                                    <input type="text" name="mollie_partner_id"
                                                                        id="mollie_partner_id" class="form-control"
                                                                        value="{{ !isset($payment['mollie_partner_id']) || is_null($payment['mollie_partner_id']) ? '' : $payment['mollie_partner_id'] }}"
                                                                        placeholder="Mollie Partner Id">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Skrill -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-10">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse9"
                                                        aria-expanded="true" aria-controls="collapse9">
                                                        <span class="d-flex align-items-center">

                                                            {{ __('Skrill') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable') }}:</span>
                                                            <div class=" form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_skrill_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_skrill_enabled" id="is_skrill_enabled"
                                                                    {{ isset($payment['is_skrill_enabled']) && $payment['is_skrill_enabled'] == 'on' ? 'checked' : '' }}>
                                                                <label class="custom-control-label form-control-label"
                                                                    for="is_skrill_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse9"class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-10" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="mollie_api_key"
                                                                        class="col-form-label">Skrill Email</label>
                                                                    <input type="text" name="skrill_email"
                                                                        id="skrill_email" class="form-control"
                                                                        value="{{ !isset($payment['skrill_email']) || is_null($payment['skrill_email']) ? '' : $payment['skrill_email'] }}"
                                                                        placeholder="Enter Skrill Email">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- CoinGate -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-11">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse10"
                                                        aria-expanded="true" aria-controls="collapse10">
                                                        <span class="d-flex align-items-center">

                                                            {{ __('CoinGate') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable') }}:</span>
                                                            <div class=" form-check form-switch custom-switch-v1">
                                                                <input type="hidden"
                                                                    name="is_coingate_enabled"value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_coingate_enabled" id="is_coingate_enabled"
                                                                    {{ isset($payment['is_coingate_enabled']) && $payment['is_coingate_enabled'] == 'on' ? 'checked' : '' }}>
                                                                <label class="custom-control-label form-control-label"
                                                                    for="is_coingate_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse10"
                                                    class="accordion-collapse collapse"aria-labelledby="heading-2-11"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="col-form-label" for="coingate_mode">
                                                                    {{ __('CoinGate Mode') }}</label> <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="coingate_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input"
                                                                                        {{ !isset($payment['coingate_mode']) || $payment['coingate_mode'] == '' || $payment['coingate_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="coingate_mode"
                                                                                        value="live"
                                                                                        class="form-check-input"
                                                                                        {{ isset($payment['coingate_mode']) && $payment['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="coingate_auth_token">{{ __('CoinGate Auth Token') }}</label>
                                                                    <input type="text" name="coingate_auth_token"
                                                                        id="coingate_auth_token" class="form-control"
                                                                        value="{{ !isset($payment['coingate_auth_token']) || is_null($payment['coingate_auth_token']) ? '' : $payment['coingate_auth_token'] }}"
                                                                        placeholder="CoinGate Auth Token">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- PaymentWall -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingten00">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse100"
                                                        aria-expanded="false" aria-controls="collapse100">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('PaymentWall') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable : ') }}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paymentwall_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_paymentwall_enabled"
                                                                    id="is_paymentwall_enabled"
                                                                    {{ isset($payment['is_paymentwall_enabled']) && $payment['is_paymentwall_enabled'] == 'on' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse100" class="accordion-collapse collapse"
                                                    aria-labelledby="headingten00" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paymentwall_public_key"
                                                                        class="col-form-label">{{ __('Public Key') }}</label>
                                                                    <input type="text" name="paymentwall_public_key"
                                                                        id="paymentwall_public_key" class="form-control"
                                                                        value="{{ !isset($payment['paymentwall_public_key']) || is_null($payment['paymentwall_public_key']) ? '' : $payment['paymentwall_public_key'] }}"
                                                                        placeholder="Public Key">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paymentwall_private_key"
                                                                        class="col-form-label">{{ __('Private Key') }}</label>
                                                                    <input type="text" name="paymentwall_private_key"
                                                                        id="paymentwall_private_key"
                                                                        class="form-control"
                                                                        value="{{ !isset($payment['paymentwall_private_key']) || is_null($payment['paymentwall_private_key']) ? '' : $payment['paymentwall_private_key'] }}"
                                                                        placeholder="Private Key">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Toyyibpay -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-2">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse11"
                                                        aria-expanded="true"aria-controls="collapse11">
                                                        <span class="d-flex align-items-center">

                                                            {{ __('Toyyibpay') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable') }}:</span>
                                                            <div class=" form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_toyyibpay_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_toyyibpay_enabled"
                                                                    id="is_toyyibpay_enabled"
                                                                    {{ isset($payment['is_toyyibpay_enabled']) && $payment['is_toyyibpay_enabled'] == 'on' ? 'checked' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_toyyibpay_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse11" class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-2" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="toyyibpay_secret_key">{{ __('Secret Key') }}</label>
                                                                    <input class="form-control"
                                                                        placeholder="{{ __('Secret Key') }}"
                                                                        name="toyyibpay_secret_key" type="text"
                                                                        value="{{ !isset($payment['toyyibpay_secret_key']) || is_null($payment['toyyibpay_secret_key']) ? '' : $payment['toyyibpay_secret_key'] }}"
                                                                        id="toyyibpay_secret_key">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="category_code">{{ __('Category Code') }}</label>
                                                                    <input class="form-control "
                                                                        placeholder="{{ __('Category Code') }}"
                                                                        name="category_code" type="text"
                                                                        value="{{ !isset($payment['category_code']) || is_null($payment['category_code']) ? '' : $payment['category_code'] }}"
                                                                        id="category_code">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Payfast -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-2">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse12"
                                                        aria-expanded="true"aria-controls="collapse11">
                                                        <span class="d-flex align-items-center">

                                                            {{ __('Payfast') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable') }}:</span>
                                                            <div class=" form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_payfast_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_payfast_enabled" id="is_payfast_enabled"
                                                                    {{ isset($payment['is_payfast_enabled']) && $payment['is_payfast_enabled'] == 'on' ? 'checked' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_payfast_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse12" class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-2" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12 pb-4">
                                                                <label class="payfast-label col-form-label"
                                                                    for="payfast_mode">{{ __('Payfast Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="col-lg-3" style="margin-right: 15px;">
                                                                        <div class="border accordion-header p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="payfast_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input"{{ !isset($payment['payfast_mode']) || $payment['payfast_mode'] == '' || $payment['payfast_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3">
                                                                        <div class="border accordion-header p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="payfast_mode"
                                                                                        value="live"
                                                                                        class="form-check-input"{{ isset($payment['payfast_mode']) && $payment['payfast_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="payfast_merchant_id">{{ __('Merchant ID') }}</label>
                                                                    <input class="form-control"
                                                                        placeholder="{{ __('Merchant ID') }}"
                                                                        name="payfast_merchant_id" type="text"
                                                                        value="{{ !isset($payment['payfast_merchant_id']) || is_null($payment['payfast_merchant_id']) ? '' : $payment['payfast_merchant_id'] }}"
                                                                        id="payfast_merchant_id">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="payfast_merchant_key">{{ __('Merchant Key') }}</label>
                                                                    <input class="form-control "
                                                                        placeholder="{{ __('Merchant Key') }}"
                                                                        name="payfast_merchant_key" type="text"
                                                                        value="{{ !isset($payment['payfast_merchant_key']) || is_null($payment['payfast_merchant_key']) ? '' : $payment['payfast_merchant_key'] }}"
                                                                        id="payfast_merchant_key">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="payfast_signature">{{ __('Salt Passphrase') }}</label>
                                                                    <input class="form-control "
                                                                        placeholder="{{ __('Merchant Key') }}"
                                                                        name="payfast_signature" type="text"
                                                                        value="{{ !isset($payment['payfast_signature']) || is_null($payment['payfast_signature']) ? '' : $payment['payfast_signature'] }}"
                                                                        id="payfast_signature">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- SSPay -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingthirteen">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse13"
                                                        aria-expanded="false" aria-controls="collapse13">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('SSPay') }}
                                                        </span>

                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable') }}:</span>
                                                            <div class=" form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_sspay_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_sspay_enabled" id="is_sspay_enabled"
                                                                    {{ isset($payment['is_sspay_enabled']) && $payment['is_sspay_enabled'] == 'on' ? 'checked' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_sspay_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse13" class="accordion-collapse collapse"
                                                    aria-labelledby="headingthirteen"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="sspay_secret_key"
                                                                        class="col-form-label">{{ __('Secret Key') }}</label>
                                                                    <input type="text" name="sspay_secret_key"
                                                                        id="sspay_secret_key" class="form-control"
                                                                        value="{{ !isset($payment['sspay_secret_key']) || is_null($payment['sspay_secret_key']) ? '' : $payment['sspay_secret_key'] }}"
                                                                        placeholder="Secret Key">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="sspay_category_code"
                                                                        class="col-form-label">{{ __('Category Code') }}</label>
                                                                    <input type="text" name="sspay_category_code"
                                                                        id="sspay_category_code" class="form-control"
                                                                        value="{{ !isset($payment['sspay_category_code']) || is_null($payment['sspay_category_code']) ? '' : $payment['sspay_category_code'] }}"
                                                                        placeholder="Category Code">
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Iyzipay -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingfourteen">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse14"
                                                        aria-expanded="false" aria-controls="collapse14">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Iyzipay') }}
                                                        </span>

                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable') }}:</span>
                                                            <div class=" form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_iyzipay_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_iyzipay_enabled" id="is_iyzipay_enabled"
                                                                    {{ isset($payment['is_iyzipay_enabled']) && $payment['is_iyzipay_enabled'] == 'on' ? 'checked' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_iyzipay_enabled"></label>
                                                            </div>
                                                        </div>

                                                    </button>
                                                </h2>
                                                <div id="collapse14" class="accordion-collapse collapse"
                                                    aria-labelledby="headingfourteen"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="col-form-label"
                                                                    for="iyzipay_mode">{{ __('Iyzipay Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="iyzipay_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input"
                                                                                        {{ isset($payment['iyzipay_mode']) && $payment['iyzipay_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>

                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="iyzipay_mode"
                                                                                        value="live"
                                                                                        class="form-check-input"
                                                                                        {{ isset($payment['iyzipay_mode']) && $payment['iyzipay_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="iyzipay_key"
                                                                        class="col-form-label">{{ __('Iyzipay Key') }}</label>
                                                                    <input type="text" name="iyzipay_key"
                                                                        id="iyzipay_key" class="form-control"
                                                                        value="{{ !isset($payment['iyzipay_key']) || is_null($payment['iyzipay_key']) ? '' : $payment['iyzipay_key'] }}"
                                                                        placeholder="Iyzipay Key">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="iyzipay_secret"
                                                                        class="col-form-label">{{ __('Iyzipay Secret') }}</label>
                                                                    <input type="text" name="iyzipay_secret"
                                                                        id="iyzipay_secret" class="form-control"
                                                                        value="{{ !isset($payment['iyzipay_secret']) || is_null($payment['iyzipay_secret']) ? '' : $payment['iyzipay_secret'] }}"
                                                                        placeholder="Iyzipay Secret">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- PayTab -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingfifteen">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse15"
                                                        aria-expanded="false" aria-controls="collapse15">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('PayTab') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable : ') }}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paytab_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_paytab_enabled" id="is_paytab_enabled"
                                                                    {{ isset($payment['is_paytab_enabled']) && $payment['is_paytab_enabled'] == 'on' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse15" class="accordion-collapse collapse"
                                                    aria-labelledby="headingfifteen" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="paytab_profile_id"
                                                                        class="col-form-label">{{ __('PayTab Profile Id') }}</label>
                                                                    <input type="text" name="paytab_profile_id"
                                                                        id="paytab_profile_id" class="form-control"
                                                                        value="{{ !isset($payment['paytab_profile_id']) || is_null($payment['paytab_profile_id']) ? '' : $payment['paytab_profile_id'] }}"
                                                                        placeholder="PayTab Profile Id">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="paytab_server_key"
                                                                        class="col-form-label">{{ __('PasTab Server Key') }}</label>
                                                                    <input type="text" name="paytab_server_key"
                                                                        id="paytab_server_key" class="form-control"
                                                                        value="{{ !isset($payment['paytab_server_key']) || is_null($payment['paytab_server_key']) ? '' : $payment['paytab_server_key'] }}"
                                                                        placeholder="PasTab Server Key">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="paytab_region"
                                                                        class="col-form-label">{{ __('PayTab Region') }}</label>
                                                                    <input type="text" name="paytab_region"
                                                                        id="paytab_region" class="form-control"
                                                                        value="{{ !isset($payment['paytab_region']) || is_null($payment['paytab_region']) ? '' : $payment['paytab_region'] }}"
                                                                        placeholder="PayTab Region">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Benifit -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingsixteen">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse16"
                                                        aria-expanded="false" aria-controls="collapse16">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Benifit') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable : ') }}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_benefit_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_benefit_enabled" id="is_benefit_enabled"
                                                                    {{ isset($payment['is_benefit_enabled']) && $payment['is_benefit_enabled'] == 'on' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse16" class="accordion-collapse collapse"
                                                    aria-labelledby="headingsixteen" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('benefit_api_key', __('Benefit Key'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('benefit_api_key', isset($payment['benefit_api_key']) ? $payment['benefit_api_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Benefit Key')]) }}
                                                                    @error('benefit_api_key')
                                                                        <span class="invalid-benefit_api_key"
                                                                            role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('benefit_secret_key', __('Benefit Secret Key'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('benefit_secret_key', isset($payment['benefit_secret_key']) ? $payment['benefit_secret_key'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Benefit Secret key')]) }}
                                                                    @error('benefit_secret_key')
                                                                        <span class="invalid-benefit_secret_key"
                                                                            role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Cashfree -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingseventeen">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse17"
                                                        aria-expanded="false" aria-controls="collapse17">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Cashfree') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable : ') }}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_cashfree_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_cashfree_enabled" id="is_cashfree_enabled"
                                                                    {{ isset($payment['is_cashfree_enabled']) && $payment['is_cashfree_enabled'] == 'on' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse17" class="accordion-collapse collapse"
                                                    aria-labelledby="headingseventeen"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('cashfree_key', __('Cashfree Key'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('cashfree_key', isset($payment['cashfree_key']) ? $payment['cashfree_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Cashfree Key')]) }}
                                                                    @error('cashfree_key')
                                                                        <span class="invalid-cashfree_key" role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('cashfree_secret', __('Cashfree Secret Key'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('cashfree_secret', isset($payment['cashfree_secret']) ? $payment['cashfree_secret'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Cashfree Secret key')]) }}
                                                                    @error('cashfree_secret')
                                                                        <span class="invalid-cashfree_secret"
                                                                            role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Aamarpay -->
                                            <div class="accordion-item ">
                                                <h2 class="accordion-header" id="headingTwenty">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwenty"
                                                        aria-expanded="true" aria-controls="collapseTwenty">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Aamarpay') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <label class="form-check-label m-1"
                                                                for="is_aamarpay_enabled">{{ __('Enable') }}</label>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_aamarpay_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_aamarpay_enabled" id="is_aamarpay_enabled"
                                                                    {{ isset($payment['is_aamarpay_enabled']) && $payment['is_aamarpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwenty" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwenty" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row pt-2">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    {{ Form::label('aamarpay_store_id', __('Store Id'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('aamarpay_store_id', isset($payment['aamarpay_store_id']) ? $payment['aamarpay_store_id'] : '', ['class' => 'form-control', 'placeholder' => __('Store Id')]) }}<br>
                                                                    @if ($errors->has('aamarpay_store_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('aamarpay_store_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    {{ Form::label('aamarpay_signature_key', __('Signature Key'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('aamarpay_signature_key', isset($payment['aamarpay_signature_key']) ? $payment['aamarpay_signature_key'] : '', ['class' => 'form-control', 'placeholder' => __('Signature Key')]) }}<br>
                                                                    @if ($errors->has('aamarpay_signature_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('aamarpay_signature_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    {{ Form::label('aamarpay_description', __('Description'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('aamarpay_description', isset($payment['aamarpay_description']) ? $payment['aamarpay_description'] : '', ['class' => 'form-control', 'placeholder' => __('Description')]) }}<br>
                                                                    @if ($errors->has('aamarpay_description'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('aamarpay_description') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Pay TR -->
                                            <div class="accordion-item ">
                                                <h2 class="accordion-header" id="headingTwentyOne">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentyOne"
                                                        aria-expanded="true" aria-controls="collapseTwentyOne">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Pay TR') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <label class="form-check-label m-1"
                                                                for="is_paytr_enabled">{{ __('Enable') }}</label>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paytr_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_paytr_enabled" id="is_paytr_enabled"
                                                                    {{ isset($payment['is_paytr_enabled']) && $payment['is_paytr_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentyOne" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwentyOne"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row pt-2">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    {{ Form::label('paytr_merchant_id', __('Merchant Id'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('paytr_merchant_id', isset($payment['paytr_merchant_id']) ? $payment['paytr_merchant_id'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Id')]) }}<br>
                                                                    @if ($errors->has('paytr_merchant_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytr_merchant_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    {{ Form::label('paytr_merchant_key', __('Merchant Key'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('paytr_merchant_key', isset($payment['paytr_merchant_key']) ? $payment['paytr_merchant_key'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Key')]) }}<br>
                                                                    @if ($errors->has('paytr_merchant_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytr_merchant_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    {{ Form::label('paytr_merchant_salt', __('Merchant Salt'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('paytr_merchant_salt', isset($payment['paytr_merchant_salt']) ? $payment['paytr_merchant_salt'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Salt')]) }}<br>
                                                                    @if ($errors->has('paytr_merchant_salt'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytr_merchant_salt') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Yookassa -->
                                            <div class="accordion-item ">
                                                <h2 class="accordion-header" id="headingTwentyTwo">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentyTwo"
                                                        aria-expanded="true" aria-controls="collapseTwentyTwo">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Yookassa') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <label class="form-check-label m-1"
                                                                for="is_yookassa_enabled">{{ __('Enable') }}</label>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_yookassa_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_yookassa_enabled" id="is_yookassa_enabled"
                                                                    {{ isset($payment['is_yookassa_enabled']) && $payment['is_yookassa_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentyTwo" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwentyTwo"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="yookassa_shop_id"
                                                                        class="form-label">{{ __('Shop ID Key') }}</label>
                                                                    <input type="text" name="yookassa_shop_id"
                                                                        id="yookassa_shop_id" class="form-control"
                                                                        value="{{ !isset($payment['yookassa_shop_id']) || is_null($payment['yookassa_shop_id']) ? '' : $payment['yookassa_shop_id'] }}"
                                                                        placeholder="{{ __('Shop ID Key') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="yookassa_secret"
                                                                        class="form-label">{{ __('Secret Key') }}</label>
                                                                    <input type="text" name="yookassa_secret"
                                                                        id="yookassa_secret" class="form-control"
                                                                        value="{{ !isset($payment['yookassa_secret']) || is_null($payment['yookassa_secret']) ? '' : $payment['yookassa_secret'] }}"
                                                                        placeholder="{{ __('Secret Key') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Midtrans -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentyThree">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentyThree"
                                                        aria-expanded="false" aria-controls="collapseTwentyThree">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Midtrans') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable : ') }}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_midtrans_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_midtrans_enabled" id="is_midtrans_enabled"
                                                                    {{ isset($payment['is_midtrans_enabled']) && $payment['is_midtrans_enabled'] == 'on' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentyThree" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwentyThree"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="midtran-label col-form-label"
                                                                    for="midtrans_mode">{{ 'Midtrans Mode' }}</label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="midtrans_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input"
                                                                                        {{ !isset($payment['midtrans_mode']) || $payment['midtrans_mode'] == '' || $payment['midtrans_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>

                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="midtrans_mode"
                                                                                        value="live"
                                                                                        class="form-check-input"
                                                                                        {{ isset($payment['midtrans_mode']) && $payment['midtrans_mode'] == 'live' ? 'checked="checked"' : '' }}>

                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="midtrans_secret"
                                                                        class="form-label">{{ __('Secret Key') }}</label>
                                                                    <input type="text" name="midtrans_secret"
                                                                        id="midtrans_secret" class="form-control"
                                                                        value="{{ !isset($payment['midtrans_secret']) || is_null($payment['midtrans_secret']) ? '' : $payment['midtrans_secret'] }}"
                                                                        placeholder="{{ __('Secret Key') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Xendit -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentyfour">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentyfour"
                                                        aria-expanded="false" aria-controls="collapseTwentyfour">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Xendit') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable : ') }}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_xendit_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_xendit_enabled" id="is_xendit_enabled"
                                                                    {{ isset($payment['is_xendit_enabled']) && $payment['is_xendit_enabled'] == 'on' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentyfour" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwentyfour"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="xendit_api"
                                                                        class="form-label">{{ __('API Key') }}</label>
                                                                    <input type="text" name="xendit_api"
                                                                        id="xendit_api" class="form-control"
                                                                        value="{{ !isset($payment['xendit_api']) || is_null($payment['xendit_api']) ? '' : $payment['xendit_api'] }}"
                                                                        placeholder="{{ __('API Key') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="xendit_token"
                                                                        class="form-label">{{ __('Token') }}</label>
                                                                    <input type="text" name="xendit_token"
                                                                        id="xendit_token" class="form-control"
                                                                        value="{{ !isset($payment['xendit_token']) || is_null($payment['xendit_token']) ? '' : $payment['xendit_token'] }}"
                                                                        placeholder="{{ __('Token') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- PayHere -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentyfive">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentyfive"
                                                        aria-expanded="false" aria-controls="collapseTwentyfive">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('PayHere') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('Enable : ') }}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_payhere_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_payhere_enabled" id="is_payhere_enabled"
                                                                    {{ isset($payment['is_payhere_enabled']) && $payment['is_payhere_enabled'] == 'on' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentyfive" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwentyfive"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12 pb-4">
                                                                <label class="paypal-label col-form-label"
                                                                    for="paypal_mode">{{ __('PayHere Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="payhere_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input"
                                                                                        {{ !isset($payment['payhere_mode']) || $payment['payhere_mode'] == '' || $payment['payhere_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="payhere_mode"
                                                                                        value="live"
                                                                                        class="form-check-input"
                                                                                        {{ isset($payment['payhere_mode']) && $payment['payhere_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="payhere_merchant_id"
                                                                        class="col-form-label">{{ __('Merchant ID') }}</label>
                                                                    <input type="text" name="payhere_merchant_id"
                                                                        id="payhere_merchant_id" class="form-control"
                                                                        value="{{ isset($payment['payhere_merchant_id']) ? $payment['payhere_merchant_id'] : '' }}"
                                                                        placeholder="{{ __('Merchant ID') }}" />
                                                                    @if ($errors->has('payhere_merchant_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('payhere_merchant_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="payhere_merchant_secret"
                                                                        class="col-form-label">{{ __('Merchant Secret') }}</label>
                                                                    <input type="text" name="payhere_merchant_secret"
                                                                        id="payhere_merchant_secret"
                                                                        class="form-control"
                                                                        value="{{ isset($payment['payhere_merchant_secret']) ? $payment['payhere_merchant_secret'] : '' }}"
                                                                        placeholder="{{ __('Merchant Secret') }}" />
                                                                    @if ($errors->has('payhere_merchant_secret'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('payhere_merchant_secret') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="payhere_app_id"
                                                                        class="col-form-label">{{ __('App ID') }}</label>
                                                                    <input type="text" name="payhere_app_id"
                                                                        id="payhere_app_id" class="form-control"
                                                                        value="{{ isset($payment['payhere_app_id']) ? $payment['payhere_app_id'] : '' }}"
                                                                        placeholder="{{ __('App ID') }}" />
                                                                    @if ($errors->has('payhere_app_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('payhere_app_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="payhere_app_secret"
                                                                        class="col-form-label">{{ __('App Secret') }}</label>
                                                                    <input type="text" name="payhere_app_secret"
                                                                        id="payhere_app_secret" class="form-control"
                                                                        value="{{ isset($payment['payhere_app_secret']) ? $payment['payhere_app_secret'] : '' }}"
                                                                        placeholder="{{ __('App Secret') }}" />
                                                                    @if ($errors->has('payhere_app_secret'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('payhere_app_secret') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Paiement Pro -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentysix">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentysix"
                                                        aria-expanded="false" aria-controls="collapseTwentysix">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Paiement Pro') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <label class="form-check-label m-1"
                                                                for="is_paiementpro_enabled">{{ __('Enable') }}</label>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paiementpro_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_paiementpro_enabled"
                                                                    id="is_paiementpro_enabled"
                                                                    {{ isset($payment['is_paiementpro_enabled']) && $payment['is_paiementpro_enabled'] == 'on' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentysix" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwentysix"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                {{ Form::label('paiementpro_merchant_id', __('Merchant Id'), ['class' => 'form-label']) }}
                                                                {{ Form::text('paiementpro_merchant_id', !empty($payment['paiementpro_merchant_id']) ? $payment['paiementpro_merchant_id'] : '', ['class' => 'form-control', 'placeholder' => 'Enter Merchant Id']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Nepalste -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentyseven">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentyseven"
                                                        aria-expanded="false" aria-controls="collapseTwentyseven">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Nepalste') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <label class="form-check-label m-1"
                                                                for="is_nepalste_enabled">{{ __('Enable') }}</label>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_nepalste_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_nepalste_enabled" id="is_nepalste_enabled"
                                                                    {{ isset($payment['is_nepalste_enabled']) && $payment['is_nepalste_enabled'] == 'on' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentyseven" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwentyseven"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row pt-2">
                                                            <div class="col-md-12 pb-4">
                                                                <label class="nepalste-label col-form-label"
                                                                    for="nepalste_mode">{{ __('Nepalste Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label text-dark">
                                                                                    <input type="radio"
                                                                                        name="nepalste_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input"
                                                                                        {{ !isset($payment['nepalste_mode']) || $payment['nepalste_mode'] == '' || $payment['nepalste_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class ="form-check">
                                                                                <label class="form-check-label text-dark">
                                                                                    <input type="radio"
                                                                                        name="nepalste_mode"
                                                                                        value="live"
                                                                                        class="form-check-input"
                                                                                        {{ isset($payment['nepalste_mode']) && $payment['nepalste_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('nepalste_public_key', __('Public Key'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('nepalste_public_key', isset($payment['nepalste_public_key']) ? $payment['nepalste_public_key'] : '', ['class' => 'form-control', 'placeholder' => __('Public Key')]) }}<br>
                                                                    @if ($errors->has('nepalste_public_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('nepalste_public_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('nepalste_secret_key', __('Secret Key'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('nepalste_secret_key', isset($payment['nepalste_secret_key']) ? $payment['nepalste_secret_key'] : '', ['class' => 'form-control', 'placeholder' => __('Secret Key')]) }}<br>
                                                                    @if ($errors->has('nepalste_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('nepalste_secret_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- CinetPay -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentyeight">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentyeight"
                                                        aria-expanded="false" aria-controls="collapseTwentyeight">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('CinetPay') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <label class="form-check-label m-1"
                                                                for="is_cinetpay_enabled">{{ __('Enable') }}</label>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_cinetpay_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_cinetpay_enabled" id="is_cinetpay_enabled"
                                                                    {{ isset($payment['is_cinetpay_enabled']) && $payment['is_cinetpay_enabled'] == 'on' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentyeight" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwentyeight"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row mt-2">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="cinetpay_api_key"
                                                                        class="form-label">{{ __('CinetPay API Key') }}</label>
                                                                    <input class="form-control stripe_webhook"
                                                                        placeholder="{{ __('CinetPay API Key') }}"
                                                                        name="cinetpay_api_key" type="text"
                                                                        value="{{ isset($payment['cinetpay_api_key']) ? $payment['cinetpay_api_key'] : '' }}"
                                                                        {{ isset($payment['is_cinetpay_enabled']) && $payment['is_cinetpay_enabled'] == 'on' ? 'checked' : '' }}>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="cinetpay_site_id"
                                                                        class="form-label">{{ __('CinetPay Site ID') }}</label>
                                                                    <input class="form-control stripe_webhook"
                                                                        placeholder="{{ __('CinetPay Site ID') }}"
                                                                        name="cinetpay_site_id" type="text"
                                                                        value="{{ isset($payment['cinetpay_site_id']) ? $payment['cinetpay_site_id'] : '' }}"
                                                                        {{ isset($payment['is_cinetpay_enabled']) && $payment['is_cinetpay_enabled'] == 'on' ? 'checked' : '' }}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- Fedapay -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentynine">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentynine"
                                                        aria-expanded="false" aria-controls="collapseTwentynine">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Fedapay') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <label class="form-check-label m-1"
                                                                for="is_fedapay_enabled">{{ __('Enable') }}</label>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_fedapay_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_fedapay_enabled" id="is_fedapay_enabled"
                                                                    {{ isset($payment['is_fedapay_enabled']) && $payment['is_fedapay_enabled'] == 'on' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentynine" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwentynine"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12 pb-4">
                                                                <label class="fedapay-label col-form-label"
                                                                    for="fedapay_mode">{{ __('Fedapay Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="company_fedapay_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input"
                                                                                        {{ !isset($payment['company_fedapay_mode']) || $payment['company_fedapay_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="company_fedapay_mode"
                                                                                        value="live"
                                                                                        class="form-check-input"
                                                                                        {{ isset($payment['company_fedapay_mode']) && $payment['company_fedapay_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="fedapay_public_key"
                                                                        class="form-label">{{ __('Public Key') }}</label>
                                                                    <input class="form-control stripe_webhook"
                                                                        placeholder="{{ __('Public Key') }}"
                                                                        name="fedapay_public_key" type="text"
                                                                        value="{{ isset($payment['fedapay_public_key']) ? $payment['fedapay_public_key'] : '' }}"
                                                                        id="fedapay_public_key">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="fedapay_secret_key"
                                                                        class="form-label">{{ __('Secret Key') }}</label>
                                                                    <input class="form-control stripe_webhook"
                                                                        placeholder="{{ __('Secret Key') }}"
                                                                        name="fedapay_secret_key" type="text"
                                                                        value="{{ isset($payment['fedapay_secret_key']) ? $payment['fedapay_secret_key'] : '' }}"
                                                                        id="fedapay_secret_key">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p></p>
                                            <!-- Tap -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingThirty">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseThirty"
                                                        aria-expanded="false" aria-controls="collapseThirty">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Tap') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <label class="form-check-label m-1"
                                                                for="is_tap_enabled">{{ __('Enable') }}</label>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_tap_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_tap_enabled" id="is_tap_enabled"
                                                                    {{ isset($payment['is_tap_enabled']) && $payment['is_tap_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseThirty" class="accordion-collapse collapse"
                                                    aria-labelledby="headingThirty" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row pt-2">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="tap_secret"
                                                                        class="form-label">{{ __('Tap Secret') }}</label>
                                                                    <input type="text" name="tap_secret"
                                                                        id="tap_secret" class="form-control"
                                                                        value="{{ !isset($payment['tap_secret']) || is_null($payment['tap_secret']) ? '' : $payment['tap_secret'] }}"
                                                                        placeholder="{{ __('Tap Secret') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- AuthorizeNet  -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingThirty-one">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseThirty-one"
                                                        aria-expanded="false" aria-controls="collapseThirty-one">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('AuthorizeNet') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <label class="form-check-label m-1"
                                                                for="is_authorizenet_enabled">{{ __('Enable') }}</label>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_authorizenet_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_authorizenet_enabled"
                                                                    id="is_authorizenet_enabled"
                                                                    {{ isset($payment['is_authorizenet_enabled']) && $payment['is_authorizenet_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseThirty-one" class="accordion-collapse collapse"
                                                    aria-labelledby="headingThirtyone"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-md-12">
                                                            <label for="authorizenet-label"
                                                                for="is_authorizenet_enabled"
                                                                class="col-form-label">{{ __('AuthorizeNet Mode') }}</label>
                                                            <div class="d-flex">
                                                                <div class="mr-2" style="margin-right: 15px;">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio"
                                                                                    name="authorizenet_mode"
                                                                                    value="sandbox"
                                                                                    class="form-check-input"
                                                                                    {{ isset($payment['authorizenet_mode']) && $payment['authorizenet_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                {{ __('Sandbox') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio"
                                                                                    name="authorizenet_mode"
                                                                                    value="live"
                                                                                    class="form-check-input"
                                                                                    {{ isset($payment['authorizenet_mode']) && $payment['authorizenet_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                {{ __('Live') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row pt-2">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('authorizenet_client_id', __('Merchant Login Id'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('authorizenet_client_id', isset($payment['authorizenet_client_id']) ? $payment['authorizenet_client_id'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Login Id')]) }}<br>
                                                                    @if ($errors->has('authorizenet_client_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('authorizenet_client_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('authorizenet_secret_key', __('Merchant Transaction Key'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('authorizenet_secret_key', isset($payment['authorizenet_secret_key']) ? $payment['authorizenet_secret_key'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Transaction Key')]) }}<br>
                                                                    @if ($errors->has('authorizenet_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('authorizenet_secret_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Ozow  -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingThirty-two">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseThirty-two"
                                                        aria-expanded="false" aria-controls="collapseThirty-two">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Ozow') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <label class="form-check-label m-1"
                                                                for="is_ozow_enabled">{{ __('Enable') }}</label>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_ozow_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_ozow_enabled" id="is_ozow_enabled"
                                                                    {{ isset($payment['is_ozow_enabled']) && $payment['is_ozow_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseThirty-two" class="accordion-collapse collapse"
                                                    aria-labelledby="headingThirtyfive" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="col-md-12">
                                                            <label class="ozow-label col-form-label text-dark"
                                                                for="ozow_mode">{{ __('Ozow Mode') }}</label> <br>
                                                            <div class="d-flex">
                                                                <div class="mr-2" style="margin-right: 15px;">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio" name="ozow_mode"
                                                                                    value="sandbox" class="form-check-input"
                                                                                    {{ isset($payment['ozow_mode']) && $payment['ozow_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>

                                                                                {{ __('Sandbox') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio" name="ozow_mode"
                                                                                    value="live" class="form-check-input"
                                                                                    {{ isset($payment['ozow_mode']) && $payment['ozow_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                {{ __('Live') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row pt-2">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('ozow_site_key', __('Site Key'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('ozow_site_key', isset($payment['ozow_site_key']) ? $payment['ozow_site_key'] : '', ['class' => 'form-control', 'placeholder' => __('Site Key')]) }}<br>
                                                                    @if ($errors->has('ozow_site_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('ozow_site_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('ozow_private_key', __('Private Key'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('ozow_private_key', isset($payment['ozow_private_key']) ? $payment['ozow_private_key'] : '', ['class' => 'form-control', 'placeholder' => __('Private Key')]) }}<br>
                                                                    @if ($errors->has('ozow_private_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('ozow_private_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('ozow_api_key', __('API Key'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('ozow_api_key', isset($payment['ozow_api_key']) ? $payment['ozow_api_key'] : '', ['class' => 'form-control', 'placeholder' => __('API Key')]) }}<br>
                                                                    @if ($errors->has('ozow_api_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('ozow_api_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Khalti  -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingThirty-three">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseThirty-three"
                                                        aria-expanded="false" aria-controls="collapseThirty-three">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Khalti') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <label class="form-check-label m-1"
                                                                for="is_khalti_enabled">{{ __('Enable') }}</label>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_khalti_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_khalti_enabled" id="is_khalti_enabled"
                                                                    {{ isset($payment['is_khalti_enabled']) && $payment['is_khalti_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseThirty-three" class="accordion-collapse collapse"
                                                    aria-labelledby="headingThirtythree" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row pt-2">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('khalti_public_key', __('Public Key'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('khalti_public_key', isset($payment['khalti_public_key']) ? $payment['khalti_public_key'] : '', ['class' => 'form-control', 'placeholder' => __('Public Key')]) }}<br>
                                                                    @if ($errors->has('khalti_public_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('khalti_public_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('khalti_secret_key', __('Secret Key'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('khalti_secret_key', isset($payment['khalti_secret_key']) ? $payment['khalti_secret_key'] : '', ['class' => 'form-control', 'placeholder' => __('Secret Key')]) }}<br>
                                                                    @if ($errors->has('khalti_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('khalti_secret_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer text-end">
                                    <button class="btn-submit btn btn-primary" type="submit">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>


                </div>


                <div id="useradd-5" class="card">
                    {{ Form::open(['url' => route('seo.settings.store'), 'enctype' => 'multipart/form-data']) }}
                    <div class="card-header">
                        <h5>{{ __('SEO Settings') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('meta_keywords', __('Meta Keywords'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('meta_keywords', !empty($settings['meta_keywords']) ? $settings['meta_keywords'] : '', ['class' => 'form-control ', 'placeholder' => 'Meta Keywords']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('meta_description', __('Meta Description'), ['class' => 'form-label']) }}
                                    {{ Form::textarea('meta_description', !empty($settings['meta_description']) ? $settings['meta_description'] : '', ['class' => 'form-control ', 'row' => 2, 'placeholder' => 'Enter Meta Description']) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('Meta Image', __('Meta Image'), ['class' => 'col-form-label ']) }}
                                    <div class="card-body pt-0">
                                        <div class="setting-card">
                                            <div class="logo-content ">
                                                <a
                                                    href="{{ $logo . (isset($meta_image) && !empty($meta_image) ? $meta_image : 'meta-image.png') }}"target="_blank">
                                                    <img id="dark"src="{{ $logo . 'meta-image.png' . '?' . time() }}"
                                                        width="350px" height="200px">
                                                </a>
                                            </div>
                                            <div class="choose-files mt-4">
                                                <label for="meta_image">
                                                    <div class=" bg-primary logo"> <i
                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                    </div>
                                                    <input style="margin-top: -40px;" type="file"
                                                        class="form-control file" name="meta_image" id="meta_image"
                                                        data-filename="meta_image"
                                                        onchange="document.getElementById('meta').src = window.URL.createObjectURL(this.files[0])">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>

                <div id="useradd-6">
                    <div class="card" id="cookie-settings">
                        {{ Form::model($settings, ['route' => 'cookie.setting', 'method' => 'post']) }}
                        <div
                            class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between">
                            <h5>{{ __('Cookie Settings') }}</h5>
                            <div class="d-flex align-items-center">
                                {{ Form::label('enable_cookie', __('Enable cookie'), ['class' => 'col-form-label p-0 fw-bold me-3']) }}
                                <div class="custom-control custom-switch" onclick="enablecookie()">
                                    <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                        name="enable_cookie" class="form-check-input input-primary "
                                        id="enable_cookie" {{ $settings['enable_cookie'] == 'on' ? ' checked ' : '' }}>
                                    <label class="custom-control-label mb-1" for="enable_cookie"></label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body  ">
                            <div
                                class="row cookieDiv {{ $settings['enable_cookie'] == 'off' ? 'disabledCookie ' : '' }}">
                                <div class="col-md-6">
                                    <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                                        <input type="checkbox" name="cookie_logging"
                                            class="form-check-input input-primary cookie_setting"
                                            id="cookie_logging"{{ $settings['cookie_logging'] == 'on' ? ' checked ' : '' }}>
                                        <label class="form-check-label"
                                            for="cookie_logging">{{ __('Enable logging') }}</label>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('cookie_title', __('Cookie Title'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('cookie_title', !empty($settings['cookie_title']) ? $settings['cookie_title'] : '', ['class' => 'form-control cookie_setting']) }}
                                    </div>
                                    <div class="form-group ">
                                        {{ Form::label('cookie_description', __('Cookie Description'), ['class' => ' form-label']) }}
                                        {!! Form::textarea(
                                            'cookie_description',
                                            !empty($settings['cookie_description']) ? $settings['cookie_description'] : '',
                                            ['class' => 'form-control cookie_setting', 'rows' => '3'],
                                        ) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch custom-switch-v1 ">
                                        <input type="checkbox" name="necessary_cookies"
                                            class="form-check-input input-primary" id="necessary_cookies" checked
                                            onclick="return false">
                                        <label class="form-check-label"
                                            for="necessary_cookies">{{ __('Strictly necessary cookies') }}</label>
                                    </div>
                                    <div class="form-group ">
                                        {{ Form::label('strictly_cookie_title', __(' Strictly Cookie Title'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('strictly_cookie_title', !empty($settings['strictly_cookie_title']) ? $settings['strictly_cookie_title'] : '', ['class' => 'form-control cookie_setting']) }}
                                    </div>
                                    <div class="form-group ">
                                        {{ Form::label('strictly_cookie_description', __('Strictly Cookie Description'), ['class' => ' form-label']) }}
                                        {!! Form::textarea(
                                            'strictly_cookie_description',
                                            !empty($settings['strictly_cookie_description']) ? $settings['strictly_cookie_description'] : '',
                                            ['class' => 'form-control cookie_setting ', 'rows' => '3'],
                                        ) !!}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h5>{{ __('More Information') }}</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        {{ Form::label('more_information_description', __('Contact Us Description'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('more_information_description', !empty($settings['more_information_description']) ? $settings['more_information_description'] : '', ['class' => 'form-control cookie_setting']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        {{ Form::label('contactus_url', __('Contact Us URL'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('contactus_url', !empty($settings['contactus_url']) ? $settings['contactus_url'] : '', ['class' => 'form-control cookie_setting']) }}
                                    </div>
                                </div>
                            </div>
                            <div
                                class="modal-footer d-flex align-items-center gap-2 flex-sm-column flex-lg-row justify-content-between">
                                <div>
                                    @if (isset($settings['cookie_logging']) && $settings['cookie_logging'] == 'on')
                                        <label for="file"
                                            class="form-label">{{ __('Download cookie accepted data') }}</label>
                                        <a href="{{ asset(Storage::url('uploads/sample')) . '/data.csv' }}"
                                            class="btn btn-primary mr-2 ">
                                            <i class="ti ti-download"></i>
                                        </a>
                                    @endif
                                </div>
                                <input type="submit" value="{{ __('Submit') }}" class="btn btn-primary">
                            </div>
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>

                <div id="useradd-7" class="card">
                    {{ Form::open(['url' => route('cache.settings'), 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                    <div class="col-md-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <h5>{{ __('Cache Settings') }}</h5>
                                    <small
                                        class="text-secondary font-weight-bold">{{ __("This is a page meant for more advanced users, simply ignore it if you don't understand what cache is.") }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="input-group search-form">
                                    <input type="text" value="{{ $file_size }}" class="form-control" disabled>
                                    <span class="input-group-text bg-transparent">MB</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer m-3">
                            {{ Form::submit(__('Clear Cache'), ['class' => 'btn btn-print-invoice  btn-primary m-r-10']) }}
                        </div>

                    </div>
                    {{ Form::close() }}
                </div>

                <!--recaptcha Setting-->
                <div id="useradd-8" class="card">
                    {{ Form::open(['route' => 'recaptcha.settings.store', 'method' => 'post']) }}
                    <div class="col-md-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <h5>{{ __('ReCaptcha Settings') }}</h5>
                                    <small>{{ __('How to Get Google reCaptcha Site and Secret key.') }}</small>
                                </div>

                                <div class="col-lg-4 col-md-4 text-end">
                                    <div class="form-check custom-control custom-switch">
                                        <input type="checkbox" class="form-check-input" name="recaptcha_module"
                                            data-toggle="switchbutton" data-onstyle="primary" id="recaptcha_module"
                                            {{ !empty($settings['recaptcha_module']) && $settings['recaptcha_module'] == 'on' ? 'checked' : '' }}>
                                        <label class="custom-control-label form-label" for="recaptcha_module"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                @php
                                    $google_recaptcha_version = ['v2' => __('v2'), 'v3' => __('v3')];
                                @endphp
                                <div class="col-md-6">
                                    <div class="form-group col switch-width">
                                        {{ Form::label('google_recaptcha_version', __('Google Recaptcha Version'), ['class' => 'form-label']) }}

                                        {{ Form::select('google_recaptcha_version', $google_recaptcha_version, isset($settings['google_recaptcha_version']) ? $settings['google_recaptcha_version'] : 'v2-checkbox', ['id' => 'google_recaptcha_version', 'class' => 'form-control form-select', 'searchEnabled' => 'true']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="google_recaptcha_key"
                                            class="form-label">{{ __('Google Recaptcha Key') }}</label>
                                        <input class="form-control" placeholder="Enter Google Recaptcha Key"
                                            name="google_recaptcha_key" type="text"
                                            value="{{ !empty($settings['google_recaptcha_key']) ? $settings['google_recaptcha_key'] : '' }}"
                                            id="google_recaptcha_key">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="google_recaptcha_secret"
                                            class="form-label">{{ __('Google Recaptcha Secret') }}</label>
                                        <input class="form-control" placeholder="Enter Google Recaptcha Secret"
                                            name="google_recaptcha_secret" type="text"
                                            value="{{ !empty($settings['google_recaptcha_secret']) ? $settings['google_recaptcha_secret'] : '' }}"
                                            id="google_recaptcha_secret">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer m-3">
                            {{ Form::submit(__('Submit'), ['class' => 'btn btn-print-invoice  btn-primary m-r-10']) }}
                        </div>

                    </div>

                    {{ Form::close() }}
                </div>
                <!--storage Setting-->
            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
@endsection


@push('pre-purpose-script-page')
    <script>
        $('.colorPicker').on('click', function(e) {

            $('body').removeClass('custom-color');
            if (/^theme-\d+$/) {
                $('body').removeClassRegex(/^theme-\d+$/);
            }
            $('body').addClass('custom-color');
            $('.themes-color-change').removeClass('active_color');
            $(this).addClass('active_color');
            const input = document.getElementById("color-picker");
            setColor();
            input.addEventListener("input", setColor);

            function setColor() {

                document.documentElement.style.setProperty('--color-customColor', input.value);
                console.log($(input.value));
            }

            $(`input[name='color_flag`).val('true');
        });

        $('.themes-color-change').on('click', function() {

            $(`input[name='color_flag`).val('false');

            var color_val = $(this).data('value');
            $('body').removeClass('custom-color');
            if (/^theme-\d+$/) {
                $('body').removeClassRegex(/^theme-\d+$/);
            }
            $('body').addClass(color_val);
            $('.theme-color').prop('checked', false);
            $('.themes-color-change').removeClass('active_color');
            $('.colorPicker').removeClass('active_color');
            $(this).addClass('active_color');
            $(`input[value=${color_val}]`).prop('checked', true);
        });

        $.fn.removeClassRegex = function(regex) {
            return $(this).removeClass(function(index, classes) {
                return classes.split(/\s+/).filter(function(c) {
                    return regex.test(c);
                }).join(' ');
            });
        };
    </script>
    <script type="text/javascript">
        function enablecookie() {

            const element = $('#enable_cookie').is(':checked');
            $('.cookieDiv').addClass('disabledCookie');
            if (element == true) {
                $('.cookieDiv').removeClass('disabledCookie');
                $("#cookie_logging").prop('checked', true);
            } else {
                $('.cookieDiv').addClass('disabledCookie');
                $("#cookie_logging").prop('checked', false);
            }
        }
    </script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
    <script>
        $(document).on("click", '.send_email', function(e) {

            e.preventDefault();
            var title = $(this).attr('data-title');

            var size = 'md';
            var url = $(this).attr('data-url');
            if (typeof url != 'undefined') {
                $("#exampleModal .modal-title").html(title);
                $("#exampleModal .modal-dialog").addClass('modal-' + size);
                $("#exampleModal").modal('show');

                $.post(url, {
                    mail_driver: $("#mail_driver").val(),
                    mail_host: $("#mail_host").val(),
                    mail_port: $("#mail_port").val(),
                    mail_username: $("#mail_username").val(),
                    mail_password: $("#mail_password").val(),
                    mail_encryption: $("#mail_encryption").val(),
                    mail_from_address: $("#mail_from_address").val(),
                    mail_from_name: $("#mail_from_name").val(),
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    $('#exampleModal .modal-body').html(data);
                });
            }
        });
        $(document).on('submit', '#test_email', function(e) {
            e.preventDefault();
            $("#email_sending").show();
            var post = $(this).serialize();
            console.log(post);

            var url = $(this).attr('action');
            $.ajax({
                type: "post",
                url: url,
                data: post,
                cache: false,
                beforeSend: function() {
                    $('#test_smtp.mailtrap.iosmtp.mailtrap.io.btn-create').attr('disabled', 'disabled');
                },
                success: function(data) {
                    if (data.is_success) {
                        toastrs('Success', data.message, 'success');
                    } else {
                        toastrs('Error', data.message, 'error');
                    }
                    $("#email_sending").hide();
                    $("#exampleModal").modal('hide');

                },
                complete: function() {
                    $('#test_email .btn-create').removeAttr('disabled');
                },
            });
        })
    </script>




    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        })
        $(".list-group-item").click(function() {
            $('.list-group-item').filter(function() {
                return this.href == id;
            }).parent().removeClass('text-primary');
        });



        $(document).on('change', '[name=storage_setting]', function() {
            if ($(this).val() == 's3') {
                $('.s3-setting').removeClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').addClass('d-none');
            } else if ($(this).val() == 'wasabi') {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').removeClass('d-none');
                $('.local-setting').addClass('d-none');
            } else {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').removeClass('d-none');
            }
        });
    </script>

    <script>
        function check_theme(color_val) {
            $('body').removeClass($(".theme_color:checked").val());
            $('body').addClass(color_val);

            $('input[value="' + color_val + '"]').prop('checked', true);
            $('input[value="' + color_val + '"]').attr('checked', true);
            $('a[data-value]').removeClass('active_color');
            $('a[data-value="' + color_val + '"]').addClass('active_color');
        }
    </script>
    <script src="{{ asset('assets/js/jscolor.js') }} "></script>

    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script>
        if ($(".multi-select").length > 0) {
            $($(".multi-select")).each(function(index, element) {
                var id = $(element).attr('id');
                var multipleCancelButton = new Choices(
                    '#' + id, {
                        removeItemButton: true,
                    }
                );
            });
        }

        var textRemove = new Choices(
            document.getElementById('choices-text-remove-button'), {
                delimiter: ',',
                editItems: true,
                maxItemCount: 5,
                removeItemButton: true,
            }
        );
    </script>

    <script>
        $(document).ready(function() {
            cust_darklayout();
            $('#cust-darklayout').trigger('cust-darklayout');
        });

        function cust_darklayout() {
            var custdarklayout = document.querySelector("#cust-darklayout");
            custdarklayout.addEventListener("click", function() {
                if (custdarklayout.checked) {
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "{{ asset('assets/css/style-dark.css') }}");
                    document
                        .querySelector(".m-header > .b-brand > .logo-lg")
                        .setAttribute("src", "{{ asset('/storage/logo/logo-light.png') }}");
                } else {
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "{{ asset('assets/css/style.css') }}");
                    document
                        .querySelector(".m-header > .b-brand > .logo-lg")
                        .setAttribute("src", "{{ asset('/storage/logo/logo-dark.png') }}");
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            cust_theme_bg();
            $('#cust-theme-bg').trigger('cust-theme-bg');
        });

        function cust_theme_bg() {

            var custthemebg = document.querySelector("#cust-theme-bg");
            custthemebg.addEventListener("click", function() {
                if (custthemebg.checked) {
                    document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.add("transprent-bg");
                } else {
                    document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.remove("transprent-bg");
                }
            });
        }
    </script>
@endpush
