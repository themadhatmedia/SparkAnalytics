@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Plan') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page">{{ __('Plan') }}</li>
@endsection
@section('content')
@if (count($plans) > 0)
<div class="col-12">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#monthly" role="tab"
            aria-controls="pills-home" aria-selected="true">{{ __('Monthly') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#annually" role="tab"
                    aria-controls="pills-profile" aria-selected="false">
                    {{ __('Annually') }}
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="monthly" class="tab-pane in active">
                <div class="row">
                    @foreach ($plans as $key => $plan)
                    <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6 main">
                        <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s"
                                    style="
                                    visibility: visible;
                                    animation-delay: 0.2s;
                                    animation-name: fadeInUp;
                                    ">
                                    <div class="card-body">
                                        <span class="price-badge bg-primary">{{ $plan->name }}</span>
                                        @if (\Auth::user()->user_type == 'company' && \Auth::user()->plan == $plan->id)
                                        <div class="d-flex flex-row-reverse m-0 p-0 active-tag">
                                                <span class=" align-items-right">
                                                    <i class="f-10 lh-1 fas fa-circle text-success"></i>
                                                    <span class="ms-2">{{ __('Active') }}</span>
                                                </span>
                                            </div>
                                        @endif
                                        <h1 class="mb-4 f-w-600  ">
                                            {{ isset($setting['currency_symbol']) ? $setting['currency_symbol'] : '$' }}{{ $plan->monthly_price }}<small
                                            class="text-sm">/{{ __('Month') }}</small></h1>
                                            <p class="mb-0">
                                                {{ __('Free Trial Days :') }}
                                            <b>{{ !empty($plan->trial_days) ? $plan->trial_days : 0 }}</b>
                                        </p>

                                        @if ($plan->description)
                                        <p class="mb-0">
                                                {{ $plan->description }}<br />
                                            </p>
                                        @endif
                                        <ul class="list-unstyled my-5">
                                            <li>
                                                <span class="theme-avtar">
                                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                                {{ $plan->trial_days < 0 ? __('Unlimited') : $plan->trial_days }}
                                                {{ __('Trial Days') }}
                                            </li>
                                            <li>
                                                <span class="theme-avtar">
                                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                                {{ $plan->max_site < 0 ? __('Unlimited') : $plan->max_site }}
                                                {{ __('Site') }}
                                            </li>
                                            <li>
                                                <span class="theme-avtar">
                                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                                {{ $plan->max_widget < 0 ? __('Unlimited') : $plan->max_widget }}
                                                {{ __('Widget Per Site') }}
                                            </li>
                                            <li>
                                                <span class="theme-avtar">
                                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                                {{ $plan->max_user < 0 ? __('Unlimited') : $plan->max_user }}
                                                {{ __('User') }}
                                            </li>
                                            <li>
                                                <span class="theme-avtar">
                                                    <i
                                                        class="{{ $plan->custom == 1 ? 'text-primary' : 'text-danger' }}  ti ti-circle-plus"></i></span>
                                                {{ $plan->custom == 1 ? __('Enable') : __('Disable') }}
                                                {{ __('Custom') }}
                                            </li>
                                            <li>
                                                <span class="theme-avtar">
                                                    <i
                                                        class="{{ $plan->analytics == 1 ? 'text-primary' : 'text-danger' }} ti ti-circle-plus"></i></span>
                                                {{ $plan->analytics == 1 ? __('Enable') : __('Disable') }}
                                                {{ __('Analytics') }}
                                            </li>
                                        </ul>
                                        <div class="d-flex justify-content-between">

                                            @can('buy plan')
                                                @if ($plan->id != \Auth::user()->plan && \Auth::user()->user_type != 'super admin')
                                                    @if ($plan->monthly_price > 0)
                                                        @if ($plan->monthly_price > 0 && \Auth::user()->trial_plan == 0 && \Auth::user()->plan != $plan->id && $plan->trial == 1)
                                                            <div class="col-auto">
                                                                <a href="{{ route('plans.trial', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                                    class="btn btn-lg btn-primary btn-icon m-1"
                                                                    data-title="{{ __('Start Free Trial') }}"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    data-bs-original-title="{{ __('Start Free Trial') }}"
                                                                    title="{{ __('Start Free Trial') }}">{{ __('Start Free Trial') }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <div class="col-auto">
                                                            <div class="d-grid text-center">
                                                                <a href="{{ route('payment', ['monthly', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}"
                                                                    class="btn btn-lg btn-primary btn-icon m-1"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    data-bs-original-title="{{ __('Subscribe') }}"
                                                                    title="{{ __('Subscribe') }}">{{ __('Subscribe') }}</a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endcan
                                            @if (\Auth::user()->user_type != 'Super Admin' && \Auth::user()->plan != $plan->id)
                                                @if ($plan->id != 1)
                                                    @if (\Auth::user()->requested_plan != $plan->id)
                                                        <div class="col-auto">
                                                            <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id), 'monthly']) }}"
                                                                class="btn btn-primary btn-lg btn-icon m-1"
                                                                data-title="{{ __('Send Request') }}"
                                                                data-toggle="tooltip">
                                                                <span class="btn-inner--icon"><i
                                                                        class="ti ti-arrow-forward-up"></i></span>
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="col-auto">
                                                            <a href="{{ route('request.cancel', \Auth::user()->id) }}"
                                                                class="btn btn-icon btn-lg m-1 btn-danger"
                                                                data-title="{{ __('Cancle Request') }}"
                                                                data-toggle="tooltip">
                                                                <span class="btn-inner--icon"><i
                                                                        class="ti ti-trash"></i></span>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>

                                        @if (\Auth::user()->user_type == 'company' && \Auth::user()->trial_expire_date)
                                            @if (\Auth::user()->user_type == 'company' && \Auth::user()->trial_plan == $plan->id)
                                                <p class="display-total-time text-dark mb-0">
                                                    {{ __('Plan Trial Expired : ') }}
                                                    {{ !empty(\Auth::user()->trial_expire_date) ? \Auth::user()->dateFormat(\Auth::user()->trial_expire_date) : 'lifetime' }}
                                                </p>
                                            @endif
                                        @else
                                            @if (\Auth::user()->user_type == 'company' && \Auth::user()->plan == $plan->id)
                                                <p class="display-total-time text-dark mb-0">
                                                    {{ __('Plan Expired : ') }}
                                                    {{ !empty(\Auth::user()->plan_expire_date) ? \Auth::user()->dateFormat(\Auth::user()->plan_expire_date) : 'lifetime' }}
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div id="annually" class="tab-pane">
                    <div class="row">
                        @foreach ($plans as $key => $plan)
                            {{-- <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6"> --}}
                            <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6 main">
                                <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s"
                                    style="
                                    visibility: visible;
                                    animation-delay: 0.2s;
                                    animation-name: fadeInUp;
                                  ">
                                    <div class="card-body">
                                        <span class="price-badge bg-primary">{{ $plan->name }}</span>
                                        @if (\Auth::user()->user_type == 'company' && \Auth::user()->plan == $plan->id)
                                            <div class="d-flex flex-row-reverse m-0 p-0 active-tag">
                                                <span class=" align-items-right">
                                                    <i class="f-10 lh-1 fas fa-circle text-success"></i>
                                                    <span class="ms-2">{{ __('Active') }}</span>
                                                </span>
                                            </div>
                                        @endif
                                        <h1 class="mb-4 f-w-600  ">
                                            {{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->annual_price }}<small
                                                class="text-sm">/{{ __('Annual') }}</small></h1>
                                        <p class="mb-0">
                                            {{ __('Free Trial Days :') }}
                                            <b>{{ !empty($plan->trial_days) ? $plan->trial_days : 0 }}</b>
                                        </p>
                                        @if ($plan->description)
                                            <p class="mb-0">
                                                {{ $plan->description }}<br />
                                            </p>
                                        @endif

                                        <ul class="list-unstyled my-5">
                                            <li>
                                                <span class="theme-avtar">
                                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                                {{ $plan->trial_days < 0 ? __('Unlimited') : $plan->trial_days }}
                                                {{ __('Trial Days') }}
                                            </li>
                                            <li>
                                                <span class="theme-avtar">
                                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                                {{ $plan->max_locations < 0 ? __('max_site') : $plan->max_site }}
                                                {{ __('Site') }}
                                            </li>
                                            <li>
                                                <span class="theme-avtar">
                                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                                {{ $plan->max_widget < 0 ? __('Unlimited') : $plan->max_widget }}
                                                {{ __('Widget Per Site') }}
                                            </li>
                                            <li>
                                                <span class="theme-avtar">
                                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                                {{ $plan->max_user < 0 ? __('Unlimited') : $plan->max_user }}
                                                {{ __('User') }}
                                            </li>
                                            <li>
                                                <span class="theme-avtar">
                                                    <i
                                                        class="{{ $plan->custom == 1 ? 'text-primary' : 'text-danger' }} ti ti-circle-plus"></i></span>
                                                {{ $plan->custom == 1 ? __('Enable') : __('Disable') }}
                                                {{ __('Custom') }}
                                            </li>
                                            <li>
                                                <span class="theme-avtar">
                                                    <i
                                                        class="{{ $plan->analytics == 1 ? 'text-primary' : 'text-danger' }} ti ti-circle-plus"></i></span>
                                                {{ $plan->analytics == 1 ? __('Enable') : __('Disable') }}
                                                {{ __('Analytics') }}
                                            </li>
                                        </ul>
                                        <div class="d-flex justify-content-between">

                                            @can('buy plan')
                                                @if ($plan->id != \Auth::user()->plan && \Auth::user()->user_type != 'super admin')
                                                    @if ($plan->annual_price > 0)
                                                        @if ($plan->annual_price > 0 && \Auth::user()->trial_plan == 0 && \Auth::user()->plan != $plan->id && $plan->trial == 1)
                                                            <div class="col-auto">
                                                                <a href="{{ route('plans.trial', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                                    class="btn btn-lg btn-primary btn-icon m-1"
                                                                    data-title="{{ __('Start Free Trial') }}"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    data-bs-original-title="{{ __('Start Free Trial') }}"
                                                                    title="{{ __('Start Free Trial') }}">{{ __('Start Free Trial') }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <div class="col-auto">
                                                            <div class="d-grid text-center">
                                                                <a href="{{ route('payment', ['annual', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}"
                                                                    class="btn btn-lg btn-primary btn-icon m-1"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    data-bs-original-title="{{ __('Subscribe') }}"
                                                                    title="{{ __('Subscribe') }}">{{ __('Subscribe') }}</a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endcan
                                            @if (\Auth::user()->user_type != 'Super Admin' && \Auth::user()->plan != $plan->id)
                                                @if ($plan->id != 1)
                                                    @if (\Auth::user()->requested_plan != $plan->id)
                                                        <div class="col-auto">
                                                            <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id), 'monthly']) }}"
                                                                class="btn btn-primary btn-lg btn-icon m-1"
                                                                data-title="{{ __('Send Request') }}"
                                                                data-toggle="tooltip">
                                                                <span class="btn-inner--icon"><i
                                                                        class="ti ti-arrow-forward-up"></i></span>
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="col-auto">
                                                            <a href="{{ route('request.cancel', \Auth::user()->id) }}"
                                                                class="btn btn-icon btn-lg m-1 btn-danger"
                                                                data-title="{{ __('Cancle Request') }}"
                                                                data-toggle="tooltip">
                                                                <span class="btn-inner--icon"><i
                                                                        class="ti ti-trash"></i></span>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        @if (\Auth::user()->user_type == 'company' && \Auth::user()->trial_expire_date)
                                            @if (\Auth::user()->user_type == 'company' && \Auth::user()->trial_plan == $plan->id)
                                                <p class="display-total-time text-dark mb-0">
                                                    {{ __('Plan Trial Expired : ') }}
                                                    {{ !empty(\Auth::user()->trial_expire_date) ? \Auth::user()->dateFormat(\Auth::user()->trial_expire_date) : 'lifetime' }}
                                                </p>
                                            @endif
                                        @else
                                            @if (\Auth::user()->user_type == 'company' && \Auth::user()->plan == $plan->id)
                                                <p class="display-total-time text-dark mb-0">
                                                    {{ __('Plan Expired : ') }}
                                                    {{ !empty(\Auth::user()->plan_expire_date) ? \Auth::user()->dateFormat(\Auth::user()->plan_expire_date) : 'lifetime' }}
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        </div>
    @else
        <div class="col-md-12" style="height: 200px; ">
            <div class="alert alert-primary alert-dismissible fade show text-center" role="alert">
                {{ __('No Data Found !') }}

            </div>
        </div>
    @endif
@endsection
