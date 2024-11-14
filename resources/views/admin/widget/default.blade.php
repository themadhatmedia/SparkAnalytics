@extends('layouts.admin')
@section('page-title')
    {{ __('Widget') }}
@endsection
@section('action-button')
<div class="d-flex flex-wrap gap-3 mb-2 mb-md-0">
   <div class="">
    @if(count($site_data)>0)
      <select class="form-select" name="site_name" id="site-list" onchange=" widget_data(0,0)"> @foreach($site_data as $val) <option value="{{$val->id}}">{{$val->site_name}}</option> @endforeach </select>
      @endif
    </div>
    <div class="">
      <div class="input-group mr-sm-2">
        <input type="text" name="date_duration" onchange="widget_data(0,0)" class="form-control date_duration w-100" id="date_duration" />
      </div>
    </div>
    <div class="">
      <button type="button" class="btn  btn-primary" data-bs-toggle="modal" data-bs-target="#add_widget_modal" style="width: max-content;" onclick="get_site_id()">
        <span data-bs-toggle="tooltip" data-bs-original-title="{{__('Add Widget')}}">{{__('Add Widget')}}</span></button>
    </div>
  </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page">{{ __('Widget') }}</li>
@endsection
@section('content')
 

    <div class="row widget-card-data"></div>

<div class="col-xl-4 col-md-6">
  <div id="add_widget_modal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalPopoversLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalPopoversLabel">{{__('Add Widget')}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" class="needs-validation" novalidate>
              @csrf
              <div class="form-group" id="site-name-div">
                  <input type="hidden" class="form-control" name="add_id" id="add_id" value="0">
                  <label class="form-label">{{__('Title:')}}</label><x-required></x-required>
                  <input type="text" class="form-control" placeholder="Enter Title" name="title" id="title" required>
                  <div class="invalid-feedback">
                      {{ __('Please enter a title.') }}
                  </div>
                  <input type="hidden" class="form-control" name="site_id" id="site_id">
              </div>
              <div class="form-group" id="site-name-div">
                  <label class="form-label">{{__('Metrics:')}}</label>
                  <select class="form-select" name="metric_1" id="metric_1">
                      @foreach($metric_option as $key => $val)
                      <option value="{{$key}}">{{$val}}</option>
                      @endforeach
                  </select>
              </div>
              <div class="form-group" id="site-name-div">
                  <select class="form-select" name="metric_2" id="metric_2">
                      @foreach($metric_option as $key => $val)
                      <option value="{{$key}}">{{$val}}</option>
                      @endforeach
                  </select>
              </div>
          </form>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
          <button type="button" class="btn btn-primary" onclick="validateAndSaveWidget(1)">{{__('Create')}}</button>
      </div>      
      </div>
    </div>
  </div>
</div>
<div class="col-xl-4 col-md-6">
  <div id="edit_widget_modal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalPopoversLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalPopoversLabel">{{__('Edit Widget')}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST"> @csrf <div class="form-group" id="site-name-div">
              <input type="hidden" class="form-control" name="edit_id" id="edit_id">
              <label class="form-label">{{__('Title:')}}</label>
              <input type="text" class="form-control" placeholder="{{__('Enter Title')}}" name="edit_title" id="edit_title">
              <input type="hidden" class="form-control" name="s_id" id="s_id">
            </div>
            <div class="form-group" id="site-name-div">
              <label class="form-label">{{__('Metrics:')}}</label>
              <select class="form-select" name="edit_metric_1" id="edit_metric_1"> @foreach($metric_option as $key => $val) <option value="{{$key}}">{{$val}}</option> @endforeach </select>
            </div>
            <div class="form-group" id="site-name-div">
              <select class="form-select" name="edit_metric_2" id="edit_metric_2"> @foreach($metric_option as $key => $val) <option value="{{$key}}">{{$val}}</option> @endforeach </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
          <button type="button" class="btn  btn-primary" data-bs-dismiss="modal" onclick="save_widget(0)">{{__('Update')}}</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

$(document).ready(function()
{
  
   widget_data(0,0);

});
function validateAndSaveWidget(type) {
    var form = document.querySelector('.needs-validation');
    
    if (form.checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
    } else {
        save_widget(type); // Call your save widget function if validation passes
    }
    
    form.classList.add('was-validated'); // Add validation feedback class
}

</script>

@endsection