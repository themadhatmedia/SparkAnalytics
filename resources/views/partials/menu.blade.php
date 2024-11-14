<?php
if (\Auth::user()->user_type == 'super admin') {
    $light_logo = $setting['light_logo'];
    $dark_logo = $setting['dark_logo'];
} elseif (\Auth::user()->user_type == 'company') {
    $dark_logo = $setting['company_dark_logo'];
    $light_logo = $setting['company_light_logo'];
    $company_favicon = $setting['company_favicon'];
} else {
}
$logos = \App\Models\Utility::get_file('logo');
$profile = asset(Storage::url('uploads/avatar/'));
$plan = \App\Models\Plan::where('id', Auth::user()->plan)->first();

?>

<!-- [ navigation menu ] start -->
@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <nav class="dash-sidebar light-sidebar transprent-bg">
    @else
        <nav class="dash-sidebar light-sidebar">
@endif
{{-- <nav class="dash-sidebar light-sidebar {{($setting['is_sidebar_transperent'] == 'off' &&  $setting['is_sidebar_transperent'] != 'on') ? '' : 'transprent-bg'}}"> --}}
<div class="navbar-wrapper">
    <div class="m-header main-logo">
        <a href="#" class="b-brand">
            <!-- ========   change your logo hear   ======= -->
            @if (\Auth::user()->user_type == 'super admin')
                @if (!empty($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
                    <img loading="lazy"
                        src="{{ $logos . '/' . (isset($light_logo) && !empty($light_logo) ? $light_logo : 'logo-light.png') . '?' . time() }}"
                        alt="{{ config('app.name', 'AnalyticsGo SaaS') }}" class="logo logo-lg">
                @else
                    <img loading="lazy"
                        src="{{ $logos . '/' . (isset($setting['dark_logo']) && !empty($setting['dark_logo']) ? $setting['dark_logo'] : 'logo-dark.png') . '?' . time() }}"
                        alt="{{ config('app.name', 'AnalyticsGo SaaS') }}" class="logo logo-lg">
                @endif
            @elseif (\Auth::user()->user_type == 'company')
                @if (!empty($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
                    <img loading="lazy"
                        src="{{ $logos . '/' . (isset($light_logo) && !empty($light_logo) ? $light_logo : 'logo-light.png') }}"
                        width="170px" alt="{{ config('app.name', 'AnalyticsGo SaaS') }}" class="logo logo-lg">
                @else
                    <img loading="lazy"
                        src="{{ $logos . '/' . (isset($dark_logo) && !empty($dark_logo) ? $dark_logo : 'logo-dark.png') }}"
                        width="170px" alt="{{ config('app.name', 'AnalyticsGo SaaS') }}" class="logo logo-lg">
                @endif
            @else
                <img loading="lazy"
                    src="{{ $logos . '' . (isset($company_dark_logo) && !empty($company_dark_logo) ? $company_dark_logo : 'logo-dark.png') }}"
                    alt="{{ config('app.name', 'AnalyticsGo SaaS') }}" class="logo logo-lg">
            @endif
        </a>
    </div>
    <div class="navbar-content">
        <ul class="dash-navbar">
            <li class="dash-item  dash-hasmenu">
                <a href="{{ route('dashboard') }}"
                    class="dash-link {{ Request::route()->getName() == 'dashboard' ? ' active' : '' }}">
                    <span class="dash-micon">
                        <i class="ti ti-home"></i>
                    </span>
                    <span class="dash-mtext">{{ __('Dashboard') }}</span>
                </a>
            </li>
            @if (\Auth::user()->user_type == 'super admin' && \Auth::user()->can('manage user'))
                <li class="dash-item  dash-hasmenu">
                    <a href="{{ route('users') }}"
                        class="dash-link {{ Request::route()->getName() == 'users' ? ' active' : '' }}">
                        <span class="dash-micon">
                            <i class="ti ti-user"></i>
                        </span>
                        @if (\Auth::user()->user_type == 'super admin')
                            <span class="dash-mtext">{{ __('Companies') }}</span>
                        @else
                            <span class="dash-mtext">{{ __('Users') }}</span>
                        @endif
                    </a>
                </li>
            @endif
            @if (\Auth::user()->user_type != 'super admin')
                @if (\Auth::user()->can('manage user'))
                    <li class="dash-item  dash-hasmenu">
                        <a href="{{ route('users') }}"
                            class="dash-link {{ Request::route()->getName() == 'users' ? ' active' : '' }}">
                            <span class="dash-micon">
                                <i class="ti ti-user"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Users') }}</span>
                        </a>
                    </li>
                @endif
                @if (\Auth::user()->can('manage role'))
                    <li class="dash-item  dash-hasmenu">
                        <a href="{{ route('roles.index') }}"
                            class="dash-link {{ Request::route()->getName() == 'roles' ? ' active' : '' }}">
                            <span class="dash-micon">
                                <i class="ti ti-share"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Role') }}</span>
                        </a>
                    </li>
                @endif
                @if (\Auth::user()->can('show quick view'))
                    <li class="dash-item  dash-hasmenu">
                        <a href="{{ url('quick-view/0') }}"
                            class="dash-link {{ Request::route()->getName() == 'quick-view' ? ' active' : '' }}">
                            <span class="dash-micon">
                                <i class="ti ti-layers-difference"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Quick View') }}</span>
                        </a>
                    </li>
                @endif
                @if (\Auth::user()->can('manage widget'))
                    <li class="dash-item  dash-hasmenu">
                        <a href="{{ route('widget') }}"
                            class="dash-link {{ Request::route()->getName() == 'widget' ? ' active' : '' }}">
                            <span class="dash-micon">
                                <i class="ti ti-layout-2"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Widget') }}</span>
                        </a>
                    </li>
                @endif
                @if (\Auth::user()->user_type != 'super admin')
                    <li class="dash-item  dash-hasmenu">
                        <a href="{{ url('site-standard/0') }}"
                            class="dash-link {{ Request::route()->getName() == 'site-standard' ? ' active' : '' }}">
                            <span class="dash-micon">
                                <i data-feather="layers"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Standard') }}</span>
                        </a>
                    </li>
                @endif
                @if ($plan->analytics == 1)
                    <li class="dash-item dash-hasmenu">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-box"></i></span><span
                                class="dash-mtext">{{ __('Analytics') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @if (\Auth::user()->can('show channel analytic'))
                                <li class="dash-item">
                                    <a href="{{ route('channel') }}"
                                        class="dash-link {{ Request::route()->getName() == 'channel' ? ' active' : '' }}">{{ __('Channel') }}</a>
                                </li>
                            @endif
                            @if (\Auth::user()->can('show audience analytic'))
                                <li class="dash-item">
                                    <a href="{{ route('audience') }}"
                                        class="dash-link {{ Request::route()->getName() == 'audience' ? ' active' : '' }}">{{ __('Audience') }}</a>
                                </li>
                            @endif
                            @if (\Auth::user()->can('show pages analytic'))
                                <li class="dash-item">
                                    <a href="{{ route('page') }}"
                                        class="dash-link {{ Request::route()->getName() == 'page' ? ' active' : '' }}">{{ __('Pages') }}</a>
                                </li>
                            @endif
                            @if (\Auth::user()->can('show seo analytic'))
                                <li class="dash-item">
                                    <a href="{{ route('seo-analysis') }}"
                                        class="dash-link {{ Request::route()->getName() == 'seo-analysis' ? ' active' : '' }}">{{ __('SEO') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (\Auth::user()->can('show custom analytic') && $plan->custom == 1)
                    <li class="dash-item  dash-hasmenu">
                        <a href="{{ url('custom-dashboard') }}"
                            class="dash-link {{ Request::route()->getName() == 'custom-dashboard' ? ' active' : '' }}">
                            <span class="dash-micon">
                                <i data-feather="layers"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Custom') }}</span>
                        </a>
                    </li>
                @endif
                @if (\Auth::user()->user_type != 'super admin')
                    <li class="dash-item dash-hasmenu">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-box"></i></span><span
                                class="dash-mtext">{{ __('Alerts') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">

                            <li class="dash-item">
                                <a href="{{ route('aletr') }}"
                                    class="dash-link {{ Request::route()->getName() == 'aletr' ? ' active' : '' }}">{{ __('Alerts') }}</a>
                            </li>

                            <li class="dash-item">
                                <a href="{{ route('aletr-history') }}"
                                    class="dash-link {{ Request::route()->getName() == 'aletr-history' ? ' active' : '' }}">{{ __('History') }}</a>
                            </li>


                        </ul>
                    </li>
                    <li class="dash-item  dash-hasmenu">
                        <a href="{{ url('report/history') }}"
                            class="dash-link {{ Request::route()->getName() == 'report/history' ? ' active' : '' }}">
                            <span class="dash-micon">
                                <i class="ti ti-file-invoice"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Report') }}</span>
                        </a>
                    </li>
                @endif
            @endif
            @if (\Auth::user()->user_type == 'company' || \Auth::user()->user_type == 'super admin')
                <li class="dash-item  dash-hasmenu">
                    <a href="{{ route('plans') }}"
                        class="dash-link {{ Request::route()->getName() == 'plans' ? ' active' : '' }}">
                        <span class="dash-micon">
                            <i class="ti ti-trophy"></i>
                        </span>
                        <span class="dash-mtext">{{ __('Plan') }}</span>
                    </a>
                </li>
            @endif
            @if (\Auth::user()->user_type == 'company' || \Auth::user()->user_type == 'super admin')
                <li class="dash-item  dash-hasmenu">
                    <a href="{{ route('referral') }}"
                        class="dash-link {{ Request::route()->getName() == 'referral' ? ' active' : '' }}">
                        <span class="dash-micon">
                            <i class="ti ti-discount-2"></i>
                        </span>
                        <span class="dash-mtext">{{ __('Referral Program') }}</span>
                    </a>
                </li>
            @endif
            @if (\Auth::user()->user_type == 'company')
                <li class="dash-item  dash-hasmenu">
                    <a href="{{ route('company.settings') }}"
                        class="dash-link {{ Request::route()->getName() == 'company.settings' ? ' active' : '' }}">
                        <span class="dash-micon">
                            <i class="ti ti-settings"></i>
                        </span>
                        <span class="dash-mtext">{{ __('Settings') }}</span>
                    </a>
                </li>
            @endif
            @if (\Auth::user()->user_type == 'super admin')
                <li class="dash-item  dash-hasmenu">
                    <a href="{{ route('plans-request') }}"
                        class="dash-link {{ Request::route()->getName() == 'plans-request' ? ' active' : '' }}">
                        <span class="dash-micon">
                            <i class="ti ti-brand-telegram"></i>
                        </span>
                        <span class="dash-mtext">{{ __('Request Plan') }}</span>
                    </a>
                </li>
                <li class="dash-item  dash-hasmenu">
                    <a href="{{ route('coupon') }}"
                        class="dash-link {{ Request::route()->getName() == 'coupon' ? ' active' : '' }}">
                        <span class="dash-micon">
                            <i class="ti ti-gift"></i>
                        </span>
                        <span class="dash-mtext">{{ __('Coupon') }}</span>
                    </a>
                </li>
                <li class="dash-item {{ Request::route()->getName() == 'order.index' ? ' active' : '' }}">
                    <a href="{{ route('order.index') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-credit-card"></i></span><span
                            class="dash-mtext">{{ __('Orders') }}</span></a>
                </li>

                @if (\Auth::user()->user_type == 'super admin')
                    @include('landingpage::menu.landingpage')
                @endif

                <li
                    class="dash-item  dash-hasmenu {{ Request::route()->getName() == 'settings.index' ? ' active' : '' }}">
                    <a href="{{ route('settings.index') }}" class="dash-link">
                        <span class="dash-micon">
                            <i class="ti ti-settings"></i>
                        </span>
                        <span class="dash-mtext">{{ __('Settings') }}</span>
                    </a>
                </li>
            @endif


        </ul>
    </div>
</div>
</nav>
<!-- [ navigation menu ] end -->
