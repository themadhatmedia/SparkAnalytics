@extends('layouts.admin')
@push('scripts')
@endpush
@section('page-title')
    {{ __('Order') }}
@endsection
@section('title')
    {{ __('Order') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Order') }}</li>
@endsection
@section('action-btn')
@endsection
@section('content')
    <div class="col-sm-12">

        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive overflow_hidden">
                    <table id="pc-dt-simple" class="table datatable align-items-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name"> {{ __('Order Id') }}</th>
                                <th scope="col" class="sort" data-sort="budget">{{ __('Date') }}</th>
                                <th scope="col" class="sort" data-sort="status">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Plan Name') }}</th>
                                <th scope="col" class="sort" data-sort="completion"> {{ __('Price') }}</th>
                                <th scope="col" class="sort" data-sort="completion"> {{ __('Payment Type') }}</th>
                                <th scope="col" class="sort" data-sort="completion"> {{ __('Status') }}</th>
                                <th scope="col" class="sort" data-sort="completion"> {{ __('Coupon') }}</th>
                                <th scope="col" class="sort text-center" data-sort="completion"> {{ __('Invoice') }}
                                </th>
                                <th scope="col" class="sort" data-sort="completion"> {{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>
                                        {{-- <div class="d-flex align-items-center">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input">
                                                </div>
                                                {{$order->order_id}}
                                            </div> --}}
                                        {{ $order->order_id }}
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>{{ $order->user_name }}</td>
                                    <td>{{ $order->plan_name }}</td>
                                    <td>{{ env('CURRENCY_SYMBOL') . $order->price }}</td>
                                    <td>{{ $order->payment_type }}</td>

                                    <td>
                                        @if ($order->payment_status == 'succeeded' || $order->payment_status == 'success' || $order->payment_status == 'COMPLETED')
                                            <span class="d-flex align-items-center">
                                                {{-- <i class="f-20 lh-1 ti ti-circle-check text-success"></i> --}}
                                                <span
                                                    class="badge rounded p-2 m-1 px-3 bg-primary w-100">{{ ucfirst($order->payment_status) }}</span>
                                            </span>
                                        @elseif($order->payment_status == 'Pending' || $order->payment_status == 'pending')
                                            <span class="d-flex align-items-center">
                                                {{-- <i class="f-20 lh-1 ti ti-circle-x text-danger"></i> --}}
                                                <span
                                                    class="badge rounded p-2 m-1 px-3 bg-warning w-100">{{ ucfirst($order->payment_status) }}</span>
                                            </span>
                                        @elseif($order->payment_status == 'Fail' || $order->payment_status == 'Rejected')
                                            <span class="d-flex align-items-center">
                                                {{-- <i class="f-20 lh-1 ti ti-circle-x text-danger"></i> --}}
                                                <span
                                                    class="badge rounded p-2 m-1 px-3 bg-danger w-100">{{ ucfirst($order->payment_status) }}</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ !empty($order->total_coupon_used) ? (!empty($order->total_coupon_used->coupon_detail) ? $order->total_coupon_used->coupon_detail->code : '-') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if ($order->receipt != 'free coupon' && $order->payment_type == 'STRIPE')
                                            <a href="{{ $order->receipt }}" title="Invoice" target="_blank"
                                                class=""><i class="fas fa-file-invoice"></i> </a>
                                        @elseif($order->receipt == 'free coupon')
                                            <p>{{ __('Used 100 % discount coupon code.') }}</p>
                                        @elseif($order->payment_type == 'Manually')
                                            <p>{{ __('Manually plan upgraded by super admin') }}</p>
                                        @elseif($order->payment_type == 'Bank Transfer')
                                            @php
                                                $thumbnail = !empty($order->receipt) ? '' . $order->receipt : '';
                                            @endphp
                                            <a href="{{ asset(Storage::url($thumbnail)) }}" title="Invoice" target="_blank"
                                                class=""><i class="fas fa-file-invoice"></i> </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="Action">
                                        @php
                                            $user = App\Models\User::find($order->user_id);
                                        @endphp
                                        @if ($order->payment_type == 'Bank Transfer' && $order->payment_status == 'Pending')
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-bs-toggle="modal" data-size="lg" data-bs-target="#exampleModal"
                                                    data-url="{{ route('banktransfer.edit', $order->id) }}"
                                                    data-bs-whatever="{{ __('Payment Status') }}"> <span
                                                        class="text-white"> <i class="ti ti-caret-right"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Edit') }}"></i></span></a>
                                            </div>
                                        @endif
                                        <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['orders.destroy', $order->id]]) !!}
                                            <a href="#!"
                                                class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm m-2">
                                                <i class="ti ti-trash text-white" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Delete') }}"></i>
                                            </a>
                                            {!! Form::close() !!}
                                        </div>
                                        @foreach ($userOrders as $userOrder)
                                            @if ($user->plan == $order->plan_id && $order->order_id == $userOrder->order_id && $order->is_refund == 0)
                                                <div class="badge bg-warning rounded p-2 px-3 ms-2">
                                                    <a href="{{ route('order.refund', [$order->id, $order->user_id]) }}"
                                                        class="mx-3 align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('Refund') }}"
                                                        data-original-title="{{ __('Refund') }}">
                                                        <span class ="text-white">{{ __('Refund') }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
   
@endsection

