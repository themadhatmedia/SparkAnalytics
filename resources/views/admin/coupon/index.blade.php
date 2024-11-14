@extends('layouts.admin')

@section('page-title')
    {{ __('Coupon') }}
@endsection

@section('action-button')
<div class="col-auto">
    <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-target="#create_coupon" data data-bs-whatever="{{ __('Create New Coupon') }}" data-bs-toggle="modal" data-bs-original-title="{{ __('Create New Coupon') }}">
        <i class="ti ti-plus text-white" data-bs-toggle="tooltip" title="{{ __('Create Coupon') }}"></i>
    </a>
</div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page">{{ __('Coupon') }}</li>
@endsection

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th> {{ __('Name') }}</th>
                                <th> {{ __('Code') }}</th>
                                <th> {{ __('Discount (%)') }}</th>
                                <th> {{ __('Limit') }}</th>
                                <th> {{ __('Used') }}</th>
                                <th> {{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->name }}</td>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ $coupon->discount }}</td>
                                    <td>{{ $coupon->limit }}</td>
                                    <td>{{ $coupon->used_coupon() }}</td>
                                    <td>
                                        <span>
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{ route('coupons.show', $coupon->id) }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-bs-whatever="{{ __('View Coupons') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('View') }}"> 
                                                    <span class="text-white"> <i class="ti ti-eye" data-bs-toggle="tooltip"  title="{{ __('View') }}"></i></span></a>
                                            </div>

                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" onclick="edit_coupun_data(<?=$coupon->id ?>)" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-target="#edit_coupon" data data-bs-whatever="{{ __('Edit Coupon') }}" data-bs-toggle="modal" data-bs-original-title="{{ __('Edit Coupon') }}">
                                                    <i class="ti ti-edit text-white" data-bs-toggle="tooltip" title="{{ __('Edit') }}"></i>
                                                </a>
                                            </div>

                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'class' => 'm-0', 'route' => ['coupons.destroy', $coupon->id]]) !!}
                                                <a href="#!"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm ">
                                                    <i class="ti ti-trash text-white" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Delete') }}"></i>
                                                </a>
                                                {!! Form::close() !!}
                                            </div>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade " id="create_coupon" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4" id="myLargeModalLabel">{{ __('Create New Coupon') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('save-coupon') }}" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="coupon_id" value="0">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="name" class="col-form-label">{{ __('Name') }}</label><x-required></x-required>
                            <input type="text" required name="name" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="discount" class="col-form-label">{{ __('Discount') }}</label><x-required></x-required>
                            <input type="number" name="discount" class="form-control" required step="0.01">
                            <span class="small">{{ __('Note: Discount in Percentage') }}</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="limit" class="col-form-label">{{ __('Limit') }}</label><x-required></x-required>
                            <input type="number" name="limit" class="form-control" required>
                        </div>
                        <div class="form-group col-md-12" id="auto">
                            <label for="auto-code" class="col-form-label">{{ __('Code') }}</label><x-required></x-required>

                            <div class="row">
                                <div class="col-md-10">
                                    <input class="form-control" name="code" type="text" id="auto-code" required>
                                </div>
                                <div class="col-md-2">
                                    <a href="#" class="btn btn-primary btn btn-sm btn-icon-only rounded-circle shadow-sm" id="code-generate"><i class="ti ti-history"></i></a>
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade " id="edit_coupon" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4" id="myLargeModalLabel">{{ __('Edit Coupons') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('save-coupon') }}" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="coupon_id" value="0" id="coupon_id">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="name" class="col-form-label">{{ __('Name') }}</label><x-required></x-required>
                            <input type="text" required name="name" class="form-control" id="name" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="discount" class="col-form-label">{{ __('Discount') }}</label><x-required></x-required>
                            <input type="number" name="discount" class="form-control" id="discount" required step="0.01">
                            <span class="small">{{ __('Note: Discount in Percentage') }}</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="limit" class="col-form-label">{{ __('Limit') }}</label><x-required></x-required>
                            <input type="number" name="limit" class="form-control" id="limit" required>
                        </div>
                        <div class="form-group col-md-12" id="auto">
                            <label for="auto-code" class="col-form-label">{{ __('Code') }}</label><x-required></x-required>
                            
                            <div class="row">
                                <div class="col-md-10">
                                    <input class="form-control" name="code" type="text"  id="edit-auto-code" required>
                                </div>
                                <div class="col-md-2">
                                    <a href="#" class="btn btn-primary btn btn-sm btn-icon-only rounded-circle shadow-sm" id="edit-code-generate"><i class="ti ti-history"></i></a>
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
 <script>
        $(document).on('click', '#code-generate', function() {
            console.log("add");
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
        $(document).on('click', '#edit-code-generate', function() {
            console.log("edit");
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#edit-auto-code').val(result);
        });
        function edit_coupun_data(id) {
            var token= $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                    url: $("#path_admin").val()+"/edit-coupon/"+id ,
                    method:"POST",
                    data: {"_token": token},
                    success: function(data) {
                        $("#coupon_id").val(data.id);
                        $("#name").val(data.name);
                        $("#discount").val(data.discount);
                        $("#limit").val(data.limit);
                        $("#edit-auto-code").val(data.code);
                        
                }
            });
             
        }
    </script>
@endsection


   

