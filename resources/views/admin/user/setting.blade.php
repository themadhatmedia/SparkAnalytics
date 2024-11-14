@extends('layouts.admin')


@section('page-title')
    {{ __('Settings') }}
@endsection
<?php

$logo = \App\Models\Utility::get_file('logo/');
$color = 'theme-3';
if (!empty($setting['color'])) {
    $color = $setting['color'];
}
$SITE_RTL = 'off';
if (!empty($setting['SITE_RTL'])) {
    $SITE_RTL = $setting['SITE_RTL'];
}
$dark_logo = $setting['company_dark_logo'];
$light_logo = $setting['company_light_logo'];

$company_favicon = $setting['company-favicon'];

$lang = 'en';
if (isset($setting['default_language']) && $setting['default_language'] != '') {
    $lang = $setting['default_language'];
}
?>


@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Settings') }}</li>
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
                            class="list-group-item list-group-item-action border-0">{{ __('System Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-3"
                            class="list-group-item list-group-item-action border-0">{{ __('Email Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-4"
                            class="list-group-item list-group-item-action border-0">{{ __('Slack Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-5" class="list-group-item list-group-item-action border-0">{{ __('Report') }}
                            {{ __('Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div id="useradd-1" class="card">
                    {{ Form::open(['route' => ['company.settings.store'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                    <div class="card-header">
                        <h5>{{ __('Brand Settings') }}</h5>
                        <small class="text-muted">{{ __('Edit your brand details') }}</small>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="card logo_card ">
                                    <div class="card-header">
                                        <h5 class="small-title">{{ __('Light Logo') }}</h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="setting-card">
                                            <div class="logo-content text-center mt-4 ">
                                                <a href="{{ $logo . '/' . (isset($light_logo) && !empty($light_logo) ? $light_logo : 'logo-light.png') }}"
                                                    target="_blank"> <img id="light"
                                                        src="{{ $logo . '/' . (isset($light_logo) && !empty($light_logo) ? $light_logo : 'logo-light.png') }}"
                                                        class="img_setting big-logo"> </a>

                                            </div>
                                            <div class="choose-files mt-5">
                                                <label for="logo">
                                                    <div class=" bg-primary logo_update"> <i
                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        <input style="margin-top: -40px;" type="file"
                                                            class="form-control file" name="light_logo" id="light_logo"
                                                            data-filename="edit-light_logo" accept=".jpeg,.jpg,.png"
                                                            accept=".jpeg,.jpg,.png"
                                                            onchange="document.getElementById('light').src = window.URL.createObjectURL(this.files[0])">
                                                    </div>
                                                </label>
                                            </div>
                                            {{-- @error('light_logo')
                                                    <div class="row">
                                                        <span class="invalid-logo" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    </div>
                                                @enderror --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Light Logo --}}
                            <div class="col-sm-4">
                                <div class="card logo_card ">
                                    <div class="card-header">

                                        <h5>{{ __('Dark Logo') }}</h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="setting-card">
                                            <div class="logo-content text-center mt-4 ">

                                                <a href="{{ $logo . '/' . (isset($dark_logo) && !empty($dark_logo) ? $dark_logo : 'logo-dark.png') }}"
                                                    target="_blank"> <img id="logo" class="big-logo"
                                                        src="{{ $logo . '/' . (isset($dark_logo) && !empty($dark_logo) ? $dark_logo : 'logo-dark.png') }}">
                                                </a>

                                            </div>
                                            <div class="choose-files mt-5">
                                                <label for="logo">
                                                    <div class=" bg-primary logo_update" style="width:180px;"> <i
                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        <input style="margin-top: -40px;" type="file"
                                                            class="form-control file" name="dark_logo" id="dark_logo"
                                                            data-filename="edit-dark_logo" accept=".jpeg,.jpg,.png"
                                                            accept=".jpeg,.jpg,.png"
                                                            onchange="document.getElementById('logo').src = window.URL.createObjectURL(this.files[0])">
                                                    </div>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Favicon Logo --}}
                            <div class="col-lg-4 col-sm-6 col-md-6">
                                <div class="card logo_card">
                                    <div class="card-header">
                                        <h5>{{ __('Favicon') }}</h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="setting-card ">
                                            <div class="logo-content text-center mt-4 ">
                                                <a href="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
                                                    target="_blank"><img
                                                        src="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
                                                        width="50px" id="favicon" class=""></a>
                                            </div>
                                            <div class="choose-files mt-5">
                                                <label for="logo">
                                                    <div class=" bg-primary logo_update" style="width:180px;"> <i
                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        <input style="margin-top: -40px;" type="file"
                                                            class="form-control file" name="company_favicon"
                                                            id="company_favicon" data-filename="edit-company_favicon"
                                                            accept=".jpeg,.jpg,.png" accept=".jpeg,.jpg,.png"
                                                            onchange="document.getElementById('favicon').src = window.URL.createObjectURL(this.files[0])">
                                                    </div>
                                                </label>
                                            </div>
                                            {{-- @error('company_favicon')
                                                    <div class="row">
                                                        <span class="invalid-logo" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    </div>
                                                @enderror --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('header_text', __('Title Text'), ['class' => 'col-form-label text-dark']) }}
                                    <input class="form-control" placeholder="Title Text" name="header_text"
                                        type="text"
                                        value="{{ !empty($setting['header_text']) ? $setting['header_text'] : '' }}"
                                        id="header_text">
                                    @error('header_text')
                                        <span class="invalid-header_text" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('header_text', __('Footer Text'), ['class' => 'col-form-label text-dark']) }}
                                    @php $year=date('Y'); @endphp
                                    <input class="form-control" placeholder="Title Text" name="footer_text"
                                        type="text"
                                        value="{{ isset($setting['footer_text']) ? $setting['footer_text'] : 'AnalyticsGo SaaS' }}"
                                        id="footer_text">
                                    @error('footer_text')
                                        <span class="invalid-footer_text" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('default_language', __('Default Language'), ['class' => 'col-form-label text-dark']) }}
                                    <select name="default_language" id="default_language" class="form-control select2">
                                        @foreach (\App\Models\Utility::languages() as $code => $language)
                                            <option @if ($lang == $code) selected @endif
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

                            <div class="col-md-4">
                                <div class="col-4 switch-width">
                                    <div class="form-group ml-2 mr-3 ">
                                        {{ Form::label('SITE_RTL', __('Enable RTL'), ['class' => 'col-form-label text-dark']) }}
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                                class="" name="SITE_RTL" value="on" id="SITE_RTL"
                                                {{ $SITE_RTL == 'on' ? 'checked="checked"' : '' }}>
                                            <label class="custom-control-label" for="SITE_RTL"></label>
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
                                                        <div class="color-picker-wrp">
                                                            <input type="color" value="{{ $color ? $color : '' }}"
                                                                class="colorPicker {{ isset($setting['color_flag']) && $setting['color_flag'] == 'true' ? 'active_color' : '' }} image-input"
                                                                name="custom_color" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" id="color-picker">
                                                            <input type="hidden" name="custom-color" id="colorCode">
                                                            <input type='hidden' name="color_flag"
                                                                value={{ isset($setting['color_flag']) && $setting['color_flag'] == 'true' ? 'true' : 'false' }}>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-xl-4 col-md-4">
                                                    <h6 class="mt-2">
                                                        <i data-feather="layout"
                                                            class="me-2"></i>{{ __('Sidebar Settings') }}
                                                    </h6>

                                                    <hr class="my-2" />
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="cust-theme-bg" name="cust_theme_bg" {{-- {{ Utility::getValByName('cust_theme_bg') == 'on' ? 'checked' : '' }}> --}}
                                                            {{ !empty($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on' ? 'checked' : '' }} />
                                                        <label class="form-check-label f-w-600 pl-1"
                                                            for="cust-theme-bg">{{ __('Transparent layout') }}</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-xl-4 col-md-4">
                                                    <h6 class="mt-2">
                                                        <i data-feather="sun"
                                                            class="me-2"></i>{{ __('Layout Settings') }}
                                                    </h6>
                                                    <hr class="my-2" />
                                                    <div class="form-check form-switch mt-2">
                                                        <input type="hidden" name="cust_darklayout" value="off">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="cust-darklayout" name="cust_darklayout"
                                                            {{ !empty($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on' ? 'checked' : '' }} />

                                                        <label class="form-check-label f-w-600 pl-1"
                                                            for="cust-darklayout">{{ __('Dark Layout') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-print-invoice  btn-primary m-r-10']) }}
                            </div>

                        </div>

                    </div>

                    {{ Form::close() }}
                </div>

                <div id="useradd-2" class="card">
                    {{ Form::open(['route' => ['company.settings.system.store'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                    <div class="card-header">
                        <h5>{{ __('System Settings') }}</h5>
                        <small class="text-muted">{{ __('This SMTP will be used for sending your company-level email. If this field is empty, then SuperAdmin SMTP will be used for sending emails.') }}</small>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label for="title_text" class="form-label">{{ __('Date format') }}</label>
                                    {!! Form::select(
                                        'date_format',
                                        ['d/m/Y' => 'DD/MM/YYYY', 'm-d-Y' => 'MM-DD-YYYY', 'd-m-Y' => 'DD-MM-YYYY'],
                                        !empty($setting['date_format']) ? $setting['date_format'] : '',
                                        ['class' => 'form-control ', 'required' => 'required'],
                                    ) !!}

                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group ">
                                    <label for="title_text"
                                        class="form-label">{{ __('Set this url as Authorized redirect URIs') }}</label>
                                    <input type="text" class="form-control" name="outh_path"
                                        value="<?= url('/') . '/oauth2callback' ?>" disabled="">

                                </div>
                            </div>

                            <div class="modal-footer">
                                <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
                            </div>
                        </div>
                    </div>

                    {{ Form::close() }}
                </div>

                <div id="useradd-3" class="card">
                    {{ Form::open(['route' => 'company.email.settings.store', 'method' => 'post', 'class'=>'needs-validation', 'novalidate']) }}
                    <div class="card-header">
                        <h5>{{ __('Email Settings') }}</h5>
                        <small class="text-muted">{{ __(' This SMTP will be used for sending your company-level email. If this field is empty, then SuperAdmin SMTP will be used for sending emails.') }}</small>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_driver', __('Mail Driver'), ['class' => 'col-form-label text-dark']) }}<x-required></x-required>
                                {{ Form::text('mail_driver', !empty($setting['mail_driver']) ? $setting['mail_driver'] : '', ['class' => 'form-control' ,'required'=>'required' , 'placeholder' => __('Enter Mail Driver')]) }}
                                @error('mail_driver')
                                    <span class="invalid-mail_driver" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_host', __('Mail Host'), ['class' => 'col-form-label text-dark']) }}<x-required></x-required>
                                {{ Form::text('mail_host', !empty($setting['mail_host']) ? $setting['mail_host'] : '', ['class' => 'form-control' ,'required'=>'required' , 'placeholder' => __('Enter Mail Driver')]) }}
                                @error('mail_host')
                                    <span class="invalid-mail_host" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_port', __('Mail Port'), ['class' => 'col-form-label text-dark']) }}<x-required></x-required>
                                {{ Form::text('mail_port', !empty($setting['mail_port']) ? $setting['mail_port'] : '', ['class' => 'form-control' ,'required'=>'required' , 'placeholder' => __('Enter Mail Port')]) }}
                                @error('mail_port')
                                    <span class="invalid-mail_port" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_username', __('Mail Username'), ['class' => 'col-form-label text-dark']) }}<x-required></x-required>
                                {{ Form::text('mail_username', !empty($setting['mail_username']) ? $setting['mail_username'] : '', ['class' => 'form-control' ,'required'=>'required' , 'placeholder' => __('Enter Mail Username')]) }}
                                @error('mail_username')
                                    <span class="invalid-mail_username" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_password', __('Mail Password'), ['class' => 'col-form-label text-dark']) }}<x-required></x-required>
                                {{ Form::text('mail_password', !empty($setting['mail_password']) ? $setting['mail_password'] : '', ['class' => 'form-control' ,'required'=>'required' , 'placeholder' => __('Enter Mail Password')]) }}
                                @error('mail_password')
                                    <span class="invalid-mail_password" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'col-form-label text-dark']) }}<x-required></x-required>
                                {{ Form::text('mail_encryption', !empty($setting['mail_encryption']) ? $setting['mail_encryption'] : '', ['class' => 'form-control' ,'required'=>'required' , 'placeholder' => __('Enter Mail Encryption')]) }}
                                @error('mail_encryption')
                                    <span class="invalid-mail_encryption" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_from_address', __('Mail From Address'), ['class' => 'col-form-label text-dark']) }}<x-required></x-required>
                                {{ Form::text('mail_from_address', !empty($setting['mail_from_address']) ? $setting['mail_from_address'] : '', ['class' => 'form-control' ,'required'=>'required' , 'placeholder' => __('Enter Mail From Address')]) }}
                                @error('mail_from_address')
                                    <span class="invalid-mail_from_address" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'col-form-label text-dark']) }}<x-required></x-required>
                                {{ Form::text('mail_from_name', !empty($setting['mail_from_name']) ? $setting['mail_from_name'] : '', ['class' => 'form-control' ,'required'=>'required' , 'placeholder' => __('Enter Mail Encryption')]) }}
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
                                    <a href="#" data-url="{{ route('company.test.mail') }}" id="test_email"
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

                <div id="useradd-4" class="card">
                    {{ Form::open(['route' => ['company.slack.settings'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                    <div class="card-header">
                        <h5>{{ __('Slack Settings') }}</h5>
                        <small class="text-muted">{{ __('Edit your Slack details') }}</small>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-9">
                                <div class="form-group ">
                                    <label for="title_text" class="form-label">{{ __('Slack Webhook URL') }}</label>
                                    <input type="text" class="form-control" name="slack_webhook"
                                        value="{{ !empty($setting['slack_webhook']) ? $setting['slack_webhook'] : '' }}"
                                        placeholder="{{ __('Slack Webhook URL') }}">

                                </div>
                            </div>

                            <div class="modal-footer">
                                <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
                            </div>
                        </div>
                    </div>

                    {{ Form::close() }}
                </div>
                <div id="useradd-5" class="card">
                    {{ Form::open(['route' => ['company.report.settings'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                    <div class="card-header">
                        <h5>{{ __('Report') }}</h5>
                        <small
                            class="text-muted">{{ __('How often do you want to receive summary reports about your primary metrics?') }}</small>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-6 form-group">
                                <div class="list-group">
                                    <div class="list-group-item form-switch form-switch-right">
                                        <label class="form-label"
                                            style="margin-left:5%;">{{ __('Email Notifiation') }}</label>
                                        <input class="form-check-input " onchange="frequency_status()"
                                            id="email_notifiation" type="checkbox" value="0"
                                            name="email_notifiation"
                                            {{ !empty($report_setting->email_notification) && $report_setting->email_notification == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_notifiation"></label>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group" id="frequency_block" style="display: none;">
                                <h4 class="small-title">{{ __('Frequency of new reports') }}</h4>
                                <hr class="my-2" />
                                <div class="d-flex flex-wrap gap-3 mb-2 mb-md-0 mt-2">
                                    <div class="form-check form-switch col-lg-2">
                                        <input type="checkbox" class="form-check-input" id="is_daily" name="is_daily"
                                            {{-- {{ Utility::getValByName('cust_theme_bg') == 1 ? 'checked' : '' }}> --}}
                                            {{ !empty($report_setting->is_daily) && $report_setting->is_daily == 1 ? 'checked' : '' }} />
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="is_daily">{{ __('Daily') }}</label>
                                    </div>
                                    <div class="form-check form-switch col-lg-2">
                                        <input type="checkbox" class="form-check-input" id="is_weekly" name="is_weekly"
                                            {{-- {{ Utility::getValByName('cust_theme_bg') == 1 ? 'checked' : '' }}> --}}
                                            {{ !empty($report_setting->is_weekly) && $report_setting->is_weekly == 1 ? 'checked' : '' }} />
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="is_weekly">{{ __('Weekly') }}</label>
                                    </div>
                                    <div class="form-check form-switch col-lg-2">
                                        <input type="checkbox" class="form-check-input" id="is_monthly"
                                            name="is_monthly" {{-- {{ Utility::getValByName('cust_theme_bg') == 1 ? 'checked' : '' }}> --}}
                                            {{ !empty($report_setting->is_monthly) && $report_setting->is_monthly == 1 ? 'checked' : '' }} />
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="is_monthly">{{ __('Monthly') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
                            </div>
                        </div>
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/repeater.js') }}"></script>
    <script src="{{ asset('assets/js/colorPick.js') }}"></script>
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
        $(document).ready(function() {

            frequency_status();

        });

        function frequency_status() {
            if ($('#email_notifiation').prop('checked') == true || $('#slack_notifiation').prop('checked') == true) {
                $('#frequency_block').css('display', '');
            } else {
                $('#frequency_block').css('display', 'none');
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
                    $('#test_email .btn-create').attr('disabled', 'disabled');
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

    {{-- <script>
        $(document).on('click', 'input[name="theme_color"]', function() {
            var eleParent = $(this).attr('data-theme');
            $('#themefile').val(eleParent);
            var imgpath = $(this).attr('data-imgpath');
            $('.' + eleParent + '_img').attr('src', imgpath);
        });

        $(document).ready(function() {
            setTimeout(function(e) {
                var checked = $("input[type=radio][name='theme_color']:checked");
                $('#themefile').val(checked.attr('data-theme'));
                $('.' + checked.attr('data-theme') + '_img').attr('src', checked.attr('data-imgpath'));
            }, 300);
        });

        function check_theme(color_val) {

            $('body').removeClass($("input[name=color]:checked").val());
            $('body').addClass(color_val);

            $('input[value="' + color_val + '"]').prop('checked', true);
            $('input[value="' + color_val + '"]').attr('checked', true);
            $('a[data-value]').removeClass('active_color');
            $('a[data-value="' + color_val + '"]').addClass('active_color');
        }
    </script> --}}

    <script type="text/javascript">
        $(document).on("click", ".email-template-checkbox", function() {
            var chbox = $(this);
            $.ajax({
                url: chbox.attr('data-url'),
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    status: chbox.val()
                },
                type: 'POST',
                success: function(response) {
                    if (response.is_success) {
                        toastrs('{{ __('Success') }}', response.success, 'success');
                        if (chbox.val() == 1) {
                            $('#' + chbox.attr('id')).val(0);
                        } else {
                            $('#' + chbox.attr('id')).val(1);
                        }
                    } else {
                        toastrs('{{ __('Error') }}', response.error, 'error');
                    }
                },
                error: function(response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        toastrs('{{ __('Error') }}', response.error, 'error');
                    } else {
                        toastrs('{{ __('Error') }}', response, 'error');
                    }
                }
            })
        });
    </script>

    <script>// Dark layout in toggle
        $(document).ready(function() {
            cust_darklayout();
            $('#cust-darklayout').trigger('cust-darklayout');
        });

        function cust_darklayout() {

            var custdarklayout = document.querySelector("#cust-darklayout");
            custdarklayout.addEventListener("click", function() {
                if (custdarklayout.checked) {
                    // document
                    //         .querySelector(".m-header > .b-brand > .logo-lg")
                    //         .setAttribute("src", "{{ asset('assets/images/logo-light.png') }}");
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "{{ asset('assets/css/style-dark.css') }}");
                } else {
                    // document
                    //         .querySelector(".m-header > .b-brand > .logo-lg")
                    //         .setAttribute("src", "{{ asset('assets/images/logo-dark.png') }}");
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "{{ asset('assets/css/style.css') }}");
                }
            });
        }
    </script>

    <script>// side bar and nav bar layout on toggle
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
