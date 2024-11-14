
@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Site') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page">{{ __('Manage Site') }}</li>
@endsection
@section('content')
<style type="text/css">
	code {
  padding: 5px 8px;
  border-radius: 10px;
  background-color: #f8f9f9;
  color: #CC0066;
}

[type='color'] {
  -moz-appearance: none;
  -webkit-appearance: none;
  appearance: none;
  padding: 0;
  width: 15px;
  height: 15px;
  border: none;
}

[type='color']::-webkit-color-swatch-wrapper {
  padding: 0;
}

[type='color']::-webkit-color-swatch {
  border: none;
}

.color-picker {
  padding: 10px 15px;
  border-radius: 10px;
  border: 1px solid #ccc;
  background-color: #f8f9f9;
}
</style>

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h5>Large Table</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive px-md-5 ">
              <table class="table table-lg " id="tbl_site_list">
                <thead>
                  <tr>
                    <th>Site name</th>
                    <th>Account ID</th>
                    <th>Property ID</th>
                    <th>View ID</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

<div class="col-xl-4 col-md-6">
  <div id="edit_site_modal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalPopoversLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="quick_view_model_header"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST"> @csrf <div class="modal-body">
            <div class="form-group" id="site-name-div">
              <input type="hidden" class="form-control" name="edit_id" id="edit_id">
              <label class="form-label">{{__('Site Name')}}:</label>
              <input type="text" class="form-control" placeholder="{{__('Enter Site Name')}}" name="site_name" id="site_name">
            </div>
            <span class="color-picker">
              <label for="colorPicker">
                <input type="color" value="#1DB8CE" id="colorPicker">
              </label>
            </span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn  btn-primary" data-bs-dismiss="modal" onclick="save_widget(0)">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	

</script>
@endsection
