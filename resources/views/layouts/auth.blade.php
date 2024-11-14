@php
    $setting = App\Models\Utility::settings();
    $users = \Auth::user();
    $logos = \App\Models\Utility::get_file('logo/');
    $temp_lang = \App::getLocale('lang');
    if ($temp_lang == 'ar' || $temp_lang == 'he') {
        $SITE_RTL = 'on';
    } else {
        $SITE_RTL = App\Models\Utility::getValByName('SITE_RTL');
    }
    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';
    if (isset($setting['color_flag']) && $setting['color_flag'] == 'true') {
        $themeColor = 'custom-color';
    } else {
        $themeColor = $color;
    }
    $meta_img = $setting['meta_image'];
    $meta_setting = App\Models\Utility::settings();
    $year = date('Y');
    $cust_darklayout = $setting['cust_darklayout'];
    $footer_text = isset($setting['footer_text']) ? $setting['footer_text'] : ' AnalyticsGo SaaS';
@endphp

<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">

<head>
    <title>
        {{ $setting['header_text'] ? $setting['header_text'] : config('app.name', 'AnalyticsGo SaaS') }}
        - @yield('page-title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <!-- Primary Meta Tags -->

    <meta name="title" content="{{ $meta_setting['meta_keywords'] }}">
    <meta name="description" content="{{ $meta_setting['meta_description'] }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://demo.workdo.io/analyticsgo/">
    <meta property="og:title" content="{{ $meta_setting['meta_keywords'] }}">
    <meta property="og:description" content="{{ $meta_setting['meta_description'] }}">
    <meta property="og:image"
        content="{{ $logos . '/' . (isset($meta_img) && !empty($meta_img) ? $meta_img : 'meta-image.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://demo.workdo.io/analyticsgo/">
    <meta property="twitter:title" content="{{ $meta_setting['meta_keywords'] }}">
    <meta property="twitter:description" content="{{ $meta_setting['meta_description'] }}">
    <meta property="twitter:image"
        content="{{ $logos . '/' . (isset($meta_img) && !empty($meta_img) ? $meta_img : 'meta-image.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Favicon icon -->
    <link rel="icon"
        href="{{ $logos . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
        type="image/x-icon" />


    @if ($setting['cust_darklayout'] == 'on')
        @if (isset($SITE_RTL) && $SITE_RTL == 'on')
            <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
        @endif
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        @if (isset($SITE_RTL) && $SITE_RTL == 'on')
            <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
        @else
            <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
        @endif
    @endif

    @if (isset($SITE_RTL) && $SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('assets/custom/css/custom-auth-rtl.css') }}" id="main-style-link">
    @else
        <link rel="stylesheet" href="{{ asset('assets/custom/css/custom-auth.css') }}" id="main-style-link">
    @endif
    @if ($setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/custom/css/custom-auth-dark.css') }}" id="main-style-link">
    @endif
    <style type="text/css">
        .logo {
            height: 41px;
            width: 265px;
        }

        .grecaptcha-badge {
            z-index: 2;
        }

        .border-grey {
            border: 1px solid #CBCBCB !important;
        }

        .upgrade-line hr {
            flex: 1;
        }
    </style>
    <style>
        :root {
            --color-customColor: <?=$color ?>;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/custom/css/custom-color.css') }}">
</head>

<body class="{{ $themeColor }}">
    <!-- [custom-login] start -->
    <div class="custom-login">
        <div class="login-bg-img">
            @if (strpos($themeColor, 'theme') === 0)
                <img src="{{ asset('assets/images/auth/' . $themeColor . '.svg') }}" class="login-bg-1">
            @else
                <img src="{{ asset('assets/images/auth/theme-3.svg') }}" class="login-bg-1">
            @endif
            <img src="{{ asset('assets/images/auth/common.svg') }}" class="login-bg-2">
        </div>
        <div class="bg-login bg-primary"></div>
        <div class="custom-login-inner">
            <header class="dash-header">
                <nav class="navbar navbar-expand-md default">
                    <div class="container-fluid pe-2">
                        <a class="navbar-brand" href="#">
                            @if (isset($cust_darklayout) && $cust_darklayout == 'on')
                                <img alt="{{ config('app.name', 'AnalyticsGo SaaS') }}"
                                    src="{{ $logos . 'logo-light.png' . '?' . time() }}" alt="logo" class="logo"
                                    loading="lazy">
                            @else
                                <img alt="{{ config('app.name', 'AnalyticsGo SaaS') }}"
                                    src="{{ $logos . 'logo-dark.png' . '?' . time() }}" alt="logo" class="logo"
                                    loading="lazy">
                            @endif
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarlogin">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarlogin">
                            <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    @include('landingpage::layouts.buttons')
                                </li>
                                <div class="lang-dropdown-only-desk">
                                    @yield('lang-selectbox')
                                </div>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <main class="custom-wrapper">
                <div class="custom-row">
                    <div class="card">
                        @yield('content')
                    </div>
                </div>
            </main>
            <footer>
                <div class="auth-footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <span>{{ '© ' . $year }} {{ $footer_text }} </span>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    @stack('custom-scripts')


    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom/js/vendor-all.js') }}"></script>
    <script src="{{ asset('assets/custom/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/custom/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>

    {{-- @if (App\Models\Utility::getValByName('recaptcha_module') == 'on')
        @if (isset($setting['google_recaptcha_version']) && $setting['google_recaptcha_version'] == 'v2')
            {!! NoCaptcha::renderJs() !!}
        @elseif(isset($setting['google_recaptcha_version']) && $setting['google_recaptcha_version'] == 'v3')
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
    @endif --}}

    <script>
        feather.replace();
    </script>

    <script>
        feather.replace();
        var pctoggle = document.querySelector("#pct-toggler");
        if (pctoggle) {
            pctoggle.addEventListener("click", function() {
                if (
                    !document.querySelector(".pct-customizer").classList.contains("active")
                ) {
                    document.querySelector(".pct-customizer").classList.add("active");
                } else {
                    document.querySelector(".pct-customizer").classList.remove("active");
                }
            });
        }

        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];

            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }




        function removeClassByPrefix(node, prefix) {
            for (let i = 0; i < node.classList.length; i++) {
                let value = node.classList[i];
                if (value.startsWith(prefix)) {
                    node.classList.remove(value);
                }
            }
        }
    </script>
</body>
@if (isset($setting['enable_cookie']) && $setting['enable_cookie'] == 'on')
    @include('layouts.cookie_consent')
@endif

</html>
