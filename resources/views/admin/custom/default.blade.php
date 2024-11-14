@extends('layouts.admin')
@section('page-title')
    {{ __('Custom') }}
@endsection
@section('action-button')
@if(count($site_data)>0)
<div class="d-flex flex-wrap gap-3 mb-2 mb-md-0">
    <div class="">
         <select class="form-select" name="site_name" id="site-list" >@foreach($site_data as $val) <option value="{{$val->id}}">{{$val->site_name}}</option> @endforeach  </select>
    </div>
    @if(\Auth::user()->can('manage share report settings'))
     <div class="custom-setting" data-bs-toggle="tooltip" data-bs-original-title="{{__('Share Report Setting')}}"></div>
    @endif
    @if(\Auth::user()->can('manage share report'))
     <div class="link" data-bs-toggle="tooltip" data-bs-original-title="{{__('Share Report')}}"></div>
    @endif
    <a style="display: none;" href="#" class="btn  btn-primary " id="download-btn" onclick="saveAsPDF('custom page')"data-bs-toggle="tooltip" title="{{__('Download')}}" data-original-title="{{__('Download')}}">
        <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
    </a>
</div>

@endif
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page">{{ __('Custom') }}</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5>{{__('Custom Chart')}}</h5>
                </div>
                <div class="col-md-6">
                    <div class="row">
                    <div class="col-sm-3 mb-2">
                        <div class="input-group mr-sm-2">
                          <select class="form-select" name="metrics" id="metrics-list"  onchange="get_dimension()">
                            <option selected="" value="0" disabled="">{{__('Metric')}}</option>

                          @foreach($metrics as $key => $val) <option value="{{$key}}" data-name="{{$val}}">{{$val}}</option> @endforeach 
                          </select>
                        </div>
                    </div>
                    <div class="col-sm-3 mb-2">
                        <div class="input-group mr-sm-2">
                          <select class="form-select" name="dimension" id="dimension-list" > 
                            <option selected="" value="0" disabled="" >{{__('Dimension')}}</option>
                           </select>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-2">
                        <div class="input-group mr-sm-2">
                          <input type="text" name="date_duration"  class="form-control date_duration w-100" id="date_duration" />
                        </div>
                    </div>
                    <div class="col-sm-2 mb-2">
                        <button type="button" class="btn  btn-primary" onclick="get_custom_cart()" data-bs-toggle="tooltip" data-bs-original-title="{{__('Create Custom Chart')}}">{{__('Refresh')}}</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="custom_chart">
                <div class="col-12 pt-5 text-center">
                    <h5>Please Select Metrics & Dimension For Custom Chart</h5>
                </div>
            </div>
        </div>
    </div>
</div>  


 <div id="share_custom_report" class="modal fade" tabindex="-1" aria-labelledby="exampleModalPopoversLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalPopoversLabel">{{__('Share Report')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="share_site"id="share_site">
                             <div class="form-group col-md-12">
                                <label for="share_met" class="col-form-label"> {{__('Metric') }} </label>
                                <input type="hidden" class="form-control"  name="share_met" id="share_met">
                                <input type="text" readonly="" class="form-control"  name="share_metric" id="share_metric">
                                <label class="form-check-label" for="share_met"></label>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="share_dim" class="col-form-label"> {{__('Dimension') }} </label>
                                <input type="hidden"  class="form-control" name="share_dim" id="share_dim">
                                <input type="text" readonly="" class="form-control" name="share_dimension" id="share_dimension">
                                <label class="form-check-label" for="share_dim"></label>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="name" class="col-form-label"> {{__('Password Protected') }} </label>
                                <div class="form-check form-switch">
                                    <input onclick="password_status()" type="checkbox" class="form-check-input input-primary"name="is_password" id="is_password">
                                    <label class="form-check-label" for="is_password"></label>
                                </div>
                            </div>
                            <div class="form-group col-md-9" style="display: none" id="password-box">
                                <label for="password" class="col-form-label">{{__('Password') }}</label>
                                <div class="action input-group input-group-merge  text-left ">
                                    <input type="password" value="12345678" class=" form-control " name="password" autocomplete="new-password" id="password" placeholder="Enter Your Password">
                                    <div class="input-group-append">
                                        <span class="input-group-text py-3">
                                            <a href="#" data-toggle="password-text" data-target="#password">
                                                <i class="fas fa-eye-slash" id="togglePassword"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <a href="#" title="{{__('Save')}}" class="btn  btn-primary" onclick="save_custom_setting('custom')">{{__('Save changes')}}</a>
                        
                    </div>
               
            </div>
        </div>
    </div>
     <script>
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    togglePassword.addEventListener("click", function () {
        // toggle the type attribute
        const type = password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type);

        // toggle the icon
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });

    // prevent form submit
    // const form = document.querySelector("form");
    // form.addEventListener('submit', function (e) {
    //     e.preventDefault();
    // });

</script>
@endsection