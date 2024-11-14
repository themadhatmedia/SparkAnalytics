<?php

     $logos = \App\Models\Utility::get_file('logo/');

    $favicon = \App\Models\Utility::getValByName('favicon');
    $company_favicon = \App\Models\Utility::getValByName('comapny_favicon');

    $setting = App\Models\Utility::colorset();
    $settings = App\Models\Utility::settings();
    $color = 'theme-3';
    if (!empty($setting['color'])) {
        $color = $setting['color'];
    }
    $SITE_RTL = \App\Models\Utility::getValByName('SITE_RTL');
    $cust_darklayout = \App\Models\utility::settings('cust_darklayout');
    $year=date('Y');
    $footer_text = isset(\App\Models\Utility::settings()['footer_text']) ? \App\Models\Utility::settings()['footer_text'] : '© '. $year.' AnalyticsGo SaaS';

    $company_logo = \App\Models\Utility::get_company_logo();
    $company_logo = \App\Models\Utility::getValByName('light_logo');
    $company_dark_logo = \App\Models\Utility::getValByName('dark_logo');
    $landing=\App\Models\Utility::get_file('landing/');
    $meta_img = \App\Models\Utility::getValByName('meta_image');
    $meta_setting = App\Models\Utility::settings();

?>

<!DOCTYPE html>
<html lang="en" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">

<head>
    <title>
        {{ \App\Models\Utility::getValByName('header_text') ? \App\Models\Utility::getValByName('header_text') : config('app.name', 'AnalyticsGo SaaS') }}
        - @yield('page-title')</title>

    <meta charset="utf-8">
    <!-- Primary Meta Tags -->

    <meta name="title" content="{{$meta_setting['meta_keywords']}}">
    <meta name="description" content="{{$meta_setting['meta_description']}}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://demo.workdo.io/analyticsgo/">
    <meta property="og:title" content="{{$meta_setting['meta_keywords']}}">
    <meta property="og:description" content="{{$meta_setting['meta_description']}}">
    <meta property="og:image" content="{{ $logos .'/'. (isset($meta_img) && !empty($meta_img) ? $meta_img : 'meta-image.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://demo.workdo.io/analyticsgo/">
    <meta property="twitter:title" content="{{$meta_setting['meta_keywords']}}">
    <meta property="twitter:description" content="{{$meta_setting['meta_description']}}">
    <meta property="twitter:image" content="{{ $logos .'/'. (isset($meta_img) && !empty($meta_img) ? $meta_img : 'meta-image.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Favicon icon -->

        <link rel="icon" href="{{$logos .'/favicon.png?'. time()}}" type="image/x-icon" />



    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}" />
    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <!-- vendor css -->

    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
    @endif
    @if (isset($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"id="main-style-link">
    @endif

    <style>
        [dir="rtl"] .dash-sidebar {
            left: auto !important;
        }

        [dir="rtl"] .dash-header {
            left: 0;
            right: 280px;
        }

        [dir="rtl"] .dash-header:not(.transprent-bg) .header-wrapper {
            padding: 0 0 0 30px;
        }

        [dir="rtl"] .dash-header:not(.transprent-bg):not(.dash-mob-header)~.dash-container {
            margin-left: 0px !important;
        }

        [dir="rtl"] .me-auto.dash-mob-drp {
            margin-right: 10px !important;
        }

        [dir="rtl"] .me-auto {
            margin-left: 10px !important;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/landing.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}" />

    <link rel="stylesheet" href="{{ asset('public/custom/css/custom.css') }}">
</head>

<body class="{{ $color }}">
    <!-- [ Nav ] start -->
    <nav class="navbar navbar-expand-md navbar-dark default top-nav-collapse">
        <div class="container">
            <a class="navbar-brand bg-transparent" href="#">


                @if (isset($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on')

                    <img alt="{{ env('header_text') }}" src="{{ $logos . 'logo-light.png' . '?' . time()}}" width='150px'>
                @else
                    <img alt="{{ env('header_text') }}" src="{{ $logos . 'logo-light.png'. '?' . time() }}" width='150px'>
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01" style="flex-grow: 0;">
                <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">{{ __('Home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">{{ __('Features') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#layouts">{{ __('Layouts') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonial">{{ __('Testimonial') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">{{ __('Pricing') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">{{ __('Faq') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light ms-2 me-1" href="{{ route('login','en') }}">{{ __('Login') }}</a>
                    </li>
                    @if (\App\Models\Utility::getValByName('SIGNUP') == 'on')
                        <li class="nav-item">
                            <a class="btn btn-light ms-2 me-1" href="{{ route('register','en') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ Nav ] start -->
    <!-- [ Header ] start -->
    <header id="home" class="bg-primary">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-sm-5">
                    <h1 class="text-white mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.2s">
                        {{ __('AnalyticsGo SaaS ') }}
                    </h1>
                    <h2 class="text-white mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.4s">
                        {{ __('Google Analytics with Multisite') }}
                    </h2>
                    <p class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.6s">
                        {{ __(' Use these awesome forms to login or create new account in your
                                            project for free.') }}
                    </p>
                    <div class="my-4 wow animate__fadeInLeft" data-wow-delay="0.8s">
                        <a href="https://demo.workdo.io/analyticsgo-saas/login" class="btn btn-light me-2"><i
                                class="far fa-eye me-2"></i>{{ __('Live Demo') }}</a>
                        <a href="#"
                            class="btn btn-outline-light" target="_blank"><i
                                class="fas fa-shopping-cart me-2"></i>{{ __('Buy now') }}</a>
                    </div>
                </div>
                <div class="col-sm-5">
                    <img src="{{ asset('assets/images/front/header-mokeup.svg') }}" alt="Datta Able Admin Template"
                        class="img-fluid header-img wow animate__fadeInRight" data-wow-delay="0.2s" />
                </div>
            </div>
        </div>
    </header>
    <!-- [ Header ] End -->
    <!-- [ client ] Start -->
    <section id="dashboard" class="theme-alt-bg dashboard-block">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-9 title">
                    <h2><span>{{__('Happy clients use Dashboard')}} </span></h2>
                </div>
            </div>
            <div class="row align-items-center justify-content-center  mobile-screen">
                <div class="col-auto">
                    <div class="wow animate__fadeInRight mobile-widget" data-wow-delay="0.2s">
                        {{-- <img src="{{ Storage::url('logo/' . $logo) }}" alt="{{ env('header_text') }}" alt=""
                    class="navbar-logo" /> --}}
                        @if (isset($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on')
                            <img alt="{{ env('header_text') }}" src="{{ $logos . 'logo-light.png' }}">
                        @else
                            <img alt="{{ env('header_text') }}" src="{{ $logos . 'logo-dark.png' }}">
                        @endif

                    </div>
                </div>
                <div class="col-auto">
                    <div class="wow animate__fadeInRight mobile-widget" data-wow-delay="0.4s">
                        {{-- <img src="{{ Storage::url('logo/' . $logo) }}" alt="{{ env('header_text') }}" alt=""
                    class="navbar-logo" /> --}}
                        @if (isset($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on')
                            <img alt="{{ env('header_text') }}" src="{{ $logos . 'logo-light.png' }}">
                        @else
                            <img alt="{{ env('header_text') }}" src="{{ $logos . 'logo-dark.png' }}">
                        @endif
                    </div>
                </div>
                <div class="col-auto">
                    <div class="wow animate__fadeInRight mobile-widget" data-wow-delay="0.6s">
                        {{-- <img src="{{ Storage::url('logo/' . $logo) }}" alt="{{ env('header_text') }}" alt=""
                    class="navbar-logo" /> --}}
                        @if (isset($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on')
                            <img alt="{{ env('header_text') }}" src="{{ $logos . 'logo-light.png' }}">
                        @else
                            <img alt="{{ env('header_text') }}" src="{{ $logos . 'logo-dark.png' }}">
                        @endif
                    </div>
                </div>
                <div class="col-auto">
                    <div class="wow animate__fadeInRight mobile-widget" data-wow-delay="0.8s">
                        {{-- <img src="{{ Storage::url('logo/' . $logo) }}" alt="{{ env('header_text') }}" alt=""
                    class="navbar-logo" /> --}}
                        @if (isset($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on')
                            <img alt="{{ env('header_text') }}" src="{{ $logos . 'logo-light.png' }}">
                        @else
                            <img alt="{{ env('header_text') }}" src="{{ $logos . 'logo-dark.png' }}">
                        @endif
                    </div>
                </div>
                <div class="col-auto">
                    <div class="wow animate__fadeInRight mobile-widget" data-wow-delay="1s">
                        {{-- <img src="{{ Storage::url('logo/' . $logo) }}" alt="{{ env('header_text') }}" alt=""
                    class="navbar-logo" /> --}}
                        @if (isset($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on')
                            <img alt="{{ env('header_text') }}" src="{{ $logos . 'logo-light.png' }}">
                        @else
                            <img alt="{{ env('header_text') }}" src="{{ $logos . 'logo-dark.png' }}">
                        @endif
                    </div>
                </div>
            </div>
            <img src="{{ $landing.'dashboard.png' }}" alt="" class="img-fluid img-dashboard wow animate__fadeInUp mt-5" style="border-radius: 15px; visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;" data-wow-delay="0.2s"/>
        </div>
    </section>
    <!-- [ client ] End -->
    <!-- [ dashboard ] start -->
    <section  class="theme-alt-bg dashboard-block">
        <div class="container">
            <div class="row align-items-center justify-content-end mb-5">
                <div class="col-sm-4">
                    <h1 class="mb-sm-4 f-w-600 wow animate__fadeInLeft" data-wow-delay="0.2s">
                        {{ __('AnalyticsGo SaaS ') }}
                    </h1>
                    <h2 class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.4s">
                        {{__('Google Analytics with Multisite')}}
                    </h2>
                    <p class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.6s">
                        Use these awesome forms to login or create new account in your
                        project for free.
                    </p>
                    <div class="my-4 wow animate__fadeInLeft" data-wow-delay="0.8s">
                        <a href="#"
                            class="btn btn-primary" target="_blank"><i class="fas fa-shopping-cart me-2"></i>Buy
                            now</a>
                    </div>
                </div>
                <div class="col-sm-6">
                    <img src="{{ $landing.'img-crm-dash-1.svg'}}"
                        alt="Datta Able Admin Template" class="img-fluid header-img wow animate__fadeInRight"
                        data-wow-delay="0.2s" />
                </div>
            </div>
            <div class="row align-items-center justify-content-start">
                <div class="col-sm-6">
                    <img src="{{ asset('assets/images/front/img-crm-dash-2.svg') }}" alt="Datta Able Admin Template"
                        class="img-fluid header-img wow animate__fadeInLeft" data-wow-delay="0.2s" />
                </div>
                <div class="col-sm-4">
                    <h1 class="mb-sm-4 f-w-600 wow animate__fadeInRight" data-wow-delay="0.2s">
                        {{ __('AnalyticsGo SaaS ') }}
                    </h1>
                    <h2 class="mb-sm-4 wow animate__fadeInRight" data-wow-delay="0.4s">
                        {{__('Google Analytics with Multisite')}}
                    </h2>
                    <p class="mb-sm-4 wow animate__fadeInRight" data-wow-delay="0.6s">
                        Use these awesome forms to login or create new account in your
                        project for free.
                    </p>
                    <div class="my-4 wow animate__fadeInRight" data-wow-delay="0.8s">
                        <a href="#"
                            class="btn btn-primary" target="_blank"><i class="fas fa-shopping-cart me-2"></i>Buy
                            now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- [ dashboard ] End -->
    <!-- [ feature ] start -->
    <section id="features" class="feature">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-9 title">
                    <h2>
                        <span class="d-block mb-3">Features</span> All in one place CRM
                        system
                    </h2>
                    <p class="m-0">
                        Use these awesome forms to login or create new account in your
                        project for free.
                    </p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-3 col-md-6">
                    <div class="card wow animate__fadeInUp" data-wow-delay="0.2s"
                        style="
                visibility: visible;
                animation-delay: 0.2s;
                animation-name: fadeInUp;
              ">
                        <div class="card-body">
                            <div class="theme-avtar bg-primary">
                                <i class="ti ti-home"></i>
                            </div>
                            <h6 class="text-muted mt-4">ABOUT</h6>
                            <h4 class="my-3 f-w-600">Feature</h4>
                            <p class="mb-0">
                                Use these awesome forms to login or create new account in your
                                project for free.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card wow animate__fadeInUp" data-wow-delay="0.4s"
                        style="
                visibility: visible;
                animation-delay: 0.2s;
                animation-name: fadeInUp;
              ">
                        <div class="card-body">
                            <div class="theme-avtar bg-success">
                                <i class="ti ti-user-plus"></i>
                            </div>
                            <h6 class="text-muted mt-4">ABOUT</h6>
                            <h4 class="my-3 f-w-600">Feature</h4>
                            <p class="mb-0">
                                Use these awesome forms to login or create new account in your
                                project for free.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card wow animate__fadeInUp" data-wow-delay="0.6s"
                        style="visibility: visible;animation-delay: 0.2s;animation-name: fadeInUp;">
                        <div class="card-body">
                            <div class="theme-avtar bg-warning">
                                <i class="ti ti-users"></i>
                            </div>
                            <h6 class="text-muted mt-4">ABOUT</h6>
                            <h4 class="my-3 f-w-600">Feature</h4>
                            <p class="mb-0">
                                Use these awesome forms to login or create new account in your
                                project for free.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card wow animate__fadeInUp" data-wow-delay="0.8s"
                        style="
                visibility: visible;
                animation-delay: 0.2s;
                animation-name: fadeInUp;
              ">
                        <div class="card-body">
                            <div class="theme-avtar bg-danger">
                                <i class="ti ti-report-money"></i>
                            </div>
                            <h6 class="text-muted mt-4">ABOUT</h6>
                            <h4 class="my-3 f-w-600">Feature</h4>
                            <p class="mb-0">
                                {{__('Use these awesome forms to login or create new account in your project for free.')}} </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center pt-sm-5  feature-mobile-screen">
                <button class="btn px-sm-5 btn-primary me-sm-3">{{__('Buy Now')}}</button>
                <button class="btn px-sm-5 btn-outline-primary">
                   {{__(' View Documentation')}}
                </button>
            </div>
        </div>
    </section>
    <!-- [ feature ] End -->
    <!-- [ dashboard ] start -->
    <section class="">
        <div class="container">
            <div class="row align-items-center justify-content-end mb-5">
                <div class="col-sm-4">
                    <h1 class="mb-sm-4 f-w-600 wow animate__fadeInLeft" data-wow-delay="0.2s">
                        {{ __('AnalyticsGo SaaS ') }}
                    </h1>
                    <h2 class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.4s">
                        {{__('Google Analytics with Multisite')}}
                    </h2>
                    <p class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.6s">
                        Use these awesome forms to login or create new account in your
                        project for free.
                    </p>
                    <div class="my-4 wow animate__fadeInLeft" data-wow-delay="0.8s">
                        <a href="#s"
                            class="btn btn-primary" target="_blank"><i class="fas fa-shopping-cart me-2"></i>Buy
                            now</a>
                    </div>
                </div>
                <div class="col-sm-6">
                    <img src="{{ $landing.'dashboard.png'}}"
                        alt="Datta Able Admin Template" class="img-fluid header-img wow animate__fadeInRight"
                        data-wow-delay="0.2s" />
                </div>
            </div>
            <div class="row align-items-center justify-content-start">
                <div class="col-sm-6">
                    <img src="{{ asset('assets/images/front/img-crm-dash-4.svg')}}" alt="Datta Able Admin Template"
                        class="img-fluid header-img wow animate__fadeInLeft" data-wow-delay="0.2s" />
                </div>
                <div class="col-sm-4">
                    <h1 class="mb-sm-4 f-w-600 wow animate__fadeInRight" data-wow-delay="0.2s">
                        {{ __('AnalyticsGo SaaS ') }}
                    </h1>
                    <h2 class="mb-sm-4 wow animate__fadeInRight" data-wow-delay="0.4s">
                        {{__('Google Analytics with Multisite')}}
                    </h2>
                    <p class="mb-sm-4 wow animate__fadeInRight" data-wow-delay="0.6s">
                        Use these awesome forms to login or create new account in your
                        project for free.
                    </p>
                    <div class="my-4 wow animate__fadeInRight" data-wow-delay="0.8s">
                        <a href="#"
                            class="btn btn-primary" target="_blank"><i class="fas fa-shopping-cart me-2"></i>Buy
                            now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- [ dashboard ] End -->
    <!-- [ price ] start -->
    <section id="price" class="price-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-9 title">
                    <h2>
                        <span class="d-block mb-3">Price</span> All in one place CRM
                        system
                    </h2>
                    <p class="m-0">
                        Use these awesome forms to login or create new account in your
                        project for free.
                    </p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s"
                        style="
                visibility: visible;
                animation-delay: 0.2s;
                animation-name: fadeInUp;
              ">
                        <div class="card-body">
                            <span class="price-badge bg-primary">STARTER</span>
                            <span class="mb-4 f-w-600 p-price">$59<small class="text-sm">/month</small></span>
                            <p class="mb-0">
                                You have Free Unlimited Updates and <br />
                                Premium Support on each package.
                            </p>
                            <ul class="list-unstyled my-5">
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    2 team members
                                </li>
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    20GB Cloud storage
                                </li>
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    Integration help
                                </li>
                            </ul>
                            <div class="d-grid text-center">
                                <button
                                    class="btn mb-3 btn-primary d-flex justify-content-center align-items-center mx-sm-5">
                                    Start with Standard plan
                                    <i class="ti ti-chevron-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card price-card price-2 bg-primary wow animate__fadeInUp" data-wow-delay="0.4s"
                        style="
                visibility: visible;
                animation-delay: 0.2s;
                animation-name: fadeInUp;
              ">
                        <div class="card-body">
                            <span class="price-badge">STARTER</span>
                            <span class="mb-4 f-w-600 p-price">$59<small class="text-sm">/month</small></span>
                            <p class="mb-0">
                                You have Free Unlimited Updates and <br />
                                Premium Support on each package.
                            </p>
                            <ul class="list-unstyled my-5">
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    2 team members
                                </li>
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    20GB Cloud storage
                                </li>
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    Integration help
                                </li>
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    Sketch Files
                                </li>
                            </ul>
                            <div class="d-grid text-center">
                                <button
                                    class="btn mb-3 btn-light d-flex justify-content-center align-items-center mx-sm-5">
                                    Start with Standard plan
                                    <i class="ti ti-chevron-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card price-card price-3 wow animate__fadeInUp" data-wow-delay="0.6s"
                        style="
                visibility: visible;
                animation-delay: 0.2s;
                animation-name: fadeInUp;
              ">
                        <div class="card-body">
                            <span class="price-badge bg-primary">STARTER</span>
                            <span class="mb-4 f-w-600 p-price">$119<small class="text-sm">/month</small></span>
                            <p class="mb-0">
                                You have Free Unlimited Updates and <br />
                                Premium Support on each package.
                            </p>
                            <ul class="list-unstyled my-5">
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    2 team members
                                </li>
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    20GB Cloud storage
                                </li>
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    Integration help
                                </li>
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    2 team members
                                </li>
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    20GB Cloud storage
                                </li>
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    Integration help
                                </li>
                            </ul>
                            <div class="d-grid text-center">
                                <button
                                    class="btn mb-3 btn-primary d-flex justify-content-center align-items-center mx-sm-5">
                                    Start with Standard plan
                                    <i class="ti ti-chevron-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- [ price ] End -->
    <!-- [ faq ] start -->
    <section id="faq" class="faq">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-9 title">
                    <h2><span>Frequently Asked Questions </span></h2>
                    <p class="m-0">
                        Use these awesome forms to login or create new account in your
                        project for free.
                    </p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-sm-12 col-md-10 col-xxl-8">
                    <div class="accordion accordion-flush" id="accordionExample">
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-info-circle text-primary"></i> How do I
                                        order?
                                    </span>
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show"
                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <strong>This is the first item's accordion body.</strong> It
                                    is shown by default, until the collapse plugin adds the
                                    appropriate classes that we use to style each element. These
                                    classes control the overall appearance, as well as the
                                    showing and hiding via CSS transitions. You can modify any
                                    of this with custom CSS or overriding our default variables.
                                    It's also worth noting that just about any HTML can go
                                    within the <code>.accordion-body</code>, though the
                                    transition does limit overflow.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-info-circle text-primary"></i> How do I
                                        order?
                                    </span>
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <strong>This is the second item's accordion body.</strong>
                                    It is hidden by default, until the collapse plugin adds the
                                    appropriate classes that we use to style each element. These
                                    classes control the overall appearance, as well as the
                                    showing and hiding via CSS transitions. You can modify any
                                    of this with custom CSS or overriding our default variables.
                                    It's also worth noting that just about any HTML can go
                                    within the <code>.accordion-body</code>, though the
                                    transition does limit overflow.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="false"
                                    aria-controls="collapseThree">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-info-circle text-primary"></i> How do I
                                        order?
                                    </span>
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse"
                                aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <strong>This is the third item's accordion body.</strong> It
                                    is hidden by default, until the collapse plugin adds the
                                    appropriate classes that we use to style each element. These
                                    classes control the overall appearance, as well as the
                                    showing and hiding via CSS transitions. You can modify any
                                    of this with custom CSS or overriding our default variables.
                                    It's also worth noting that just about any HTML can go
                                    within the <code>.accordion-body</code>, though the
                                    transition does limit overflow.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- [ faq ] End -->
    <!-- [ dashboard ] start -->
    <section class="side-feature">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
                    <h1 class="mb-sm-4 f-w-600 wow animate__fadeInLeft" data-wow-delay="0.2s">
                        {{ __('AnalyticsGo SaaS ') }}
                    </h1>
                    <h2 class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.4s">
                        {{ __('Google Analytics with Multisite') }}
                    </h2>
                    <p class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.6s">
                        {{__('Use these awesome forms to login or create new account in your project for free.')}}
                    </p>
                    <div class="my-4 wow animate__fadeInLeft" data-wow-delay="0.8s">
                        <a href="#"
                            class="btn btn-primary" target="_blank"><i class="fas fa-shopping-cart me-2"></i>
                        {{__('Buy now')}}</a>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-6 col-md-12 col-sm-12">
                    <div class="row feature-img-row m-auto">
                        <div class="col-lg-3 col-sm-6">
                            <img src="{{ $landing.'dashboard.png' }}"
                                class="img-fluid header-img wow animate__fadeInRight" data-wow-delay="0.2s"
                                alt="Admin" />
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <img src="{{$landing.'dash-1.png'}}"
                                class="img-fluid header-img wow animate__fadeInRight" data-wow-delay="0.4s"
                                alt="Admin" />
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <img src="{{ $landing.'dash-2.png'}}"
                                class="img-fluid header-img wow animate__fadeInRight" data-wow-delay="0.6s"
                                alt="Admin" />
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <img src="{{ $landing.'dash-3.png'}}"
                                class="img-fluid header-img wow animate__fadeInRight" data-wow-delay="0.8s"
                                alt="Admin" />
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <img src="{{ $landing.'dash-4.png'}}"
                                class="img-fluid header-img wow animate__fadeInRight" data-wow-delay="0.3s"
                                alt="Admin" />
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <img src="{{ $landing.'dash-5.png'}}"
                                class="img-fluid header-img wow animate__fadeInRight" data-wow-delay="0.5s"
                                alt="Admin" />
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <img src="{{ $landing.'dash-6.png'}}"
                                class="img-fluid header-img wow animate__fadeInRight" data-wow-delay="0.7s"
                                alt="Admin" />
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <img src="{{ $landing.'dash-7.png'}}"
                                class="img-fluid header-img wow animate__fadeInRight" data-wow-delay="0.9s"
                                alt="Admin" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- [ dashboard ] End -->
    <!-- [ dashboard ] start -->
    <section class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    @if (isset($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on')
                        <img alt="{{ env('header_text') }}" src="{{ $logos . 'logo-light.png' }}">
                    @else
                        <img alt="{{ env('header_text') }}" src="{{ $logos . 'logo-dark.png' }}">
                    @endif
                </div>
                <div class="col-lg-6 col-sm-12 text-end">

                    <p class="text-body">{{ $footer_text }}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- [ dashboard ] End -->
    <!-- Required Js -->
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/wow.min.js') }}"></script>
    <script>
        // Start [ Menu hide/show on scroll ]
        let ost = 0;
        document.addEventListener("scroll", function() {
            let cOst = document.documentElement.scrollTop;
            if (cOst == 0) {
                document.querySelector(".navbar").classList.add("top-nav-collapse");
            } else if (cOst > ost) {
                document.querySelector(".navbar").classList.add("top-nav-collapse");
                document.querySelector(".navbar").classList.remove("default");
            } else {
                document.querySelector(".navbar").classList.add("default");
                document
                    .querySelector(".navbar")
                    .classList.remove("top-nav-collapse");
            }
            ost = cOst;
        });
        // End [ Menu hide/show on scroll ]
        var wow = new WOW({
            animateClass: "animate__animated", // animation css class (default is animated)
        });
        wow.init();
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: "#navbar-example",
        });
    </script>
</body>

</html>
