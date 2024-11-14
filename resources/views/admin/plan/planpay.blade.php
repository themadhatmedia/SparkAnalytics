@php
$logo=asset(Storage::url('uploads/logo'));
$company_favicon=App\Models\Utility::getValByName('company_favicon');

@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Salesy Saas- Business Sales CRM">
    <meta name="author" content="Rajodiya Infotech">
    <title>{{(App\Models\Utility::getValByName('title_text')) ? App\Models\Utility::getValByName('title_text') : config('app.name', 'Analyticsgo')}} - @yield('page-title')</title>
    <link rel="icon" href="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" type="image" sizes="16x16">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@php
$plan_id= \Illuminate\Support\Facades\Crypt::decrypt($data['plan_id']);
$plandata=App\Models\Plan::find($plan_id);
$get_amount =  (float)$plandata->{$data['paymentwall_payment_frequency'] . '_price'};
@endphp




<script src="https://api.paymentwall.com/brick/build/brick-default.1.5.0.min.js"> </script>
<div id="payment-form-container"> </div>
<script>

var  paymentwall_callback = "{{ url('/plan/paymentwall') }}";

var brick = new Brick({
  public_key: '{{ $admin_payment_setting['paymentwall_public_key']  }}', // please update it to Brick live key before launch your project
  amount: {{$get_amount}},
  currency: '{{ $admin_payment_setting['currency']  }}',
  container: 'payment-form-container',
  action: '{{route("paymentwall.payment",[$data["plan_id"],"coupon" => $data["coupon"]])}}',
  success_url:'{{route("plans")}}',
  form: {
    merchant: 'Paymentwall',
    product: '{{$plandata->name}}',
    pay_button: 'Pay',
    show_zip: true, // show zip code
    show_cardholder: true // show card holder name
  },
});
brick.showPaymentForm(function(data) {
  if(data.flag == 1){
    window.location.href ='{{route("error.plan.show",1)}}';
  }else{
    window.location.href ='{{route("error.plan.show",2)}}';
  }
}, function(errors) {
  if(errors.flag == 1){
    window.location.href ='{{route("error.plan.show",1)}}';
  }else{
    window.location.href ='{{route("error.plan.show",2)}}';
  }
});

</script>
