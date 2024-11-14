@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Plan') }}
@endsection
{{-- @php
    $admin_payment_setting   = App\Models\Utility::getAdminPaymentSetting();
@endphp --}}
@section('action-button')
    <div class="float-end">
        @can('create plan')
            {{-- @if (isset($admin_payment_setting) && !empty($admin_payment_setting))
                @if (
                        $admin_payment_setting['is_stripe_enabled'] == 'on' || $admin_payment_setting['is_paypal_enabled'] == 'on' ||
                        $admin_payment_setting['is_paystack_enabled'] == 'on' || $admin_payment_setting['is_flutterwave_enabled'] == 'on' ||
                        $admin_payment_setting['is_razorpay_enabled'] == 'on' || $admin_payment_setting['is_mercado_enabled'] == 'on' ||
                        $admin_payment_setting['is_paytm_enabled'] == 'on' || $admin_payment_setting['is_mollie_enabled'] == 'on' ||
                        $admin_payment_setting['is_skrill_enabled'] == 'on' || $admin_payment_setting['is_coingate_enabled'] == 'on' ||
                        $admin_payment_setting['is_paymentwall_enabled'] == 'on' || $admin_payment_setting['is_toyyibpay_enabled'] == 'on' ||
                        $admin_payment_setting['is_payfast_enabled'] == 'on' || $admin_payment_setting['is_banktransfer_enabled'] == 'on' ||
                        $admin_payment_setting['is_iyzipay_enabled'] == 'on' || $admin_payment_setting['is_sspay_enabled'] == 'on' ||
                        $admin_payment_setting['is_paytab_enabled'] == 'on' || $admin_payment_setting['is_benefit_enabled'] == 'on' ||
                        $admin_payment_setting['is_cashfree_enabled'] == 'on' || $admin_payment_setting['is_aamarpay_enabled'] == 'on' ||
                        $admin_payment_setting['is_paytr_enabled'] == 'on' || $admin_payment_setting['is_yookassa_enabled'] == 'on' ||
                        $admin_payment_setting['is_midtrans_enabled'] == 'on' || $admin_payment_setting['is_xendit_enabled'] == 'on' ||
                        $admin_payment_setting['is_payhere_enabled'] == 'on' || $admin_payment_setting['is_paiementpro_enabled'] == 'on' ||
                        $admin_payment_setting['is_nepalste_enabled'] == 'on' || $admin_payment_setting['is_cinetpay_enabled'] == 'on' ||
                        $admin_payment_setting['is_fedapay_enabled'] == 'on' || $admin_payment_setting['is_tap_enabled'] == 'on'  ||
                        $admin_payment_setting['is_authorizenet_enabled'] == 'on'|| $admin_payment_setting['is_ozow_enabled'] == 'on' ||
                        $admin_payment_setting['is_khalti_enabled'] == 'on') --}}

                    <div class="col-auto">
                        <a href="#" class="btn btn-sm btn-primary btn-icon m-1"
                            data-bs-toggle="modal"data-bs-target="#create_plan" data-size="lg" data-bs-whatever="Create New Plan">
                            <span class="text-white">
                                <i class="ti ti-plus" data-bs-toggle="tooltip"
                                    data-bs-original-title="{{ __('Create Plan') }}"></i>
                            </span>
                        </a>
                    </div>
                {{-- @endif
            @endif --}}
        @endcan
    </div>

@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page">{{ __('Plan') }}</li>
@endsection
@section('content')
    <div class="row">
        @foreach ($plans as $key => $plan)
            <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6 main">
                <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s" s>
                    <div class="card-body">
                        <span class="price-badge bg-primary">{{ $plan->name }}</span>
                        <div class="row d-flex my-2">
                            <div class="col-6 text-start">
                                @if (\Auth::user()->user_type == 'super admin' && $plan->monthly_price > 0 && $plan->annual_price > 0)
                                    <div class="d-inline-flex align-items-center mt-1 active-tag">
                                        <div class="form-check form-switch custom-switch-v1 float-end">
                                            <input type="checkbox" name="status"
                                                class="form-check-input input-primary status" value="1"
                                                data-id='{{ $plan->id }}' data-name="{{ __('plan') }}"
                                                {{ $plan->status == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status"></label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-6 text-end">
                                @if (\Auth::user()->user_type == 'super admin' && $plan->monthly_price > 0)
                                    <div class="action-btn bg-danger ms-2">

                                        {!! Form::open(['method' => 'DELETE', 'class' => 'm-0', 'route' => ['plans.destroy', $plan->id]]) !!}
                                        <a href="#!"
                                            class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm ">
                                            <i class="ti ti-trash text-white" data-bs-toggle="tooltip"
                                                data-bs-original-title="{{ __('Delete') }}"></i>
                                        </a>
                                        {!! Form::close() !!}
                                    </div>
                                @endif
                                @can('edit plan')
                                    <div class="action-btn bg-primary ms-2">
                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                            data-bs-toggle="modal"data-bs-target="#edit_plan" data-size="lg"
                                            data-bs-whatever="Edit Plan" onclick="edit_plan_data(<?= $plan->id ?>)">
                                            <span class="text-white">
                                                <i class="ti ti-pencil text-white" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Edit Plan') }}"></i>
                                            </span>
                                        </a>
                                    </div>
                                @endcan
                            </div>

                            @if (\Auth::user()->user_type == 'company' && \Auth::user()->plan == $plan->id)
                                <span class="d-flex align-items-center ms-2">
                                    <i class="f-10 lh-1 fas fa-circle text-success"></i>
                                    <span class="ms-2">{{ __('Active') }}</span>
                                </span>
                            @endif
                        </div>

                        <h3 class="mb-2 f-w-600 ">
                            {{ isset($setting['currency_symbol']) ? $setting['currency_symbol'] : '$' }}{{ $plan->monthly_price }}<small
                                class="text-sm">/{{ __('Month') }}</small></h3>
                        <h3 class="mb-4 f-w-600 ">
                            {{ isset($setting['currency_symbol']) ? $setting['currency_symbol'] : '$' }}{{ $plan->annual_price }}
                            <small class="text-sm">/{{ __('Annual Price') }}</small>
                        </h3>
                        <span>{{ __('Free Trial Days :') }}
                            <b>{{ !empty($plan->trial_days) ? $plan->trial_days : 0 }}</b></span>
                        <p class="m-b-0">{{ !empty($plan->description) ? $plan->description : '' }}</p>

                        <ul class="list-unstyled my-3">
                            <li>
                                <span class="theme-avtar">
                                    <i class="text-primary ti ti-circle-plus"></i>
                                </span>
                                {{ $plan->max_site }}
                                {{ __('Sites') }}
                            </li>
                            <li>
                                <span class="theme-avtar">
                                    <i class="text-primary ti ti-circle-plus"></i>
                                </span>
                                {{ $plan->max_widget }}
                                {{ __('Widget Per Sites') }}
                            </li>
                            <li>
                                <span class="theme-avtar">
                                    <i class="text-primary ti ti-circle-plus"></i>
                                </span>
                                {{ $plan->max_user }}
                                {{ __('Users') }}
                            </li>
                            <li>
                                <span class="theme-avtar">
                                    <i
                                        class="{{ $plan->custom == 1 ? 'text-primary' : 'text-danger' }} ti ti-circle-plus"></i>
                                </span>
                                @if ($plan->custom == 1)
                                    {{ __('Enable custom') }}
                                @else
                                    {{ __('Disable custom') }}
                                @endif

                            </li>
                            <li>
                                <span class="theme-avtar">
                                    <i
                                        class="{{ $plan->analytics == 1 ? 'text-primary' : 'text-danger' }} ti ti-circle-plus"></i>
                                </span>
                                @if ($plan->analytics == 1)
                                    {{ __('Enable Analytics') }}
                                @else
                                    {{ __('Disable Analytics') }}
                                @endif

                            </li>
                        </ul>
                        <br>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="modal fade " id="create_plan" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="myLargeModalLabel">{{ __('Create New Plan') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="save-plan" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="plan_id" id="plan_id" value="0">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="name" class="col-form-label">{{ __('Name') }}</label> <x-required></x-required>
                                <input type="text" class="form-control" id="name" name="name" required />
                            </div>
                            <div class="form-group col-md-4">
                                <label for="monthly_price" class="col-form-label">{{ __('Monthly Price') }}</label> <x-required></x-required>
                                <div class="form-icon-user">
                                    {{-- <span class="currency-icon">{{ (env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$') }}</span> --}}
                                    <input class="form-control" type="number" min="0" id="monthly_price"
                                        name="monthly_price" step="0.01" placeholder="{{ __('Monthly Price') }}" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="annual_price" class="col-form-label">{{ __('Annual Price') }}</label> <x-required></x-required>
                                <div class="form-icon-user">
                                    {{-- <span class="currency-icon">{{ (env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$') }}</span> --}}
                                    <input class="form-control" type="number" min="0" id="annual_price"
                                        name="annual_price" step="0.01" placeholder="{{ __('Annual Price') }}" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="max_site" class="col-form-label">{{ __('Maximum Site') }} </label> <x-required></x-required>
                                <input type="number" class="form-control mb-0" id="max_site" name="max_site"
                                    min="-1" required />
                                <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="form-group">
                                    <label for="max_widget" class="col-form-label">{{ __('Maximum Widget Per Site') }}
                                        </label> <x-required></x-required>
                                    <input type="number" class="form-control mb-0" id="max_widget" min="-1"
                                        name="max_widget" required />
                                    <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="form-group">
                                    <label for="max_user" class="col-form-label">{{ __('Maximum User') }}
                                        </label> <x-required></x-required>
                                    <input type="number" class="form-control mb-0" id="max_user" min="-1"
                                        name="max_user" required />
                                    <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-check-label" for="trial"></label>
                                <div class="form-group">
                                    <label for="trial" class="form-label">{{ __('Trial is enable(on/off)') }}</label>
                                    <div class="form-check form-switch custom-switch-v1 float-end">
                                        <input type="checkbox" name="trial"
                                            class="form-check-input input-primary pointer" value="1" id="trial">
                                        <label class="form-check-label" for="trial"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-none plan_div">
                                <div class="form-group">
                                    {{ Form::label('trial_days', __('Trial Days'), ['class' => 'form-label']) }}
                                    {{ Form::number('trial_days', null, ['class' => 'form-control', 'placeholder' => __('Enter Trial days'), 'id' => 'trial_days', 'step' => '1', 'min' => '1']) }}
                                </div>
                            </div>
                            <div class="form-group col-md-12 mb-0">
                                <div class="form-group">
                                    <label for="description" class="col-form-label">{{ __('Description') }}</label><x-required></x-required>
                                    <textarea class="form-control" id="description" name="description" required></textarea>
                                </div>
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="name" class="col-form-label"> {{ __('Custom') }} </label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input input-primary" value="1"
                                        name="custom" id="custom">
                                    <label class="form-check-label" for="custom"></label>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="name" class="col-form-label"> {{ __('Analytics') }} </label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input input-primary" value="1"
                                        name="analytics" id="analytics">
                                    <label class="form-check-label" for="analytics"></label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light"
                            data-bs-dismiss="modal">
                        <input type="submit" value="{{ __('Create') }}" class="btn btn-primary ms-2">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade " id="edit_plan" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="myLargeModalLabel">{{ __('Edit Plan') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="save-plan" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="plan_id" id="edit_plan_id">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="edit_name" class="col-form-label">{{ __('Name') }}</label><x-required></x-required>
                                <input type="text" class="form-control" id="edit_name" name="name" required />
                            </div>
                            <div class="form-group col-md-4">
                                <label for="edit_monthly_price" class="col-form-label">{{ __('Monthly Price') }}</label><x-required></x-required>
                                <div class="form-icon-user">
                                    {{-- <span class="currency-icon">{{ (env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$') }}</span> --}}
                                    <input class="form-control" type="number" min="0" id="edit_monthly_price"
                                        name="monthly_price" step="0.01" placeholder="{{ __('Monthly Price') }}" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="edit_annual_price" class="col-form-label">{{ __('Annual Price') }}</label><x-required></x-required>
                                <div class="form-icon-user">
                                    {{-- <span class="currency-icon">{{ (env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$') }}</span> --}}
                                    <input class="form-control" type="number" min="0" id="edit_annual_price"
                                        name="annual_price" step="0.01" placeholder="{{ __('Annual Price') }}" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="edit_max_site" class="col-form-label">{{ __('Maximum Site') }} </label><x-required></x-required>
                                <input type="number" class="form-control mb-0" id="edit_max_site" name="max_site"
                                    min="-1" required />
                                <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="form-group">
                                    <label for="edit_max_widget"
                                        class="col-form-label">{{ __('Maximum Widget Per Site') }}
                                        </label><x-required></x-required>
                                    <input type="number" class="form-control mb-0" id="edit_max_widget" min="-1"
                                        name="max_widget" required />
                                    <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="form-group">
                                    <label for="edit_max_user" class="col-form-label">{{ __('Maximum User') }}
                                        </label><x-required></x-required>
                                    <input type="number" class="form-control mb-0" id="edit_max_user" min="-1"
                                        name="max_user" required />
                                    <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
                                </div>
                            </div>

                            {{-- @if ('edit_plan_id' == 1) --}}
                            <div class="col-md-6 mt-3 plan_price_div">
                                <label class="form-check-label" for="trial"></label>
                                <div class="form-group">
                                    <label for="trial" class="form-label">{{ __('Trial is enable(on/off)') }}</label>
                                    <div class="form-check form-switch custom-switch-v1 float-end">
                                        <input type="checkbox" name="trial"
                                            class="form-check-input input-primary pointer" value="1" id="edit_trial"
                                            {{ $plan->trial == 1 ? ' checked ' : '' }}>
                                        <label class="form-check-label" for="trial"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6  {{ $plan->trial == 1 ? '  ' : 'd-none' }} plan_div plan_price_div">
                                <div class="form-group">
                                    {{ Form::label('trial_days', __('Trial Days'), ['class' => 'form-label']) }}
                                    {{ Form::number('trial_days', null, ['class' => 'form-control', 'placeholder' => __('Enter Trial days'), 'id' => 'edit_trial_days']) }}
                                </div>
                            </div>
                            {{-- @endif --}}
                            <div class="form-group col-md-12 mb-0">
                                <div class="form-group">
                                    <label for="description" class="col-form-label">{{ __('Description') }}</label><x-required></x-required>
                                    <textarea class="form-control" id="edit_description" name="description" required></textarea>
                                </div>
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="name" class="col-form-label"> {{ __('Custom') }} </label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input input-primary" value="1"
                                        name="custom" id="edit_custom">
                                    <label class="form-check-label" for="edit_custom"></label>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="name" class="col-form-label"> {{ __('Analytics') }} </label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input input-primary" value="1"
                                        name="analytics" id="edit_analytics">
                                    <label class="form-check-label" for="edit_analytics"></label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light"
                            data-bs-dismiss="modal">
                        <input type="submit" value="{{ __('Update') }}" class="btn btn-primary ms-2">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function edit_plan_data(id) {
            $('.plan_price_div').removeClass('d-none');
            if (id == 1) {
                $('.plan_price_div').addClass('d-none');
            }
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: $("#path_admin").val() + "/edit-plan/" + id,
                method: "POST",
                data: {
                    "_token": token
                },
                success: function(data) {
                    if (data.trial != 1) {
                        $('.plan_div').addClass('d-none');
                    }
                    $("#edit_plan_id").val(data.id);
                    $("#edit_name").val(data.name);
                    $("#edit_monthly_price").val(data.monthly_price);
                    $("#edit_annual_price").val(data.annual_price);
                    $("#edit_trial_days").val(data.trial_days);
                    $("#edit_max_site").val(data.max_site);
                    $("#edit_max_widget").val(data.max_widget);
                    $("#edit_max_user").val(data.max_user);
                    $('#edit_description').val(data.description);;
                    $('#edit_analytics').prop('checked', data.analytics);
                    $('#edit_custom').prop('checked', data.custom);
                    $('#edit_trial').prop('checked', data.trial);

                }
            });


        }
    </script>
    <script>
        $(document).on('change', '#trial', function() {
            if ($(this).is(':checked')) {
                $('.plan_div').removeClass('d-none');
                $('#trial').attr("required", true);
                $('#trial_days').attr("required", true);

            } else {
                $('.plan_div').addClass('d-none');
                $('#trial').removeAttr("required");
                $('#trial_days').removeAttr("required");
            }
        });
        $(document).on('change', '#edit_trial', function() {
            if ($(this).is(':checked')) {
                $('.plan_div').removeClass('d-none');
                $('#edit_trial').attr("required", true);
                $('#edit_trial_days').attr("required", true);

            } else {
                $('.plan_div').addClass('d-none');
                $('#edit_trial').removeAttr("required");
                $('#edit_trial_days').removeAttr("required");
            }
        });
        $(document).on("click", ".status", function() {

            var id = $(this).attr('data-id');
            var status = ($(this).is(':checked')) ? $(this).val() : 0;

            $.ajax({
                url: '{{ route('plan.disable') }}',
                type: 'POST',
                data: {
                    "status": status,
                    "id": id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data.success) {
                        toastrs('success', data.success, 'success');
                    } else {
                        toastrs('error', data.error, 'error');

                    }

                }
            });
        });
    </script>
@endsection
