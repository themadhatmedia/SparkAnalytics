@extends('layouts.admin')
<?php
$settings = \App\Models\Utility::settings(1);
$currency = isset($settings['currency_symbol']) ? $settings['currency_symbol'] : '$';
?>
@push('scripts')
@endpush
@section('page-title')
    {{ __('Referral') }}
@endsection
@section('title')
    {{ __('Referral') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Referral') }}</li>
@endsection

@push('pre-purpose-css-page')
    <link rel="stylesheet"
        href="{{ asset('Modules/LandingPage/Resources/assets/js/plugins/summernote/summernote-bs4.css') }}">
@endpush

@push('script-page')
    <script src="{{ asset('Modules/LandingPage/Resources/assets/js/plugins/summernote/summernote-bs4.js') }}"></script>
    <script type="text/javascript">
        summernote()
    </script>
@endpush



@section('content')
    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>
    @if (\Auth::user()->user_type == 'super admin')
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="card sticky-top" style="top:30px">
                            <div class="list-group list-group-flush" id="useradd-sidenav">
                                <a href="#transaction" data-target="transaction"
                                    class="list-group-item list-group-item-action border-0 menu-btn active">
                                    {{ __('Transaction') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                                <a href="#payout_req" data-target="payout_req"
                                    class="list-group-item list-group-item-action border-0 menu-btn">
                                    {{ __('Payout Request') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                                <a href="#setting" data-target="setting"
                                    class="list-group-item list-group-item-action border-0 menu-btn">
                                    {{ __('Settings') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-9">
                        {{-- Transaction --}}
                        <div class="card menu-section" id="transaction">
                            {{ Form::open(['route' => 'settings.store', 'method' => 'post']) }}
                            <div class="card-header">
                                <h5>{{ __('Transaction') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>{{ __('#') }}</th>
                                                <th>{{ __('COMPANY NAME') }}</th>
                                                <th>{{ __('REFERRAL COMPANY NAME') }}</th>
                                                <th>{{ __('PLAN NAME') }}</th>
                                                <th>{{ __('PLAN PRICE') }}</th>
                                                <th>{{ __('COMMISSION(%)') }}</th>
                                                <th>{{ __('COMMISSION AMOUNT') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (is_array($transactions) || is_object($transactions))
                                                @foreach ($transactions as $transaction)
                                                    <!-- Changed variable here -->
                                                    <tr class="text-center">
                                                        <td>{{ $transaction->id }}</td>
                                                        <td>{{ $transaction->company_name }}</td>
                                                        <td>{{ $transaction->RefcompanyName->name ?? '' }}</td>
                                                        <td>{{ $transaction->plan_name }}</td>
                                                        <td>{{ $transaction->plan_price }}</td>
                                                        <td>{{ $transaction->plan_commission_rate }}</td>
                                                        <td>{{ $transaction->commission }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>

                        {{-- Payout_req --}}
                        <div class="card menu-section d-none" id="payout_req">
                            <div class="card-header">
                                <h5>{{ __('Payout Request') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table text-center">
                                        <thead>
                                            <tr>
                                                <th>{{ __('#') }}</th>
                                                <th>{{ __('COMPANY NAME') }}</th>
                                                <th>{{ __('REQUESTED DATE') }}</th>
                                                <th>{{ __('REQUESTED AMOUNT') }}</th>
                                                <th>{{ __('ACTION') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($payouts as $payout)
                                                <!-- Changed variable here -->
                                                <tr>
                                                    <td>{{ $payout->id }}</td>
                                                    <td>{{ $payout->companyName->name }}</td>
                                                    <td>{{ $payout->date }}</td>
                                                    <td>{{ $payout->amount }}</td>
                                                    <td class="text-right">
                                                        <div class="actions ml-3">
                                                            <form action="{{ route('referral_store.status') }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $payout->id }}">
                                                                <button type="submit" class="btn btn-success btn-sm"
                                                                    name="status" value="accept"><i
                                                                        class="ti ti-check"></i></button>
                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                    name="status" value="reject"><i
                                                                        class="ti ti-x"></i></button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Settings --}}
                        <div class="card menu-section d-none" id="setting">
                            {{ Form::open(['route' => 'referral.settings', 'method' => 'post', 'id' => 'settings_form']) }}
                            <div class="card-header">
                                <h5>{{ __('Settings') }}</h5>
                            </div>
                            <div class="card-body" id="targetDiv">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="plan_commission_rate"
                                                class="form-label">{{ __('Commission Percentage (%)') }}</label>
                                            <input class="form-control" placeholder="Enter Percentage"
                                                name="plan_commission_rate" type="text"
                                                value="{{ $settings['plan_commission_rate'] ?? '' }}"
                                                id="plan_commission_rate" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="threshold_amount"
                                                class="form-label">{{ __('Minimum Threshold Amount') }}</label>
                                            <input class="form-control" placeholder="Enter Threshold Amount"
                                                name="threshold_amount" type="text"
                                                value="{{ $settings['threshold_amount'] ?? '' }}" id="threshold_amount"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mt-2">
                                        <label for="guidelines" class="form-label">{{ __('Guidelines') }}</label>
                                        {{ Form::textarea('guidelines', $settings['guidelines'] ?? '', ['class' => 'summernote-simple form-control', 'placeholder' => __('Enter Long Description'), 'id' => 'guidelines']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <input class="btn btn-primary" type="submit" value="{{ __('Save Changes') }}">
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif

    @if (\Auth::user()->user_type == 'company')
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="card sticky-top" style="top:30px">
                            <div class="list-group list-group-flush" id="useradd-sidenav">

                                <a href="#guideline" data-target="guideline"
                                    class="list-group-item list-group-item-action border-0 menu-btn active">{{ __('GuideLine') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>

                                <a href="#ref_transaction" data-target="ref_transaction"
                                    class="list-group-item list-group-item-action border-0 menu-btn">{{ __('Referral Transaction') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>

                                <a href="#payout" data-target="payout"
                                    class="list-group-item list-group-item-action border-0 menu-btn">{{ __('Payout') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-9">
                        {{--  Guideline --}}
                        <div class="card menu-section" id="guideline">
                            {{ Form::open(['route' => 'settings.store', 'method' => 'post']) }}
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5>{{ __('GuideLine') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6 col-md-6 ">
                                        <div class="form-group p-3 border">
                                            <h4>{{ __('Refer' . ' ' . Auth::user()->name . ' ' . 'and earn ') . $currency . $settings['threshold_amount'] . __(' per paid signup!') }}
                                            </h4>
                                            {!! isset($settings) ? $settings['guidelines'] : '' !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6 col-md-6 ">
                                        <div id="setting">
                                            <h4 class="text-center">{{ __('Share Your Link') }}</h4>
                                            <div class="d-flex justify-content-between">
                                                <a href="#" class="btn btn-sm btn-light-primary w-100 cp_link"
                                                    data-link="{{ route('register', ['ref_id' => \Auth::user()->referrance_code]) }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                    data-bs-original-title="Click to copy business link">
                                                    {{ route('register', ['ref' => \Auth::user()->referrance_code]) }}
                                                    <i class="ti ti-copy"></i>
                                                </a>
                                            </div>
                                            @if (isset($settings) && $settings['referral_enable'] == 'off')
                                                <h6 class="text-danger pt-2 text-end">
                                                    {{ __('Note: Super admin has disabled the referral program.') }}</h6>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>



                        {{-- Ref Transaction --}}
                        <div class="card menu-section d-none" id="ref_transaction">
                            {{ Form::open(['route' => 'settings.store', 'method' => 'post']) }}
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5>{{ __('Referral Transaction') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table text-center">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('#') }}</th>
                                                    <th>{{ __('COMPANY NAME') }}</th>
                                                    <th>{{ __('PLAN NAME') }}</th>
                                                    <th>{{ __('PLAN PRICE') }}</th>
                                                    <th>{{ __('COMMISSION(%)') }}</th>
                                                    <th>{{ __('COMMISSION AMOUNT') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (is_array($transactions) || is_object($transactions))
                                                    @foreach ($transactions as $transactions)
                                                        <tr>
                                                            <td>{{ $transactions->id }}</td>
                                                            <td>{{ $transactions->company_name }}</td>
                                                            <td>{{ $transactions->plan_name }}</td>
                                                            <td>{{ $transactions->plan_price }}</td>
                                                            <td>{{ $transactions->plan_commission_rate }}</td>
                                                            <td>{{ $transactions->commission }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>




                        {{-- payout --}}
                        <div class="menu-section d-none" id="payout">
                            <div class="card">
                                {{ Form::open(['route' => 'payout.store', 'method' => 'post']) }}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <h5>{{ __('Payout') }}</h5>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                            <a href="#" class="btn btn-sm btn-primary btn-icon m-1"
                                                data-bs-toggle="modal"data-bs-target="#bonus" data-size="lg"
                                                data-bs-whatever="Create New User">
                                                <span class="text-white">
                                                    <i class="ti ti-arrow-forward-up text-end" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Create User') }}"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="d-flex border p-3">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti ti-report-money"></i>
                                                </div>
                                                <div style="margin-left: 3%">
                                                    <small>{{ __('Total') }}</small>
                                                    <h5>{{ __('Commission Amount') }}</h5>
                                                </div>
                                                <h4 class="pt-3" style="margin-left: auto">${{ $commission ?? '' }}
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="d-flex border p-3">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti ti-report-money"></i>
                                                </div>
                                                <div style="margin-left: 3%">
                                                    <small>{{ __('Paid') }}</small>
                                                    <h5>{{ __('Commission Amount') }}</h5>
                                                </div>
                                                <h4 class="pt-3" style="margin-left: auto">$
                                                    {{ $totalpaidCommission ?? '' }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                            <div class="card">
                                {{ Form::open(['route' => 'settings.store', 'method' => 'post']) }}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <h5>{{ __('Payout History') }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table text-center">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('#') }}</th>
                                                        <th>{{ __('COMPANY NAME') }}</th>
                                                        <th>{{ __('REQUESTED DATE') }}</th>
                                                        <th>{{ __('STATUS') }}</th>
                                                        <th>{{ __('REQUESTED AMOUNT') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($paidCommission as $transaction)
                                                        <tr>
                                                            <td>{{ $transaction->id }}</td>
                                                            <td>{{ $transaction->companyName != null ? $transaction->companyName->name : '-' }}
                                                            </td>
                                                            <td>{{ $transaction->date }}</td>
                                                            <td>
                                                                @if ($transaction->status == 'reject')
                                                                    <span
                                                                        class="status_badge badge bg-danger p-2 px-3 rounded">{{ $transaction->status }}</span>
                                                                @elseif($transaction->status == '')
                                                                    <span
                                                                        class="status_badge badge bg-warning p-2 px-3 rounded">Pending..</span>
                                                                @elseif($transaction->status == 'accept')
                                                                    <span
                                                                        class="status_badge badge bg-primary p-2 px-3 rounded">{{ $transaction->status }}</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $transaction->amount }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade " id="bonus" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog moda">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="myLargeModalLabel">{{ __('Send Request') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('payout.store') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="form-group" id="site-name-div">
                            <label class="form-label">{{ __('Request Amount') }}</label><x-required></x-required>
                            <input type="number" class="form-control" placeholder="{{ __('Enter Amount') }}"
                                name="amount" id="amount" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-light"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button class="btn btn-primary me-2">{{ __('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.menu-btn', function() {
            var target = $(this).data('target');

            $('.menu-section').addClass('d-none');
            $('.menu-btn').removeClass('active');

            $('#' + target).removeClass('d-none');
            $(this).addClass('active');
        });

        $(document).ready(function() {
            $('.menu-btn').click(function(e) {
                e.preventDefault();

                $('.menu-btn').removeClass('active');
                $(this).addClass('active');
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            var copyLinkButtons = document.querySelectorAll('.cp_link');
            copyLinkButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    var link = this.getAttribute('data-link');

                    // Create a temporary input element
                    var input = document.createElement('input');
                    input.setAttribute('value', link);
                    document.body.appendChild(input);

                    // Select and copy the link
                    input.select();
                    document.execCommand('copy');

                    // Remove the temporary input element
                    document.body.removeChild(input);
                    toastrs('success', '{{ __('Link Copy on Clipboard') }}', 'success')
                });
            });
        });
    </script>
@endsection
