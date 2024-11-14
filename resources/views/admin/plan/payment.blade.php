@extends('layouts.admin')
@php
    $dir = asset(Storage::url('uploads/plan'));
@endphp
@push('script-page')
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
@endpush
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    $(document).ready(function() {

    });

    $(document).on('click', '.apply-coupon', function(e) {
        e.preventDefault();
        var where = $(this).attr('data-from');

        applyCoupon($('#' + where + '_coupon').val(), where);
    })

    $('.custom-list-group-item').on('click', function() {
        var href = $(this).attr('data-href');
        $('.tabs-card').addClass('d-none');
        $(href).removeClass('d-none');
        $('#tabs .custom-list-group-item').removeClass('text-primary');
        $(this).addClass('text-primary');
    });

    function applyCoupon(coupon_code, where) {

        if (coupon_code != null && coupon_code != '') {
            $.ajax({
                url: '{{ route('apply.coupon') }}',
                datType: 'json',
                data: {
                    plan_id: '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}',

                    coupon: coupon_code,
                    frequency: "{{ $frequency }}",
                },
                success: function(data) {
                    if (data.is_success) {

                        $('.' + where + '-coupon-tr').show().find('.' + where + '-coupon-price').text(data
                            .discount_price);
                        $('.' + where + '-final-price').text("" + data.final_price);
                        if (where == 'payfast') {
                            get_payfast_status(data.final_price, coupon_code, 1);
                        }

                        toastrs('success', data.message, 'success');

                    } else {
                        $('.' + where + '-coupon-tr').hide().find('.' + where + '-coupon-price').text('');
                        $('.' + where + '-final-price').text(data.final_price);
                        toastrs('Error', data.message, 'error');
                    }
                }
            })
        } else {
            toastrs('Error', '{{ __('Invalid Coupon Code.') }}', 'error');
            $('.' + where + '-coupon-tr').hide().find('.' + where + '-coupon-price').text('');
        }
    }
</script>

@if (isset($admin_payment_setting['is_stripe_enabled']) &&
        $admin_payment_setting['is_stripe_enabled'] == 'on' &&
        !empty($admin_payment_setting['stripe_key']) &&
        !empty($admin_payment_setting['stripe_secret']))
    <?php $stripe_session = Session::get('stripe_session'); ?>
    <?php if(isset($stripe_session) && $stripe_session): ?>
    <script>
        var stripe = Stripe('{{ $admin_payment_setting['stripe_key'] }}');
        stripe.redirectToCheckout({
            sessionId: '{{ $stripe_session->id }}',
        }).then((result) => {
            console.log(result);
        });
    </script>
    <?php endif ?>
@endif
<script>
    @if (isset($admin_payment_setting['is_payfast_enabled']) &&
            $admin_payment_setting['is_payfast_enabled'] == 'on' &&
            !empty($admin_payment_setting['payfast_merchant_id']) &&
            !empty($admin_payment_setting['payfast_merchant_key']))
        $(document).ready(function() {
            get_payfast_status(amount = 0, coupon = null, apply_coupon = 0);
        })

        function get_payfast_status(amount, coupon, apply_coupon) {


            var plan_id = $('#plan_id').val();

            $.ajax({
                url: '{{ route('payfast.payment') }}',
                method: 'POST',
                data: {
                    'plan_id': plan_id,
                    'payfast_payment_frequency': "{{ $frequency }}",
                    'coupon_amount': amount,
                    'coupon_code': coupon,
                    'apply_coupon': apply_coupon
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {

                    if (apply_coupon == 1 && data.success == true && amount == 0) {

                        window.location.replace($("#path_admin").val() + "/plans");
                    }
                    if (apply_coupon == 1 && data.success == true && amount != 0) {
                        if (data.success == true) {
                            $('#get-payfast-inputs').empty();
                            $('#get-payfast-inputs').append(data.inputs);

                        } else {
                            toastrs('Error', data.inputs, 'error')
                        }
                    }
                    if (apply_coupon == 0 && data.success == true) {
                        if (data.success == true) {
                            $('#get-payfast-inputs').empty();
                            $('#get-payfast-inputs').append(data.inputs);

                        } else {
                            toastrs('Error', data.inputs, 'error')
                        }
                    }

                }
            });
        }
    @endif
</script>
@if (isset($admin_payment_setting['is_paystack_enabled']) && $admin_payment_setting['is_paystack_enabled'] == 'on')
    <script src="https://js.paystack.co/v1/inline.js"></script>

    <script>
        $(document).on("click", "#pay_with_paystack", function(e) {

            e.preventDefault();

            $('#paystack-payment-form').ajaxForm(function(res) {
                if (res.flag == 1) {
                    var coupon_id = res.coupon;
                    var frequency = res.frequency;
                    var paystack_callback = "{{ url('/plan/paystack') }}";
                    var order_id = '{{ time() }}';
                    var handler = PaystackPop.setup({
                        key: '{{ $admin_payment_setting['paystack_public_key'] }}',
                        email: res.email,
                        amount: res.total_price * 100,
                        currency: 'NGN',
                        ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                            1
                        ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                        metadata: {
                            custom_fields: [{
                                display_name: "Email",
                                variable_name: "email",
                                value: res.email,
                            }]
                        },

                        callback: function(response) {


                            window.location.href = paystack_callback + '/' + response
                                .reference + '/' + '{{ encrypt($plan->id) }}' + '?coupon_id=' +
                                coupon_id + '&frequency=' + frequency + '';
                        },
                        onClose: function() {
                            alert('window closed');
                        }
                    });
                    handler.openIframe();
                } else if (res.flag == 2) {
                    setTimeout(() => {
                        toastrs('{{ __('Success') }}', res.msg, 'success');
                        window.location.href = "{{ route('plans') }}";
                    }, 2000);

                } else {
                    toastrs('Error', res.msg, 'msg');
                }

            }).submit();
        });
    </script>
@endif

@if (isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on')
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>

    <script>
        //    Flaterwave Payment
        $(document).on("click", "#pay_with_flaterwave", function(e) {

            e.preventDefault();

            $('#flaterwave-payment-form').ajaxForm(function(res) {
                if (res.flag == 1) {
                    var coupon_id = res.coupon;
                    var API_publicKey = '';
                    if ("{{ isset($admin_payment_setting['flutterwave_public_key']) }}") {
                        API_publicKey = "{{ $admin_payment_setting['flutterwave_public_key'] }}";
                    }
                    var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                    var flutter_callback = "{{ url('/plan/flaterwave') }}";
                    var x = getpaidSetup({
                        PBFPubKey: API_publicKey,
                        customer_email: '{{ Auth::user()->email }}',
                        amount: res.total_price,
                        currency: res.currency,
                        txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) +
                            'fluttpay_online-' +
                            {{ date('Y-m-d') }},
                        meta: [{
                            metaname: "payment_id",
                            metavalue: "id"
                        }],
                        onclose: function() {},
                        callback: function(response) {
                            var txref = response.tx.txRef;
                            if (
                                response.tx.chargeResponseCode == "00" ||
                                response.tx.chargeResponseCode == "0"
                            ) {
                                window.location.href = flutter_callback + '/' + txref + '/' +
                                    '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}?coupon_id=' +
                                    coupon_id + '&payment_frequency=' + res.payment_frequency;
                            } else {
                                // redirect to a failure page.
                            }
                            x.close(); // use this to close the modal immediately after payment.
                        }
                    });
                } else if (res.flag == 2) {
                    setTimeout(() => {
                        toastrs('{{ __('Success') }}', res.msg, 'success');
                        window.location.href = "{{ route('plans') }}";
                    }, 2000);
                } else {
                    toastrs('Error', res.msg, 'msg');
                }

            }).submit();
        });
    </script>
@endif

@if (isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        // Razorpay Payment
        $(document).on("click", "#pay_with_razorpay", function(e) {
            e.preventDefault();
            $('#razorpay-payment-form').ajaxForm(function(res) {
                if (res.flag == 1) {

                    var razorPay_callback = '{{ url('/plan/razorpay') }}';
                    var totalAmount = res.total_price * 100;
                    var coupon_id = res.coupon;
                    var API_publicKey = '';
                    if ("{{ isset($admin_payment_setting['razorpay_public_key']) }}") {
                        API_publicKey = "{{ $admin_payment_setting['razorpay_public_key'] }}";
                    }
                    var options = {
                        "key": API_publicKey, // your Razorpay Key Id
                        "amount": totalAmount,
                        "name": 'Plan',
                        "currency": res.currency,
                        "description": "",
                        "handler": function(response) {
                            window.location.href = razorPay_callback + '/' + response
                                .razorpay_payment_id + '/' +
                                '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}?coupon_id=' +
                                coupon_id + '&payment_frequency=' + res.payment_frequency;
                        },
                        "theme": {
                            "color": "#528FF0"
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                } else if (res.flag == 2) {
                    setTimeout(() => {
                        toastrs('{{ __('Success') }}', res.msg, 'success');
                        window.location.href = "{{ route('plans') }}";
                    }, 2000);
                } else {
                    toastrs('Error', res.msg, 'msg');
                }

            }).submit();
        });
    </script>
@endif

{{-- Khalti Payment --}}
<!-- Include Khalti Checkout script -->
<script src="https://khalti.com/static/khalti-checkout.js"></script>

<script>
    $(document).ready(function() {
        var config = {
            "publicKey": "{{ isset($admin_payment_setting['khalti_public_key']) ? $admin_payment_setting['khalti_public_key'] : '' }}",
            "productIdentity": "1234567890",
            "productName": "demo",
            "productUrl": "{{ env('APP_URL') }}",
            "paymentPreference": [
                "KHALTI",
                "EBANKING",
                "MOBILE_BANKING",
                "CONNECT_IPS",
                "SCT",
            ],
            "eventHandler": {
                onSuccess(payload) {
                    if (payload.status == 200) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-Token': '{{ csrf_token() }}'
                            }
                        });
                        $.ajax({
                            url: '{{ route('khalti.status') }}',
                            method: 'POST',
                            data: {
                                'payload': payload,
                                'coupon_code': $('#khalti_coupon').val(),
                                'plan_id': $('.khalti_plan_id').val(),
                                'khalti_payment_frequency': $('.khalti_payment_frequency').val(),
                            },
                            beforeSend: function() {
                                $(".loader-wrapper").removeClass('d-none');
                            },
                            success: function(data) {
                                $(".loader-wrapper").addClass('d-none');
                                if (data.status_code === 200) {
                                    toastrs('Success', 'Plan Successfully Activated', 'success');
                                    setTimeout(() => {
                                        window.location.href = "{{ route('plans') }}";
                                    }, 1000);
                                } else {
                                    toastrs('Error', 'Payment Failed', 'msg');
                                }
                            },
                            error: function(err) {
                                toastrs('Error', err.response, 'msg');
                            },
                        });
                    }
                },
                onError(error) {
                    toastrs('Error', error, 'msg');
                },
                onClose() {}
            }
        };
        // Initialize checkout only after Khalti script is loaded
        var checkout = new KhaltiCheckout(config);

        $(document).on("click", "#pay_with_khalti", function(event) {
            event.preventDefault();
            get_khalti_status();
        });

        function get_khalti_status() {
            var coupon_code = $('#khalti_coupon').val();
            var plan_id = $('.khalti_plan_id').val();
            var khalti_payment_frequency = $('.khalti_payment_frequency').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route('plan.with.khalti') }}',
                method: 'POST',
                data: {
                    'coupon_code': coupon_code,
                    'plan_id': plan_id,
                    'khalti_payment_frequency': khalti_payment_frequency,
                },
                beforeSend: function() {
                    $(".loader-wrapper").removeClass('d-none');
                },
                success: function(data) {
                    $(".loader-wrapper").addClass('d-none');
                    if (data == 0) {
                        toastrs('Success', 'Plan Successfully Activated', 'success');
                        setTimeout(() => {
                            window.location.href = '{{ route('plans') }}';
                        }, 1000);
                    } else {
                        let price = data * 100;
                        checkout.show({
                            amount: price
                        });
                    }
                }
            });
        }
    });
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
</script>
{{-- Khalti Payment end --}}


@php
    $dir = asset(Storage::url('uploads/plan'));
    $dir_payment = asset(Storage::url('uploads/payments'));
@endphp
@section('page-title')
    {{ __('Order Summary') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{ __('Order Summary') }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('plans') }}">{{ __('Plan') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Order Summary') }}</li>
@endsection
@section('action-btn')
@endsection
@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="sticky-top" style="top:30px">
                        <div class="card ">
                            <div class="list-group list-group-flush" id="useradd-sidenav">
                                @if (isset($admin_payment_setting['is_manual_enabled']) && $admin_payment_setting['is_manual_enabled'] == 'on')
                                    <a href="#manual_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('manually') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_banktransfer_enabled']) &&
                                        $admin_payment_setting['is_banktransfer_enabled'] == 'on')
                                    <a href="#bank_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Bank Transfer') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_stripe_enabled']) &&
                                        $admin_payment_setting['is_stripe_enabled'] == 'on' &&
                                        !empty($admin_payment_setting['stripe_key']) &&
                                        !empty($admin_payment_setting['stripe_secret']))
                                    <a href="#stripe_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Stripe') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif

                                @if (isset($admin_payment_setting['is_paypal_enabled']) &&
                                        $admin_payment_setting['is_paypal_enabled'] == 'on' &&
                                        !empty($admin_payment_setting['paypal_client_id']) &&
                                        !empty($admin_payment_setting['paypal_secret_key']))
                                    <a href="#paypal_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Paypal') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif

                                @if (isset($admin_payment_setting['is_paystack_enabled']) &&
                                        $admin_payment_setting['is_paystack_enabled'] == 'on' &&
                                        !empty($admin_payment_setting['paystack_public_key']) &&
                                        !empty($admin_payment_setting['paystack_secret_key']))
                                    <a href="#paystack_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Paystack') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif

                                @if (isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on')
                                    <a href="#flutterwave_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Flutterwave') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif

                                @if (isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on')
                                    <a href="#razorpay_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Razorpay') }} <div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif

                                @if (isset($admin_payment_setting['is_mercado_enabled']) && $admin_payment_setting['is_mercado_enabled'] == 'on')
                                    <a href="#mercado_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Mercado Pago') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif

                                @if (isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on')
                                    <a href="#paytm_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Paytm') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif

                                @if (isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on')
                                    <a href="#mollie_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Mollie') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif

                                @if (isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on')
                                    <a href="#skrill_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Skrill') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif

                                @if (isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on')
                                    <a href="#coingate_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Coingate') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif

                                @if (isset($admin_payment_setting['is_paymentwall_enabled']) && $admin_payment_setting['is_paymentwall_enabled'] == 'on')
                                    <a href="#paymentwall_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Paymentwall') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif

                                @if (isset($admin_payment_setting['is_toyyibpay_enabled']) && $admin_payment_setting['is_toyyibpay_enabled'] == 'on')
                                    <a href="#toyyibpay_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Toyyibpay') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif

                                @if (isset($admin_payment_setting['is_payfast_enabled']) && $admin_payment_setting['is_payfast_enabled'] == 'on')
                                    <a href="#payfast_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Payfast') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif

                                @if (isset($admin_payment_setting['is_sspay_enabled']) && $admin_payment_setting['is_sspay_enabled'] == 'on')
                                    <a href="#sspay_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('SSPay') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif

                                @if (isset($admin_payment_setting['is_iyzipay_enabled']) && $admin_payment_setting['is_iyzipay_enabled'] == 'on')
                                    <a href="#iyzipay_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Iyzipay') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif

                                @if (isset($admin_payment_setting['is_paytab_enabled']) && $admin_payment_setting['is_paytab_enabled'] == 'on')
                                    <a href="#paytab_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('PayTab') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif

                                @if (isset($admin_payment_setting['is_benefit_enabled']) && $admin_payment_setting['is_benefit_enabled'] == 'on')
                                    <a href="#benefit_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Benefit') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif

                                @if (isset($admin_payment_setting['is_cashfree_enabled']) && $admin_payment_setting['is_cashfree_enabled'] == 'on')
                                    <a href="#cashfree_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Cashfree') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif

                                @if (isset($admin_payment_setting['is_aamarpay_enabled']) && $admin_payment_setting['is_aamarpay_enabled'] == 'on')
                                    <a href="#aamarpay_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Aamarpay') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif

                                @if (isset($admin_payment_setting['is_paytr_enabled']) && $admin_payment_setting['is_paytr_enabled'] == 'on')
                                    <a href="#paytr_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Pay Tr') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_yookassa_enabled']) && $admin_payment_setting['is_yookassa_enabled'] == 'on')
                                    <a href="#yookassa_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Yookassa') }} <div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payment_setting['is_midtrans_enabled']) && $admin_payment_setting['is_midtrans_enabled'] == 'on')
                                    <a href="#midtrans_payment"
                                        class="list-group-item list-group-item-action  border-0">{{ __('Midtrans') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_xendit_enabled']) && $admin_payment_setting['is_xendit_enabled'] == 'on')
                                    <a href="#xendit_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Xendit') }} <div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payment_setting['is_payhere_enabled']) && $admin_payment_setting['is_payhere_enabled'] == 'on')
                                    <a href="#payhere_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('PayHere') }} <div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payment_setting['is_paiementpro_enabled']) && $admin_payment_setting['is_paiementpro_enabled'] == 'on')
                                    <a href="#paiementpro_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Paiement Pro') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_nepalste_enabled']) && $admin_payment_setting['is_nepalste_enabled'] == 'on')
                                    <a href="#nepalste_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Nepalste') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_cinetpay_enabled']) && $admin_payment_setting['is_cinetpay_enabled'] == 'on')
                                    <a href="#cinetpay_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Cinetpay') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_fedapay_enabled']) && $admin_payment_setting['is_fedapay_enabled'] == 'on')
                                    <a href="#fedapay_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Fedapay') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_tap_enabled']) && $admin_payment_setting['is_tap_enabled'] == 'on')
                                    <a href="#tap_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Tap') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_authorizenet_enabled']) &&
                                        $admin_payment_setting['is_authorizenet_enabled'] == 'on')
                                    <a href="#authorizenet_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('AuthorizeNet') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_ozow_enabled']) && $admin_payment_setting['is_ozow_enabled'] == 'on')
                                    <a href="#ozow_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Ozow') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_khalti_enabled']) && $admin_payment_setting['is_khalti_enabled'] == 'on')
                                    <a href="#khalti_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Khalti') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="mt-5">
                            <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s"
                                style="visibility: visible;animation-delay: 0.2s;animation-name: fadeInUp;">
                                <div class="card-body">
                                    <span class="price-badge bg-primary">{{ $plan->name }}</span>
                                    @if (\Auth::user()->type == 'Owner' && \Auth::user()->plan == $plan->id)
                                        <div class="d-flex flex-row-reverse m-0 p-0 ">
                                            <span class="d-flex align-items-center ">
                                                <i class="f-10 lh-1 fas fa-circle text-success"></i>
                                                <span class="ms-2">{{ __('Active') }}</span>
                                            </span>
                                        </div>
                                    @endif

                                    <div class="text-end">
                                        <div class="">
                                            @if (\Auth::user()->type == 'super admin')
                                                <a title="Edit Plan" data-size="lg" href="#" class="action-item"
                                                    data-url="{{ route('plans.edit', $plan->id) }}"
                                                    data-ajax-popup="true" data-title="{{ __('Edit Plan') }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Plan') }}"><i class="fas fa-edit"></i></a>
                                            @endif
                                        </div>
                                    </div>

                                    <h3 class="mb-4 f-w-600  ">
                                        <h5 class="h6 my-4 "><span class="final-price">{{ $plan->price }}</span>
                                            / {{ !empty($plan->subscription_type) ? $plan->subscription_type : '' }}</h5>
                                        {{-- {{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price . ' / ' . __(\App\Models\Plan::$arrDuration[$plan->duration]) }}</small> --}}
                                    </h3>
                                    <p class="mb-0">
                                        {{ __('Trial : ') . $plan->trial_days . __(' Days') }}<br />
                                    </p>
                                    @if ($plan->description)
                                        <p class="mb-0">
                                            {{ $plan->description }}<br />
                                        </p>
                                    @endif
                                    <ul class="list-unstyled">
                                        <li> <span class="theme-avtar"><i
                                                    class="text-primary ti ti-circle-plus"></i></span>{{ $plan->trial_days < 0 ? __('Unlimited') : $plan->trial_days }}
                                            {{ __('Trial Days') }}</li>
                                        <li><span class="theme-avtar"><i
                                                    class="text-primary ti ti-circle-plus"></i></span>{{ $plan->max_site < 0 ? __('Unlimited') : $plan->max_site }}
                                            {{ __('Sites') }}</li>
                                        <li><span class="theme-avtar"><i
                                                    class="text-primary ti ti-circle-plus"></i></span>{{ $plan->max_widget < 0 ? __('Unlimited') : $plan->max_widget }}
                                            {{ __('Widget Per Sites') }}</li>
                                        <li><span class="theme-avtar"><i
                                                    class="text-primary ti ti-circle-plus"></i></span>{{ $plan->max_user < 0 ? __('Unlimited') : $plan->max_user }}
                                            {{ __('User') }}</li>
                                        <li>
                                            <span class="theme-avtar">
                                                <i class="text-primary ti ti-circle-plus"></i></span>
                                            {{ $plan->custom == 1 ? __('Enable') : __('Disable') }}
                                            {{ __('Custom') }}
                                        </li>
                                        <li>
                                            <span class="theme-avtar">
                                                <i class="text-primary ti ti-circle-plus"></i></span>
                                            {{ $plan->analytics == 1 ? __('Enable') : __('Disable') }}
                                            {{ __('Analytics') }}
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-xl-9">

                    {{-- manual payment --}}
                    @if (isset($admin_payment_setting['is_manual_enabled']) && $admin_payment_setting['is_manual_enabled'] == 'on')
                        <div id="manual_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('manually') }}</h5>
                            </div>
                            <div class="tab-pane {{ $admin_payment_setting['is_manual_enabled'] == 'on' ? 'active' : '' }}"
                                id="stripe_payment">
                                <form role="form"
                                    action="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}"
                                    method="post" class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="border p-3 rounded stripe-payment-div">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="custom-radio">
                                                    <label
                                                        class="font-weight-bold">{{ __('Requesting manual payment for the planned amount for the subscriptions plan.') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 my-2 px-2">
                                        <div class="text-end">
                                            @if (\Auth::user()->requested_plan != $plan->id && $plan->monthly_price)
                                                <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id), 'monthly']) }}"
                                                    class="btn btn-primary btn-icon"
                                                    data-title="{{ __('Send Request') }}" data-toggle="tooltip">
                                                    {{ __('Send Request') }}
                                                </a>
                                            @elseif (\Auth::user()->requested_plan != $plan->id && $plan->annual_price)
                                                <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id), 'yearly']) }}"
                                                    class="btn btn-primary btn-icon"
                                                    data-title="{{ __('Send Request') }}" data-toggle="tooltip">
                                                    {{ __('Send Request') }}
                                                </a>
                                            @else
                                                <a href="{{ route('request.cancel', \Auth::user()->id) }}"
                                                    class="btn btn-icon btn-danger"
                                                    data-title="{{ __('Cancle Request') }}" data-toggle="tooltip">
                                                    {{ __('Cancel Request') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    {{-- manual payment end --}}

                    {{-- bank payment --}}
                    @if (isset($admin_payment_setting['is_banktransfer_enabled']) &&
                            $admin_payment_setting['is_banktransfer_enabled'] == 'on' &&
                            !empty($admin_payment_setting['bank_details']))
                        <div id="bank_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Bank Transfer') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Bank Transfer') }}</p>
                            </div>
                            <div class="tab-pane {{ $admin_payment_setting['is_banktransfer_enabled'] == 'on' && !empty($admin_payment_setting['bank_details']) ? 'active' : '' }}"
                                id="bank_payment">
                                <form role="form" action="{{ route('banktransfer.post') }}" method="post"
                                    class="require-validation" id="payment-form" enctype='multipart/form-data'>
                                    {{-- {{ Form::open(['url' => route('seo.settings'), 'method' => 'post', 'enctype' => 'multipart/form-data']) }} --}}
                                    @csrf
                                    <div class="border p-3 rounded stripe-payment-div">
                                        <div class="row">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bank_details"
                                                        class="form-label">{{ __('Bank Details') }}</label><br>
                                                    @if (isset($admin_payment_setting['bank_details']) && !empty($admin_payment_setting['bank_details']))
                                                        {!! $admin_payment_setting['bank_details'] !!}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_receipt"
                                                        class="form-label">{{ __('Upload Receipt') }}</label><br>
                                                    <input type="file" name="payment_receipt" class="form-control"
                                                        Required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="card-element">
                                                    <!-- A Stripe Element will be inserted here. -->
                                                </div>
                                                <div id="card-errors" role="alert"></div>
                                            </div>
                                            <div class="col-md-12 mt-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-group w-100">
                                                        <label for="paypal_coupon"
                                                            class="form-label">{{ __('Coupon') }}</label>
                                                        <input type="text" id="bank_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                    <div class="form-group ms-3 mt-4">
                                                        <a href="#" class="text-muted apply-coupon"
                                                            data-bs-toggle="tooltip" data-from="bank"
                                                            data-bs-title="{{ __('Apply') }}"><i
                                                                class="fas fa-save"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-6">
                                                <p><b>{{ __('Plan Price :') }}</b>{{ $plan->price }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><b>{{ __('Net Amount : ') }}</b><span
                                                        class="bank-final-price">{{ $plan->price }}</span><br>
                                                    <small>{{ __('(After Applied Coupon)') }}</small>
                                                </p>
                                            </div>
                                        </div>



                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 my-2 px-2">
                                        <div class="text-end">
                                            <input type="hidden" id="stripe" value="stripe" name="payment_processor"
                                                class="custom-control-input">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <input type="hidden" name="stripe_payment_frequency"
                                                class="payment_frequency" value="{{ $frequency }}">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                {{-- (<span class="bank-final-price">{{ $plan->price }}</span>) --}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    {{-- stripe payment --}}
                    @if (isset($admin_payment_setting['is_stripe_enabled']) &&
                            $admin_payment_setting['is_stripe_enabled'] == 'on' &&
                            !empty($admin_payment_setting['stripe_key']) &&
                            !empty($admin_payment_setting['stripe_secret']))
                        <div id="stripe_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Stripe') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Stripe payment') }}</p>
                            </div>
                            <div class="tab-pane {{ ($admin_payment_setting['is_stripe_enabled'] == 'on' && !empty($admin_payment_setting['stripe_key']) && !empty($admin_payment_setting['stripe_secret'])) == 'on' ? 'active' : '' }}"
                                id="stripe_payment">
                                <form role="form" action="{{ route('stripe.post') }}" method="post"
                                    class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="border p-3 rounded stripe-payment-div">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="custom-radio">
                                                    <label
                                                        class="font-16 font-weight-bold">{{ __('Credit / Debit Card') }}</label>
                                                </div>
                                                <p class="pt-1 text-sm">
                                                    {{ __('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.') }}
                                                </p>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="card-name-on"
                                                        class="form-label text-dark">{{ __('Name on card') }}</label>
                                                    <input type="text" name="name" id="card-name-on"
                                                        class="form-control required"
                                                        placeholder="{{ \Auth::user()->name }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="card-element">
                                                    <!-- A Stripe Element will be inserted here. -->
                                                </div>
                                                <div id="card-errors" role="alert"></div>
                                            </div>
                                            <div class="col-md-12 mt-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-group w-100">
                                                        <label for="stripe_coupon"
                                                            class="form-label">{{ __('Coupon') }}</label>
                                                        <input type="text" id="stripe_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                    <div class="form-group ms-3 mt-4">
                                                        <a href="#" class="text-muted apply-coupon"
                                                            data-toggle="tooltip" data-from="stripe"
                                                            data-title="{{ __('Apply') }}" data-from="stripe"><i
                                                                class="fas fa-save"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 my-2 px-2">
                                        <div class="text-end">
                                            <input type="hidden" id="stripe" value="stripe" name="payment_processor"
                                                class="custom-control-input">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <input type="hidden" name="stripe_payment_frequency"
                                                class="payment_frequency" value="{{ $frequency }}">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="stripe-final-price">{{ $plan->price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    {{-- stripr payment end --}}

                    {{-- paypal start --}}
                    @if (isset($admin_payment_setting['is_paypal_enabled']) &&
                            $admin_payment_setting['is_paypal_enabled'] == 'on' &&
                            !empty($admin_payment_setting['paypal_client_id']) &&
                            !empty($admin_payment_setting['paypal_secret_key']))
                        <div id="paypal_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Paypal') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan paypal payment') }}</p>
                            </div>
                            {{-- <div class="card-body"> --}}
                            <div class="tab-pane {{ ($admin_payment_setting['is_stripe_enabled'] != 'on' && $admin_payment_setting['is_paypal_enabled'] == 'on' && !empty($admin_payment_setting['paypal_client_id']) && !empty($admin_payment_setting['paypal_secret_key'])) == 'on' ? 'active' : '' }}"
                                id="paypal_payment">
                                {{-- <div class="card"> --}}
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="payment-form"action="{{ route('plan.pay.with.paypal') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-md-12 mt-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-group w-100">
                                                        <label for="paypal_coupon"
                                                            class="form-label">{{ __('Coupon') }}</label>
                                                        <input type="text" id="paypal_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>

                                                    <div class="form-group ms-3 mt-4">
                                                        <a href="#" class="text-muted apply-coupon"
                                                            data-toggle="tooltip" data-from="paypal"
                                                            data-title="{{ __('Apply') }}"><i
                                                                class="fas fa-save"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 my-2 px-2">
                                        <div class="text-end">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <input type="hidden" name="paypal_payment_frequency"
                                                class="payment_frequency" value="{{ $frequency }}">
                                            <button class="btn btn-primary  pay-button" type="submit">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                <span class="paypal-final-price">{{ $plan->price }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    @endif
                    {{-- paypal end --}}

                    {{-- Paystack --}}
                    @if (isset($admin_payment_setting['is_paystack_enabled']) && $admin_payment_setting['is_paystack_enabled'] == 'on')
                        <div id="paystack_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Paystack') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Paystack payment') }}</p>
                            </div>
                            {{-- <div class="card-body"> --}}

                            <div class="tabs-card" id="paystack-payment">
                                <div class="">
                                    <form role="form" action="{{ route('plan.pay.with.paystack') }}" method="post"
                                        id="paystack-payment-form" class="w3-container w3-display-middle w3-card-4">
                                        @csrf

                                        <div class="">
                                            <div class="border p-3 mb-3 rounded payment-box">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-group w-100">
                                                        <label for="paystack_coupon"
                                                            class="form-label">{{ __('Coupon') }}</label>
                                                        <input type="text" id="paystack_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>

                                                    <div class="form-group ms-3 mt-4">
                                                        <a href="#" class="text-muted apply-coupon"
                                                            data-toggle="tooltip" data-from="paystack"
                                                            data-title="{{ __('Apply') }}"><i
                                                                class="fas fa-save"></i></a>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 my-2 px-2">
                                                <div class="text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <input type="hidden" name="paystack_payment_frequency"
                                                        class="payment_frequency" value="{{ $frequency }}">
                                                    <button class="btn btn-primary pay-button" type="submit"
                                                        id="pay_with_paystack">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                        (<span
                                                            class="paystack-final-price">{{ $plan->price }}</span>)</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Paystack end --}}

                    {{-- Flutterwave --}}

                    @if (isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on')
                        <div id="flutterwave_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Flutterwave') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Flutterwave payment') }}</p>
                            </div>
                            {{-- <div class="card-body"> --}}
                            <div class="tab-pane " id="flutterwave_payment">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="flaterwave-payment-form"action="{{ route('plan.pay.with.flaterwave') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="d-flex align-items-center">
                                            <div class="form-group w-100">
                                                <label for="flutterwave_coupon"
                                                    class="form-label">{{ __('Coupon') }}</label>
                                                <input type="text" id="flutterwave_coupon" name="coupon"
                                                    class="form-control coupon"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>

                                            <div class="form-group ms-3 mt-4">
                                                <a href="#" class="text-muted apply-coupon" data-from="flutterwave"
                                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                                        class="fas fa-save"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 my-2 px-2">
                                        <div class="text-end">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <input type="hidden" name="flutterwave_payment_frequency"
                                                class="payment_frequency" value="{{ $frequency }}">
                                            <button class="btn btn-primary pay-button" type="submit"
                                                id="pay_with_flaterwave">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="flutterwave-final-price">{{ $plan->price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- Flutterwave END --}}

                    {{-- Razorpay --}}
                    @if (isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on')
                        <div id="razorpay_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Razorpay') }} </h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Razorpay payment') }}</p>
                            </div>
                            {{-- <div class="card-body"> --}}

                            <div class="tabs-card" id="razorpay-payment">
                                <div class="">
                                    <form role="form" action="{{ route('plan.pay.with.razorpay') }}" method="post"
                                        class="w3-container w3-display-middle w3-card-4" id="razorpay-payment-form">
                                        @csrf

                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="razorpay_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="razorpay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-from="razorpay"
                                                        data-title="{{ __('Apply') }}"><i class="fas fa-save"></i></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end ml-2">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="razorpay_payment_frequency"
                                                    class="payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary  pay-button " type="button"
                                                    id="pay_with_razorpay">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                        class="razorpay-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Razorpay end --}}

                    {{-- Mercado Pago --}}
                    @if (isset($admin_payment_setting['is_mercado_enabled']) && $admin_payment_setting['is_mercado_enabled'] == 'on')
                        <div id="mercado_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Mercado Pago') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Mercado Pago payment') }}
                                </p>
                            </div>

                            <div class="tabs-card" id="mercadopago-payment">
                                <div class="">
                                    <form role="form" action="{{ route('plan.pay.with.mercado') }}" method="post"
                                        class="w3-container w3-display-middle w3-card-4" id="mercado-payment-form">
                                        @csrf

                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="mercado_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="mercado_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-title="{{ __('Apply') }}"
                                                        data-from='mercado'><i class="fas fa-save"></i></a>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="mercado_payment_frequency"
                                                    class="payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary pay-button h-auto" type="submit"
                                                    id="pay_with_paytm">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                        class="mercado-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Mercado Pago end --}}

                    {{-- Paytm --}}
                    @if (isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on')
                        <div id="paytm_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Paytm') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Paytm payment') }}</p>
                            </div>

                            <div class="tabs-card" id="paytm-payment">
                                <div class="">
                                    <form role="form" action="{{ route('plan.pay.with.paytm') }}" method="post"
                                        class="w3-container w3-display-middle w3-card-4" id="paytm-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="mobile"
                                                            class="form-label">{{ __('Mobile Number') }}</label>
                                                        <input type="text" id="mobile" name="mobile"
                                                            class="form-control mobile" data-from="mobile"
                                                            placeholder="{{ __('Enter Mobile Number') }}">
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="form-group w-100">
                                                        <label for="paytm_coupon"
                                                            class="form-label">{{ __('Coupon') }}</label>
                                                        <input type="text" id="paytm_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>

                                                    <div class="form-group ms-3 mt-4">
                                                        <a href="#" class="text-muted apply-coupon"
                                                            data-toggle="tooltip" data-from='paytm'
                                                            data-title="{{ __('Apply') }}"><i
                                                                class="fas fa-save"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="paytm_payment_frequency"
                                                    class="payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary  pay-button h-auto" type="submit"
                                                    id="pay_with_paytm">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                        class="paytm-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Paytm end --}}

                    {{-- Mollie --}}
                    @if (isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on')
                        <div id="mollie_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Mollie') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Mollie payment') }}</p>
                            </div>
                            {{-- <div class="card-body"> --}}
                            <div class="tabs-card" id="mollie-payment">
                                <div class="">
                                    <form role="form" action="{{ route('plan.pay.with.mollie') }}" method="post"
                                        class="w3-container w3-display-middle w3-card-4" id="mollie-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="mollie_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="mollie_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-from="mollie"
                                                        data-title="{{ __('Apply') }}"><i class="fas fa-save"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="mollie_payment_frequency"
                                                    class="payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary pay-button h-auto" type="submit"
                                                    id="pay_with_mollie">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                        class="mollie-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- Mollie end --}}
                        </div>
                    @endif
                    {{-- Skrill --}}
                    @if (isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on')
                        <div id="skrill_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Skrill') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Skrill payment') }}</p>
                            </div>

                            <div class="tabs-card" id="skrill-payment">
                                <div class="">
                                    <form role="form" action="{{ route('plan.pay.with.skrill') }}" method="post"
                                        class="w3-container w3-display-middle w3-card-4" id="skrill-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded payment-box skrill-payment-div">

                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="skrill_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="skrill_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-from="skrill"
                                                        data-title="{{ __('Apply') }}"><i class="fas fa-save"></i></a>
                                                </div>
                                            </div>

                                            @php
                                                $skrill_data = [
                                                    'transaction_id' => md5(
                                                        date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id',
                                                    ),
                                                    'user_id' => 'user_id',
                                                    'amount' => 'amount',
                                                    'currency' => 'currency',
                                                ];
                                                session()->put('skrill_data', $skrill_data);
                                            @endphp
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="skrill_payment_frequency"
                                                    class="payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary pay-button h-auto" type="submit"
                                                    id="pay_with_skrill">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                        class="skrill-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Skrill end --}}

                    {{-- Coingate --}}
                    @if (isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on')
                        <div id="coingate_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Coingate') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Coingate payment') }}</p>
                            </div>
                            <div class="tabs-card" id="coingate-payment">
                                <div class="">
                                    <form role="form" action="{{ route('plan.pay.with.coingate') }}" method="post"
                                        class="w3-container w3-display-middle w3-card-4" id="coingate-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="coingate_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="coingate_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-title="{{ __('Apply') }}"
                                                        data-from="coingate"><i class="fas fa-save"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="coingate_payment_frequency"
                                                    class="payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary pay-button h-auto" type="submit"
                                                    id="pay_with_coingate">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                        class="coingate-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Coingate end --}}

                    {{-- paymentwall --}}
                    @if (isset($admin_payment_setting['is_paymentwall_enabled']) && $admin_payment_setting['is_paymentwall_enabled'] == 'on')
                        <div id="paymentwall_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('PaymentWall') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan paymentwall payment') }}</p>
                            </div>
                            <div class="tabs-card" id="paymentwall-payment">
                                <div class="">
                                    <form role="form" action="{{ route('paymentwall') }}" method="post"
                                        class="w3-container w3-display-middle w3-card-4" id="paymentwall-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="paymentwall_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paymentwall_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-from="paymentwall"
                                                        data-title="{{ __('Apply') }}"><i class="fas fa-save"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="paymentwall_payment_frequency"
                                                    class="payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary pay-button h-auto" type="submit"
                                                    id="pay_with_paymentwall">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                        class="paymentwall-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- paymentwall end --}}

                    {{-- Toyyibpay --}}
                    @if (isset($admin_payment_setting['is_toyyibpay_enabled']) && $admin_payment_setting['is_toyyibpay_enabled'] == 'on')
                        <div id="toyyibpay_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Toyyibpay') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Toyyibpay payment') }}</p>
                            </div>
                            <form role="form" action="{{ route('plan-pay-with-toyyibpay') }}" method="post"
                                class="require-validation" id="coingate-payment-form">
                                @csrf
                                <div class="border p-3 rounded ">
                                    <div class="tab-pane " id="toyyibpay_payment">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="hidden" name="toyyibpay_payment_frequency"
                                            class="payment_frequency" value="{{ $frequency }}">
                                        <div class="d-flex align-items-center">
                                            <div class="form-group w-100">
                                                <label for="paypal_coupon" class="form-label">Coupon</label>
                                                <input type="text" id="toyyibpay_coupon" name="coupon"
                                                    class="form-control coupon" data-from="toyyibpay"
                                                    placeholder="Enter Coupon Code">
                                            </div>
                                            <div class="form-group ms-3 mt-4">
                                                <a href="#" class="text-muted apply-coupon" data-toggle="tooltip"
                                                    data-from="toyyibpay" data-title="Apply"><i
                                                        class="fas fa-save"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-end">
                                        <button class="btn btn-primary pay-button h-auto" type="submit"
                                            id="pay_with_toyyibpay">
                                            <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                class="toyyibpay-final-price">{{ $plan->price }}</span>)
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    @endif
                    {{-- Toyyibpay end --}}

                    {{-- Payfast --}}
                    @if (isset($admin_payment_setting['is_payfast_enabled']) &&
                            $admin_payment_setting['is_payfast_enabled'] == 'on' &&
                            !empty($admin_payment_setting['payfast_merchant_id']) &&
                            !empty($admin_payment_setting['payfast_merchant_key']) &&
                            !empty($admin_payment_setting['payfast_signature']) &&
                            !empty($admin_payment_setting['payfast_mode']))
                        <div id="payfast_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Payfast') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Payfast payment') }}</p>
                            </div>

                            {{-- <div class="card-body"> --}}
                            <div
                                class="tab-pane {{ ($admin_payment_setting['is_payfast_enabled'] == 'on' && !empty($admin_payment_setting['payfast_merchant_id']) && !empty($admin_payment_setting['payfast_merchant_key'])) == 'on' ? 'active' : '' }}">
                                @php
                                    $pfHost =
                                        $admin_payment_setting['payfast_mode'] == 'sandbox'
                                            ? 'sandbox.payfast.co.za'
                                            : 'www.payfast.co.za';
                                @endphp
                                <form role="form" action={{ 'https://' . $pfHost . '/eng/process' }} method="post"
                                    class="require-validation" id="payfast-form">
                                    <div class="border p-3 rounded ">
                                        <div class="d-flex align-items-center">
                                            <div class="form-group w-100">
                                                <label for="paypal_coupon"
                                                    class="form-label">{{ __('Coupon') }}</label>
                                                <input type="text" id="payfast_coupon" name="coupon"
                                                    class="form-control coupon" data-from="payfast"
                                                    placeholder="{{ __('Enter Coupon Code') }}">

                                            </div>
                                            <div class="form-group ms-3 mt-4">
                                                <a href="#" class="text-muted apply-coupon" data-toggle="tooltip"
                                                    data-from="payfast" data-title="{{ __('Apply') }}"><i
                                                        class="fas fa-save"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="get-payfast-inputs"></div>
                                    <div class="col-sm-12 my-2 px-2">
                                        <div class="text-end">
                                            <input type="hidden" name="plan_id" id="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <input type="hidden" name="payfast_payment_frequency"
                                                id="payfast_payment_frequency" class="payment_frequency"
                                                value="{{ $frequency }}">
                                            <button type="submit" data-from="payfast" value="{{ __('Pay Now') }}"
                                                id="pay_with_payfast"
                                                class="btn btn-xs btn-primary">{{ __('Pay Now') }}(<span
                                                    class="payfast-final-price">{{ $plan->price }}</span>)</button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    {{-- Payfast end --}}

                    {{-- sspay --}}
                    @if (isset($admin_payment_setting['is_sspay_enabled']) && $admin_payment_setting['is_sspay_enabled'] == 'on')
                        <div id="sspay_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('SSPay') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan SSPay payment') }}</p>
                            </div>
                            <div class="tabs-card" id="SSPay-payment">
                                <div class="">
                                    <form role="form" action="{{ route('sspay.prepare.plan') }}" method="post"
                                        class="w3-container w3-display-middle w3-card-4" id="SSPay-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="sspay_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="sspay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-from="sspay"
                                                        data-title="{{ __('Apply') }}"><i class="fas fa-save"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="sspay_payment_frequency"
                                                    class="payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary pay-button h-auto" type="submit"
                                                    id="pay_with_sspay">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                        class="sspay-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- toyyibpay end --}}
                        </div>
                    @endif
                    {{-- sspay end --}}

                    {{-- iyzipay --}}
                    @if (isset($admin_payment_setting['is_iyzipay_enabled']) && $admin_payment_setting['is_iyzipay_enabled'] == 'on')
                        <div id="iyzipay_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Iyzipay') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Iyzipay payment') }}</p>
                            </div>
                            <div class="tabs-card" id="iyzipay-payment">
                                <div class="">
                                    <form role="form" action="{{ route('iyzipay.payment.init') }}" method="post"
                                        class="w3-container w3-display-middle w3-card-4" id="iyzipay-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="iyzipay_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="iyzipay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-from="iyzipay"
                                                        data-title="{{ __('Apply') }}"><i
                                                            class="fas fa-save"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="iyzipay_payment_frequency"
                                                    class="payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary pay-button h-auto" type="submit"
                                                    id="pay_with_iyzipay">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    (<span class="iyzipay-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- toyyibpay end --}}
                        </div>
                    @endif
                    {{-- iyzipay end --}}

                    {{-- paytab --}}
                    @if (isset($admin_payment_setting['is_paytab_enabled']) && $admin_payment_setting['is_paytab_enabled'] == 'on')
                        <div id="paytab_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('PayTab') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan PayTab payment') }}</p>
                            </div>
                            <div class="tabs-card" id="paytab-payment">
                                <div class="">
                                    <form role="form" action="{{ route('plan.pay.with.paytab') }}" method="post"
                                        class="w3-container w3-display-middle w3-card-4" id="paytab-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="paytab_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paytab_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-from="paytab"
                                                        data-title="{{ __('Apply') }}"><i
                                                            class="fas fa-save"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="paytab_payment_frequency"
                                                    class="payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary pay-button h-auto" type="submit"
                                                    id="pay_with_paytab">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    (<span class="paytab-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- toyyibpay end --}}
                        </div>
                    @endif
                    {{-- paytab end --}}

                    {{-- benifit --}}
                    @if (isset($admin_payment_setting['is_benefit_enabled']) && $admin_payment_setting['is_benefit_enabled'] == 'on')
                        <div id="benefit_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Benefit') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Benefit payment') }}</p>
                            </div>
                            <div class="tabs-card" id="benefit-payment">
                                <div class="">
                                    <form role="form" action="{{ route('benefit.initiate') }}" method="post"
                                        class="w3-container w3-display-middle w3-card-4" id="benefit-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="benefit_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="benefit_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-from="benefit"
                                                        data-title="{{ __('Apply') }}"><i
                                                            class="fas fa-save"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="benefit_payment_frequency"
                                                    class="payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary pay-button h-auto" type="submit"
                                                    id="pay_with_benefit">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    (<span class="benefit-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- benifit end --}}

                    {{-- cashfree --}}
                    @if (isset($admin_payment_setting['is_cashfree_enabled']) && $admin_payment_setting['is_cashfree_enabled'] == 'on')
                        <div id="cashfree_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Cashfree') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan cashfree payment') }}</p>
                            </div>
                            <div class="tabs-card" id="cashfree-payment">
                                <div class="">
                                    <form role="form" action="{{ route('cashfree.payment') }}" method="post"
                                        class="w3-container w3-display-middle w3-card-4" id="cashfree-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="cashfree_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="cashfree_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-from="cashfree"
                                                        data-title="{{ __('Apply') }}"><i
                                                            class="fas fa-save"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="cashfree_payment_frequency"
                                                    class="payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary pay-button h-auto" type="submit"
                                                    id="pay_with_cashfree">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    (<span class="cashfree-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- cashfree end --}}

                    {{-- aamarpay --}}
                    @if (isset($admin_payment_setting['is_aamarpay_enabled']) && $admin_payment_setting['is_aamarpay_enabled'] == 'on')
                        <div id="aamarpay_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Aamarpay') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan aamarpay payment') }}</p>
                            </div>
                            <div class="tabs-card" id="aamarpay-payment">
                                <div class="">
                                    <form role="form" action="{{ route('pay.aamarpay.payment') }}" method="post"
                                        class="w3-container w3-display-middle w3-card-4" id="aamarpay-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="aamarpay_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="aamarpay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-from="aamarpay"
                                                        data-title="{{ __('Apply') }}"><i
                                                            class="fas fa-save"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="aamarpay_payment_frequency"
                                                    class="payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary pay-button h-auto" type="submit"
                                                    id="pay_with_aamarpay">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    (<span class="aamarpay-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- aamarpay end --}}


                    {{-- paytr --}}
                    @if (isset($admin_payment_setting['is_paytr_enabled']) && $admin_payment_setting['is_paytr_enabled'] == 'on')
                        <div id="paytr_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Pay Tr') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Pay Tr payment') }}</p>
                            </div>
                            <div class="tabs-card" id="paytr-payment">
                                <div class="">
                                    <form role="form" action="{{ route('pay.paytr.payment') }}" method="post"
                                        class="w3-container w3-display-middle w3-card-4" id="paytr-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="paytr_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paytr_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-from="paytr"
                                                        data-title="{{ __('Apply') }}"><i
                                                            class="fas fa-save"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="paytr_payment_frequency"
                                                    class="payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary pay-button h-auto" type="submit"
                                                    id="pay_with_paytr">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    (<span class="paytr-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- paytr end --}}
                    {{-- yookassa  --}}
                    @if (isset($admin_payment_setting['is_yookassa_enabled']) && $admin_payment_setting['is_yookassa_enabled'] == 'on')
                        <div id="yookassa_payment" class="card  shadow-none border-bottom ">

                            <form class="w3-container w3-display-middle w3-card-4" method="get"
                                id="yookassa-payment-form" action="{{ route('plan.pay.with.yookassa') }}">
                                @csrf <div class="card-header">
                                    <h5>{{ __('Yookassa') }}</h5>
                                    <p class="text-sm text-muted">{{ __('Details about your plan Yookassa payment') }}
                                    </p>
                                </div>
                                <div class="">
                                    <form>
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="yookassa_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="yookassa_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-from="yookassa"
                                                        data-title="{{ __('Apply') }}"><i
                                                            class="fas fa-save"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="col-sm-12">
                                                <div class="float-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <input type="hidden" name="yookassa_payment_frequency"
                                                        class="payment_frequency" value="{{ $frequency }}">
                                                    <button class="btn btn-primary d-flex align-items-center"
                                                        type="submit">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i>
                                                        {{ __('Pay Now') }}
                                                        (<span class="yookassa-final-price">{{ $plan->price }}</span>)
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif
                    {{-- yookassa end --}}
                    {{-- Midtrans  --}}
                    @if (isset($admin_payment_setting['is_midtrans_enabled']) && $admin_payment_setting['is_midtrans_enabled'] == 'on')
                        <div id="midtrans_payment" class="card  shadow-none border-bottom ">
                            <form class="w3-container w3-display-middle w3-card-4" method="get"
                                id="midtrans-payment-form" action="{{ route('plan.get.midtrans') }}">
                                @csrf <div class="card-header">
                                    <h5>{{ __('Midtrans') }}</h5>
                                </div>
                                <div class="">
                                    <form>
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="midtrans_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="midtrans_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-from="midtrans"
                                                        data-title="{{ __('Apply') }}"><i
                                                            class="fas fa-save"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="col-sm-12">
                                                <div class="float-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <input type="hidden" name="midtrans_payment_frequency"
                                                        class="payment_frequency" value="{{ $frequency }}">
                                                    <button class="btn btn-primary d-flex align-items-center"
                                                        type="submit">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i>
                                                        {{ __('Pay Now') }}
                                                        (<span class="midtrans-final-price">{{ $plan->price }}</span>)
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif
                    {{-- Midtrans end --}}
                    {{-- Xendit --}}
                    @if (isset($admin_payment_setting['is_xendit_enabled']) && $admin_payment_setting['is_xendit_enabled'] == 'on')
                        <div id="xendit_payment" class="card  shadow-none border-bottom ">
                            <form class="w3-container w3-display-middle w3-card-4" method="get"
                                id="xendit-payment-form" action="{{ route('plan.xendit.payment') }}">
                                @csrf <div class="card-header">
                                    <h5>{{ __('xendit') }}</h5>
                                </div>
                                <div class="">
                                    <form>
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="xendit_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="xendit_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="form-group ms-3 mt-4">
                                                    <a href="#" class="text-muted apply-coupon"
                                                        data-toggle="tooltip" data-from="xendit"
                                                        data-title="{{ __('Apply') }}"><i
                                                            class="fas fa-save"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="col-sm-12">
                                                <div class="float-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <input type="hidden" name="xendit_payment_frequency"
                                                        class="payment_frequency" value="{{ $frequency }}">
                                                    <button class="btn btn-primary d-flex align-items-center"
                                                        type="submit">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i>
                                                        {{ __('Pay Now') }}
                                                        (<span class="xendit-final-price">{{ $plan->price }}</span>)
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif
                    {{-- Xendit end --}}
                    {{-- payhere --}}
                    @if (isset($admin_payment_setting['is_payhere_enabled']) && $admin_payment_setting['is_payhere_enabled'] == 'on')
                        <div id="payhere_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('PayHere') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan PayHere payment') }}</p>
                            </div>
                            <div class="tab-pane" id="payhere_payment">
                                <form role="form" action="{{ route('plan.payhere.payment') }}" method="post"
                                    id="payhere-payment-form" class="w3-container w3-display-middle w3-card-4">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-md-12 mt-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-group w-100">
                                                        <label for="payhere_coupon"
                                                            class="form-label">{{ __('Coupon') }}</label>
                                                        <input type="text" id="payhere_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                    <div class="form-group ms-3 mt-4">
                                                        <a href="#" class="text-muted apply-coupon"
                                                            data-toggle="tooltip" data-from="payhere"
                                                            data-title="{{ __('Apply') }}"><i
                                                                class="fas fa-save"></i></a>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="error" style="display: none;">
                                                                <div class='alert-danger alert'>
                                                                    {{ __('Please correct the errors and try again.') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 my-2 px-2">
                                        <div class="text-end">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <input type="hidden" name="payhere_payment_frequency"
                                                class="payment_frequency" value="{{ $frequency }}">
                                            <button class="btn btn-primary  pay-button h-auto" type="submit"
                                                id="pay_with_paytm">
                                                <i class="mdi mdi-cash-multiple mr-1"></i>
                                                {{ __('Pay Now') }}
                                                (<span class="payhere-final-price">{{ $plan->price }}</span>)
                                            </button>

                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    @endif
                    {{-- payhere end --}}
                    {{-- Paiement Pro --}}
                    @if (isset($admin_payment_setting['is_paiementpro_enabled']) && $admin_payment_setting['is_paiementpro_enabled'] == 'on')
                        <div id="paiementpro_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Paiement Pro') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Paiement Pro payment') }}
                                </p>
                            </div>

                            <div class="tabs-card" id="paiementpro-payment">
                                <div class="">
                                    <form role="form" action="{{ route('plan.pay.with.paiementpro') }}"
                                        method="post" class="w3-container w3-display-middle w3-card-4"
                                        id="paiementpro-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="row">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-group w-100">
                                                        <label for="paiementpro_coupon"
                                                            class="form-label">{{ __('Coupon') }}</label>
                                                        <input type="text" id="paiementpro_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>

                                                    <div class="form-group ms-3 mt-4">
                                                        <a href="#" class="text-muted apply-coupon"
                                                            data-toggle="tooltip" data-from="paiementpro"
                                                            data-title="{{ __('Apply') }}"><i
                                                                class="fas fa-save"></i></a>
                                                    </div>

                                                </div>


                                                <div class="form-group col-md-6" id="mobile_div">
                                                    {{ Form::label('mobile_number', __('Mobile Number'), ['class' => 'form-label']) }}
                                                    <input type="text" name="mobile_number"
                                                        class="form-control font-style mobile_number"
                                                        id="mobile_number">
                                                </div>
                                                <div class="form-group col-md-6" id="channel_div">
                                                    {{ Form::label('channel', __('Channel'), ['class' => 'form-label']) }}
                                                    <input type="text" name="channel"
                                                        class="form-control font-style channel" id="channel">
                                                    <small class="text-danger">Example : OMCIV2,MOMO,CARD,FLOOZ
                                                        ,PAYPAL</small>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="hidden" name="paiementpro_payment_frequency"
                                                    class="paiementpro_payment_frequency" value="{{ $frequency }}">
                                                <button class="btn btn-primary  pay-button h-auto" type="submit"
                                                    id="pay_with_paiementpro">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    (<span class="paiementpro-final-price">{{ $plan->price }}</span>)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Paiement Pro end --}}

                    {{-- Nepalist --}}
                    @if (isset($admin_payment_setting['is_nepalste_enabled']) && $admin_payment_setting['is_nepalste_enabled'] == 'on')
                        <div id="nepalste_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Nepalste') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Nepalste payment') }}</p>
                            </div>
                            <div class="tab-pane" id="nepalste_payment">
                                <form role="form" action="{{ route('plan.pay.with.nepalste', $plan->id) }}"
                                    method="post" id="nepalste-payment-form"
                                    class="w3-container w3-display-middle w3-card-4">
                                    @csrf

                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-md-12 mt-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-group w-100">
                                                        <label for="nepalste_coupon"
                                                            class="form-label">{{ __('Coupon') }}</label>
                                                        <input type="text" id="nepalste_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>

                                                    <div class="form-group ms-3 mt-4">
                                                        <a href="#" class="text-muted apply-coupon"
                                                            data-toggle="tooltip" data-from="nepalste"
                                                            data-title="{{ __('Apply') }}"><i
                                                                class="fas fa-save"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 my-2 px-2">
                                        <div class="text-end">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <input type="hidden" name="nepalste_payment_frequency"
                                                class="nepalste_payment_frequency" value="{{ $frequency }}">
                                            <button class="btn btn-primary  pay-button h-auto" type="submit"
                                                id="pay_with_nepalste">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                (<span class="nepalste-final-price">{{ $plan->price }}</span>)
                                            </button>

                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    @endif
                    {{-- Nepalist end --}}

                    {{-- CinetPay --}}
                    @if (isset($admin_payment_setting['is_cinetpay_enabled']) && $admin_payment_setting['is_cinetpay_enabled'] == 'on')
                        <div id="cinetpay_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Cinetpay') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Cinetpay payment') }}</p>
                            </div>
                            <div class="tab-pane" id="cinetpay_payment">
                                <form role="form" action="{{ route('plan.pay.with.cinetpay') }}" method="post"
                                    id="cinetpay-payment-form" class="w3-container w3-display-middle w3-card-4">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-md-12 mt-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-group w-100">
                                                        <label for="cinetpay_coupon"
                                                            class="form-label">{{ __('Coupon') }}</label>
                                                        <input type="text" id="cinetpay_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="error" style="display: none;">
                                                                <div class='alert-danger alert'>
                                                                    {{ __('Please correct the errors and try again.') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ms-3 mt-4">
                                                        <a href="#" class="text-muted apply-coupon"
                                                            data-toggle="tooltip" data-from="cinetpay"
                                                            data-title="{{ __('Apply') }}"><i
                                                                class="fas fa-save"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 my-2 px-2">
                                        <div class="text-end">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <input type="hidden" name="cinetpay_payment_frequency"
                                                class="cinetpay_payment_frequency" value="{{ $frequency }}">
                                            <button class="btn btn-primary  pay-button h-auto" type="submit"
                                                id="pay_with_cinetpay">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                (<span class="cinetpay-final-price">{{ $plan->price }}</span>)
                                            </button>

                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    @endif
                    {{-- CinetPay end --}}

                    {{-- Fedapay --}}
                    @if (isset($admin_payment_setting['is_fedapay_enabled']) && $admin_payment_setting['is_fedapay_enabled'] == 'on')
                        <div id="fedapay_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Fedapay') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Fedapay payment') }}</p>
                            </div>
                            <div class="tab-pane" id="fedapay_payment">
                                <form role="form" action="{{ route('plan.pay.with.fedapay') }}" method="post"
                                    id="fedapay-payment-form" class="w3-container w3-display-middle w3-card-4">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-md-12 mt-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-group w-100">
                                                        <label for="fedapay_coupon"
                                                            class="form-label">{{ __('Coupon') }}</label>
                                                        <input type="text" id="fedapay_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                    <div class="form-group ms-3 mt-4">
                                                        <a href="#" class="text-muted apply-coupon"
                                                            data-toggle="tooltip" data-from="fedapay"
                                                            data-title="{{ __('Apply') }}"><i
                                                                class="fas fa-save"></i></a>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="error" style="display: none;">
                                                                <div class='alert-danger alert'>
                                                                    {{ __('Please correct the errors and try again.') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 my-2 px-2">
                                        <div class="text-end">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <input type="hidden" name="fedapay_payment_frequency"
                                                class="fedapay_payment_frequency" value="{{ $frequency }}">
                                            <button class="btn btn-primary  pay-button h-auto" type="submit"
                                                id="pay_with_fedapay">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                (<span class="fedapay-final-price">{{ $plan->price }}</span>)
                                            </button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    {{-- Fedapay end --}}

                    {{-- Tap --}}
                    @if (isset($admin_payment_setting['is_tap_enabled']) && $admin_payment_setting['is_tap_enabled'] == 'on')
                        <div id="tap_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Tap') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Tap payment') }}</p>
                            </div>
                            <div class="tab-pane" id="tap_payment1">
                                <form role="form" action="{{ route('plan.with.tap', $plan->id) }}" method="post"
                                    id="tap-payment-form" class="w3-container w3-display-middle w3-card-4">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-md-12 mt-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-group w-100">
                                                        <label class="form-label" class="form-control-label"
                                                            for="tap_coupon">{{ __('Coupon') }}</label>
                                                        <input type="text" id="tap_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                    <div class="form-group ms-3 mt-4">
                                                        <a href="#" class="text-muted apply-coupon"
                                                            data-toggle="tooltip" data-from="tap"
                                                            data-title="{{ __('Apply') }}"><i
                                                                class="fas fa-save"></i></a>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="error" style="display: none;">
                                                                <div class='alert-danger alert'>
                                                                    {{ __('Please correct the errors and try again.') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 my-2 px-2">
                                        <div class="text-end">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <input type="hidden" name="tap_payment_frequency"
                                                class="tap_payment_frequency" value="{{ $frequency }}">
                                            <button class="btn btn-primary  pay-button h-auto" type="submit"
                                                id="pay_with_tap">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                (<span class="tap-final-price">{{ $plan->price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    {{-- Tap end --}}

                    {{-- AuthorizeNet --}}
                    @if (isset($admin_payment_setting['is_authorizenet_enabled']) &&
                            $admin_payment_setting['is_authorizenet_enabled'] == 'on')
                        <div id="authorizenet_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('AuthorizeNet') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan AuthorizeNet payment') }}
                                </p>
                            </div>
                            <div class="tab-pane" id="authorizenet_payment">
                                <form role="form" action="{{ route('plan.with.authorizenet', $plan->id) }}"
                                    method="post" id="authorizenet-payment-form"
                                    class="w3-container w3-display-middle w3-card-4">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-md-12 mt-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-group w-100">
                                                        <label for="authorizenet_coupon"
                                                            class="form-label">{{ __('Coupon') }}</label>
                                                        <input type="text" id="authorizenet_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                    <div class="form-group ms-3 mt-4">
                                                        <a href="#" class="text-muted apply-coupon"
                                                            data-toggle="tooltip" data-from="authorizenet"
                                                            data-title="{{ __('Apply') }}"><i
                                                                class="fas fa-save"></i></a>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="error" style="display: none;">
                                                                <div class='alert-danger alert'>
                                                                    {{ __('Please correct the errors and try again.') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 my-2 px-2">
                                        <div class="text-end">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <input type="hidden" name="authorizenet_payment_frequency"
                                                class="authorizenet_payment_frequency" value="{{ $frequency }}">
                                            <button class="btn btn-primary  pay-button h-auto" type="submit"
                                                id="pay_with_authorizenet">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                (<span class="authorizenet-final-price">{{ $plan->price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    {{-- AuthorizeNet end --}}

                    {{-- Ozow --}}
                    @if (isset($admin_payment_setting['is_ozow_enabled']) && $admin_payment_setting['is_ozow_enabled'] == 'on')
                        <div id="ozow_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Ozow') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Ozow payment') }}
                                </p>
                            </div>
                            <div class="tab-pane" id="ozow_payment">
                                <form role="form" action="{{ route('plan.with.ozow', $plan->id) }}"
                                    method="post" id="ozow-payment-form"
                                    class="w3-container w3-display-middle w3-card-4">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-md-12 mt-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-group w-100">
                                                        <label class="form-label" class="form-control-label"
                                                            for="ozow_coupon">{{ __('Coupon') }}</label>
                                                        <input type="text" id="ozow_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                    <div class="form-group ms-3 mt-4">
                                                        <a href="#" class="text-muted apply-coupon"
                                                            data-toggle="tooltip" data-from="ozow"
                                                            data-title="{{ __('Apply') }}"><i
                                                                class="fas fa-save"></i></a>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="error" style="display: none;">
                                                                <div class='alert-danger alert'>
                                                                    {{ __('Please correct the errors and try again.') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-sm-12 my-2 px-2">
                                        <div class="text-end">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <input type="hidden" name="ozow_payment_frequency"
                                                class="ozow_payment_frequency" value="{{ $frequency }}">
                                            <button class="btn btn-primary  pay-button h-auto" type="submit"
                                                id="pay_with_ozow">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                (<span class="ozow-final-price">{{ $plan->price }}</span>)
                                            </button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    {{-- Ozow end --}}

                    {{-- Khalti --}}
                    @if (isset($admin_payment_setting['is_khalti_enabled']) && $admin_payment_setting['is_khalti_enabled'] == 'on')
                        <div id="khalti_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Khalti') }}</h5>
                                <p class="text-sm text-muted">{{ __('Details about your plan Kahlti payment') }}
                                </p>
                            </div>
                            <div class="tab-pane" id="khalti_payment">
                                <form role="form" action="{{ route('plan.with.khalti', $plan->id) }}"
                                    method="post" id="khalti-payment-form"
                                    class="w3-container w3-display-middle w3-card-4">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-md-12 mt-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-group w-100">
                                                        <label class="form-label"
                                                            for="khalti_coupon">{{ __('Coupon') }}</label>
                                                        <input type="text" id="khalti_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                    <div class="form-group ms-3 mt-4">
                                                        <a href="#" class="text-muted apply-coupon"
                                                            data-toggle="tooltip" data-from="khalti"
                                                            data-title="{{ __('Apply') }}"><i
                                                                class="fas fa-save"></i></a>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="error" style="display: none;">
                                                                <div class='alert-danger alert'>
                                                                    {{ __('Please correct the errors and try again.') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 my-2 px-2">
                                        <div class="text-end">
                                            <input type="hidden" name="plan_id" class="khalti_plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <input type="hidden" name="khalti_payment_frequency"
                                                class="khalti_payment_frequency" value="{{ $frequency }}">
                                            <button class="btn btn-primary  pay-button h-auto" type="submit"
                                                id="pay_with_khalti">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                (<span class="khalti-final-price">{{ $plan->price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    {{-- Khalti end --}}
                </div>
            </div>
        </div>
    </div>
@endsection
