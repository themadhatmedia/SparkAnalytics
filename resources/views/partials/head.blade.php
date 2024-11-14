<?php

    $favicon = $setting['favicon'];

    if (\Auth::user()->user_type == 'super admin') {
        $company_favicon = $setting['favicon'];
    } else {
        $company_favicon = $setting['company-favicon'];
    }

    $title_text = $setting['title_text'];


    $meta_img = $setting['meta_image'];
    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';

    if(isset($setting['color_flag']) && $setting['color_flag'] == 'true')
    {

        $themeColor = 'custom-color';
    }
    else {
        $themeColor = $color;
    }
?>

<head>

    <title> @yield('page-title') -
        {{ $setting['header_text'] ? $setting['header_text'] : config('app.name', 'AnalyticsGo SaaS') }}
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <!-- Primary Meta Tags -->
    <meta name="title" content="{{ $setting['meta_keywords'] }}">
    <meta name="description" content="{{ $setting['meta_description'] }}">
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://demo.workdo.io/analyticsgo/">
    <meta property="og:title" content="{{ $setting['meta_keywords'] }}">
    <meta property="og:description" content="{{ $setting['meta_description'] }}">
    <meta property="og:image"
        content="{{ $logos . '/' . (isset($meta_img) && !empty($meta_img) ? $meta_img : 'meta-image.png') }}">
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://demo.workdo.io/analyticsgo/">
    <meta property="twitter:title" content="{{ $setting['meta_keywords'] }}">
    <meta property="twitter:description" content="{{ $setting['meta_description'] }}">
    <meta property="twitter:image"
        content="{{ $logos . '/' . (isset($meta_img) && !empty($meta_img) ? $meta_img : 'meta-image.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Favicon icon -->
    @if (Auth::user()->user_type == 'super admin')
        <link rel="icon" href="{{ $logos . '/favicon.png?' . time() }}" type="image/x-icon" />
    @else
        <link rel="icon"
            href="{{ $logos . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"type="image/x-icon" />
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/custom.css') }}">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.css"
        integrity="sha512-gp+RQIipEa1X7Sq1vYXnuOW96C4704yI1n0YB9T/KqdvqaEgL6nAuTSrKufUX3VBONq/TPuKiXGLVgBKicZ0KA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- font css -->
    <script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}">
    @stack('pre-purpose-css-page')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}" />
    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('css/bootstrap-rtl.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif
    @if (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}" id="main-style-link">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif
    <style>
        :root {
            --color-customColor: <?=$color ?>;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/custom/css/custom-color.css') }}">
    <!--bootstrap switch-->
    {{-- @endif --}}
    <style type="text/css">
        .big-logo {
            width: 160px;
            height: 40px;
        }

        .logo_card {
            min-height: 280px;
        }
    </style>
    <style type="text/css">
        .logo {
            height: 41px;
            width: 265px;
        }
    </style>
    <script src="{{ asset('js/admin.js?v=1234') }}"></script>

</head>
