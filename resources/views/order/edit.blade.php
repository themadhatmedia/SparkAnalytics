
    {{-- {{Form::open(array('route'=>array('users.update', $users[0]['id']),'method'=>'PUT'))}} --}}
    {{-- {{ Form::model($order, ['route' => ['banktransfer.update', $order['id']], 'method' => 'PUT']) }} --}}
    <div class="row">
        <div class="col-md-4">
            <div class="form-control-label"><b>{{__('Order ID')}}</b></div>
        </div>
        <div class="col-md-6">
            <p class="mb-4">
                {{$order->order_id}}
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-control-label"><b>{{__('Plan Name')}}</b></div>
        </div>
        <div class="col-md-6">
            <p class="mb-4">
                {{$order->plan_name}}
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-control-label"><b>{{__('Plan Price')}}</b></div>
        </div>
        <div class="col-md-6">
            <p class="mb-4">
                {{$order->price}}
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-control-label"><b>{{__('Payment Type')}}</b></div>
        </div>
        <div class="col-md-6">
            <p class="mb-4">
                {{$order->payment_type}}
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-control-label"><b>{{__('Payment Status')}}</b></div>
        </div>
        <div class="col-md-6">
            <p class="mb-4">
                {{$order->payment_status}}
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-control-label"><b>{{__('Bank Details')}}</b></div>
        </div>
        <div class="col-md-6">
            <p class="mb-4">
                {!! $bank_details !!}
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-control-label"><b>{{__('Payment Receipt')}}</b></div>
        </div>
        <div class="col-md-6">
            <a href="{{ asset(Storage::url($order->receipt)) }}" class="btn  btn-primary btn-sm" download>
                                                <i class="ti ti-download"></i>
            </a>
        </div>
    </div>

        <div class="modal-footer">
            <a href="{{ route('response.status', [$order->id, 1 , $order->payment_frequency]) }}"
                class="btn btn-primary btn-xs">
                {{__('Accept')}}
            </a>
            <a href="{{ route('response.status', [$order->id, 0 ,$order->payment_frequency]) }}"
                class="btn btn-danger btn-xs">
                {{__('Reject')}}
            </a>
        </div>
    
    {{-- {{Form::close()}} --}}

