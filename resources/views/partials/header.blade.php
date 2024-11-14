@php
$avatar = \App\Models\Utility::get_file('avatars/');
$SITE_RTL= 'off';
@endphp
@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on' || $SITE_RTL =='on')
    <header class="dash-header transprent-bg">
@else
    <header class="dash-header">
@endif
    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            <ul class="list-unstyled">
                <li class="dash-h-item mob-hamburger">
                    <a href="#!" class="dash-head-link" id="mobile-collapse">
                        <div class="hamburger hamburger--arrowturn">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="dropdown dash-h-item drp-company">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">

                        <img class="theme-avtar" loading="lazy"
                        src="{{ !empty(\Auth::user()->avatar) ? $avatar . \Auth::user()->avatar : $avatar.'/avatar.png' }}">                        <span class="hide-mob ms-2">{{ $users->name }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">
                        <a href="{{ route('my.account') }}" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span>{{ __('Profile') }}</span>
                        </a>
                        <a href="{{ route('logout') }}" class="dropdown-item">
                          <i class="ti ti-power"></i>
                          <span>{{__('Logout')}}</span>
                        </a>
                    </div>
                </li>

            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">
               @impersonating($guard = null)
                    <li class="dropdown dash-h-item drp-company">
                            <a class="btn btn-danger btn-sm me-3"
                                href="{{ route('exit.admin') }}"><i class="ti ti-ban"></i>
                                {{ __('Exit Company Login') }}
                            </a>
                    </li>
				@endImpersonating
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-world nocolor"></i>
                        @foreach ($languages as $code => $lang)
                        <span class="drp-text hide-mob" style="margin-left:2px;">{{ $currantLang == $code  ? Str::ucfirst($lang) : '' }}</span>
                        @endforeach
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                        
                        @if (\Auth::user()->user_type == 'super admin' || \Auth::user()->user_type == 'company' || Auth::user()->user_type != '')
                            @foreach ($languages as $code => $lang)
                                <a href="{{ route('change_lang_admin', $code) }}"
                                    class="dropdown-item {{ $currantLang == $code ? 'text-danger' : '' }}">
                                    <span class="small">{{ Str::ucfirst($lang) }}</span>
                                </a>
                            @endforeach

                        @endif

                        @if (\Auth::user()->user_type == 'super admin')
                        <a href="#" class="dropdown-item text-primary" data-size="md" data-url="{{ route('create.language') }}" data-bs-toggle="modal" data-bs-target="#exampleoverModal"
                        data-bs-whatever="{{__('Create New Language')}}" data-bs-toggle="tooltip" title="{{ __('Create Language') }}"
                        data-bs-original-title="{{__('Create New Language')}}">
                            <span> {{ __('Create Language') }}</span>
                        </a>
                        @endif

                        @if (\Auth::user()->user_type == 'super admin')
                            <a href="{{ route('manage.language', [$currantLang]) }}"
                                class="dropdown-item text-primary">
                                <span> {{ __('Manage Language') }}</span>
                            </a>
                        @endif

                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
