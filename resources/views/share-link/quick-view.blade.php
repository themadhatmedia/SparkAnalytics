@extends('layouts.share-link')
@section('breadcrumb')
   
    
@endsection
@section('lang-section')
@foreach($languages as $language)
    <a class="dropdown-item @if($language == $currantLang) text-danger @endif" href="{{route('quickview.share.link',[\Illuminate\Support\Facades\Crypt::encrypt($data->id),$language])}}">{{\Str::upper($language)}}</a>
@endforeach
@endsection
@section('content')
@if(count($site_data)>0)
    @foreach($site_data as $val)
    @if ($val !== null)
    <div class="col-md-12 col-lg-6 col-xl-4 ">
      <div class="card">
          <div class="card-header">
              <div class="row">
                <div class="col-6">
                   <h5 class="site_name" data-site="{{$val->site_name}}" data-siteid="{{$val->id}}">{{$val->site_name}}</h5>
                </div>
              
              </div>
             
          </div>
          <div class="card-body" style="padding: 0px!important">
              <div id="quick_chart_{{$val->id}}">
                <div class="loader " id="progress_{{$val->id}}">
                <div class="spinner text-center" style="align-items: center;">
                  <img height="452px"  src="{{asset('assets/images/loader.gif')}}" />
                </div>
              </div>
              </div>
          </div>
          <div class="card-footer">
            <div class="row ">
                <div class="col-6">
                    <h6 class="surtitle" id="top_left_id_{{$val->id}}">-</h6>
                    <h6  id="top_left_value_{{$val->id}}">-</h6>
                </div>
                <div class="col-6 text-right">
                    <h6 class="surtitles text-end" id="top_right_id_{{$val->id}}">-</h6>
                    <h6 class="text-end" id="top_right_value_{{$val->id}}">-</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <h6 class="surtitle" id="bottom_left_id_{{$val->id}}">-</h6>
                    <h6  id="bottom_left_value_{{$val->id}}">-</h6>
                </div>
                <div class="col-6 text-right">
                    <h6 class="surtitle text-end" id="bottom_right_id_{{$val->id}}">-</h6>
                    <h6 class="text-end" id="bottom_right_value_{{$val->id}}">-</h6>
                </div>
            </div>
          </div>
      </div>
    </div>
    @else
    <div class="col-md-12" style="height: 200px; ">
      <div class="alert alert-primary alert-dismissible fade show text-center" role="alert">
              {{__('No Data Found !')}}
             
            </div>
    </div>
    @endif
    @endforeach
    @else
    <div class="col-md-12" style="height: 200px; ">
      <div class="alert alert-primary alert-dismissible fade show text-center" role="alert">
              {{__('No Data Found !')}}
             
            </div>
    </div>
    @endif

<script type="text/javascript">
 
  $(document).ready(function()
  {
      if($('.site_name').length) {
              $('.site_name').each(function (data) {
                  var siteName = $(this).attr('data-site');
                  var siteid = $(this).attr('data-siteid');
                  qick_view_data(siteName, siteid);
              });
          } 
  });



</script>
@endsection
