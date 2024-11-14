@extends('layouts.admin')
@section('page-title')
    {{ __('Audience Analytics') }}
@endsection
@section('action-button')
@if(count($site_data)>0)
<div class=" d-flex align-items-center flex-wrap gap-3">
   <div class="select-box">
     <select class="form-select" name="site_name" id="site-list" >@foreach($site_data as $val) <option value="{{$val->id}}">{{$val->site_name}}</option> @endforeach  </select>
    </div>
    @if(\Auth::user()->can('manage share report settings'))
    <div class="btn p-0">
      <a class="btn btn-primary" onclick="share_setting('audience');" data-bs-toggle="modal"  data-bs-target="#share_audience_report">
        <span><i class="ti ti-settings text-white" data-bs-toggle="tooltip" data-bs-original-title="{{__('Share Report Setting')}}"></i></span></a>
    </div>
    @endif
    @if(\Auth::user()->can('manage share report'))
    <div class="link" data-bs-toggle="tooltip" data-bs-original-title="{{__('Share Report')}}"></div>
    @endif
    <a href="#" class="btn btn-primary" onclick="saveAsPDF('audience page')"data-bs-toggle="tooltip" title="{{__('Download')}}" data-original-title="{{__('Download')}}">
            <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
     </a>
</div>
@endif
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
      <li class="breadcrumb-item" aria-current="page">{{ __('Audience Analytics') }}</li>
@endsection
@section('content')
@if(count($site_data)>0)
<div class="col-md-12">
  <div class="card">
    <div class="card-body">
        <div class="row">
          <div class="col-md-10 ">
              <div class=" ">
                  <ul class="nav nav-pills nav-fill row" id="pills-tab" role="tablist">
                      @php $i=0; @endphp
                      @foreach($dimension_option as $key => $dimension)
                      <li class="nav-item col-sm-2" role="presentation">
                          <button class="nav-link audience-analytics {{ ($i == 0) ? ' active' : '' }} " id="pills-tab-{{$i}}" onclick="get_audience_data()" data-bs-toggle="pill" data-value="{{$key}}"
                              data-bs-target="#" type="button">{{$dimension}}</button>   
                      </li>
                      @php $i++; @endphp
                      @endforeach
                      <li class="col-sm-6"></li>
                  </ul>
              </div>
          </div>
          <div class="col-md-2">
              <div class="input-group mr-sm-2">
                <input type="text" name="date_duration"  onchange="get_audience_data()" class="form-control date_duration w-100" id="date_duration" />
              </div>
          </div>
        </div>
    </div>
  </div>
</div> 
<div class="col-sm-12 col-md-12 col-xxl-12">
   <div class="card">
      <div class="card-body">
          <div class="tab-content" id="pills-tabContent">
               <?php $j=1; ?>
              @foreach($metric_option as $key => $val)
              @if($j==1)
              <div class="tab-pane fade show active" id="channel-chart-{{$j}}" role="tabpanel"
                  aria-labelledby="pills-user-tab-1">
                  @else
                  <div class="tab-pane fade =" id="channel-chart-{{$j}}" role="tabpanel"
                  aria-labelledby="pills-user-tab-1">
                  @endif
                  <div class="row">
                    <div class="col-md-6">

                       <div class="card">
                          <div class="card-body">
                            <div id="audience-line-chart-{{$key}}">
                              <div class="loader " id="progress">
                                <div class="spinner text-center" style="align-items: center;">
                                  <img height="452px"  src="{{asset('assets/images/loader.gif')}}" />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card">
                        <div class="card-body">
                            <div id="audience-bar-chart-{{$key}}">
                              <div class="loader " id="progress">
                                <div class="spinner text-center" style="align-items: center;">
                                  <img height="452px"  src="{{asset('assets/images/loader.gif')}}" />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                  </div>
              </div>
              <?php $j++; ?>
              @endforeach
          </div>
      </div>
  </div>
  <div class="p-3 card">
    <ul class="nav nav-pills nav-fill row" id="pills-tab" role="tablist">
      @if(count($metric_option)>0)
      <?php $i=1;?>
      @foreach($metric_option as $key => $val)
        <li class="nav-item col-md-3" role="presentation">
          @if($i==1)
          <div class="card nav-link audience-card-metrics active" id="{{$key}}" data-bs-toggle="pill"
                data-bs-target="#channel-chart-{{$i}}"  type="button">
          @else
          <div class="card nav-link audience-card-metrics" id="{{$key}}" data-bs-toggle="pill"
                data-bs-target="#channel-chart-{{$i}}" data-id="{{$key}}" type="button">
          @endif
            <div class="card-body px-lg-5">
              <h4 id="audinece_metric_data_{{$key}}">0</h4>
              <h5>{{$val}}</h5>
            </div>
          </div>
            
        </li>
      <?php $i++;?>
      @endforeach
      @endif
    </ul>
  </div>
 
</div>

<div id="share_audience_report" class="modal fade" tabindex="-1" aria-labelledby="exampleModalPopoversLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalPopoversLabel">{{__('Share Report')}}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form method="POST" action="{{ route('save-share-setting','audience') }}"  enctype="multipart/form-data"> @csrf
                  <div class="modal-body">
                      <div class="row">
                          <input type="hidden" name="share_site"id="share_site">

                          @foreach($dimension_option as $key => $dimension)
                            <div class="form-group col-md-4">
                                <label for="name" class="col-form-label"> {{$dimension}} </label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input input-primary"
                                        name="{{$key}}" id="{{$key}}">
                                    <label class="form-check-label" for="{{$key}}"></label>
                                </div>
                            </div>
                          @endforeach
                          
                          <div class="form-group col-md-2">
                              <label for="name" class="col-form-label"> {{ __('Password Protected') }} </label>
                              <div class="form-check form-switch">
                                  <input onclick="password_status()" type="checkbox" class="form-check-input input-primary"name="is_password" id="is_password">
                                  <label class="form-check-label" for="is_password"></label>
                              </div>
                          </div>
                          <div class="form-group col-md-12">
                              
                              <div style="display: none" id="password-box">
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
                  </div>
                   
                  <div class="modal-footer">
                      <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                      <button type="submit" title="{{__('Copy')}}" class="btn  btn-primary" >{{__('Save changes')}}</button>
                  </div>
              </form>
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
<script type="text/javascript">
  $(function (){      
        get_audience_data();
        if ($('.audience-card-metrics').length > 0) {
            $('.audience-card-metrics').on('click', function (e) {
                e.preventDefault();
               
                analytics_chart($(this).attr('id'),"audience");
            });
        }
    });
  
</script>
@else
<div class="col-md-12" style="height: 200px; ">
      <div class="alert alert-primary alert-dismissible fade show text-center" role="alert">
              {{__('No Data Found !')}}
             
            </div>
    </div>
@endif
@endsection