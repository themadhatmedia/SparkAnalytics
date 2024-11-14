@extends('layouts.admin')
@if (\Auth::user()->is_json_upload ==1 && count($site)>0)
@section('action-button')
<div class="d-flex align-items-center flex-wrap gap-3">
    <div class="select-box">
      <select class="form-select" name="site_name" id="site-list" onchange="dash_site_detail()"> 
      	@foreach($site as $val) 
      		<option value="{{$val->id}}" data-site="{{$val->site_name}}">{{$val->site_name}}</option> 
      	@if ($loop->last)
            <script>
                $(document).ready(function() {
            $('select[name="site_name"]').val('{{$val->id}}').attr("data-siteid",'{{$val->site_name}}').trigger('change');
            });
            </script>
        @endif
      	@endforeach 
      </select>
    </div>
    @if(\Auth::user()->can('create site'))
    <div class="btn p-0">
      <a class="btn btn-primary" href="{{ route('add-site') }}" data-bs-toggle="tooltip" data-bs-original-title="{{__('Add Site')}}"><span><i class="ti ti-plus">
        </i></span>{{__('Add Site')}}</a>
    </div>
    @endif
    @if (count($site)>0)
        @if(\Auth::user()->can('manage share report settings'))
            <div class="btn p-0">
              <a class="btn btn-primary" onclick="share_setting('dashboard')" data-bs-toggle="modal"  data-bs-target="#share_dash_report">
                <span class="btn-inner--icon"><i class="ti ti-settings text-white" data-bs-toggle="tooltip" data-bs-original-title="{{__('Share Report Setting')}}"></i></span></a>
            </div>
        @endif
        @if(\Auth::user()->can('manage share report'))
            <div class="link" data-bs-toggle="tooltip" data-bs-original-title="{{__('Share Report')}}"></div>
        @endif
         <a href="#" class="btn btn-primary" onclick="saveAsPDF('dashboard page')"data-bs-toggle="tooltip" title="{{__('Download')}}" data-original-title="{{__('Download')}}">
            <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
        </a>
    @endif
  </div>
@endsection
@endif
@section('page-title')
    {{ __('Dashboard') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item" id="current_site" data-siteid=""></li>
@endsection
@section('content')
	@if (\Auth::user()->user_type == 'company' || \Auth::user()->user_type != 'super admin')
    @if(\Auth::user()->can('manage dashboard'))
	@if (count($site)==0 && \Auth::user()->can('create site'))
	
		<div class="col-sm-12">
            <div class="row">
                <div class="col-lg-4 col-6">
                	@if (\Auth::user()->is_json_upload ==1)
                    <a href="{{ route('add-site') }}">
                    @else
                    <a data-bs-toggle="modal" data-bs-target="#json_upload">
                    @endif
                	<div class="card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-auto">
	                            <div class="theme-avtar bg-primary">
	                                <i class="ti ti-plus"></i>
	                            </div>
                          	</div>
                            <div class="col">
                              <h5 class="mb-3">{{__('Add New Site')}}</h5>
                            </div>
                          </div>
                        </div>
                    </div>
                  	</a>
                </div>
            </div>
		</div>
	@else
   
        <div class="col-sm-12 " >
            <div class="card">
                <div class="card-header row">
                    <div class="col">
                        <h5>{{__('Visitor')}}</h5>
                    </div>
                    <div class="col">
                        <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="week-chart" data-bs-toggle="pill" data-bs-target="#timeline-chart-week" type="button">{{__('Week')}}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="15daysago-chart" data-bs-toggle="pill" data-bs-target="#timeline-chart-month" type="button">{{__('Month')}}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="year-chart" data-bs-toggle="pill" data-bs-target="#timeline-chart-year" type="button">{{__('Year')}}</button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="timeline-chart-week" role="tabpanel" aria-labelledby="pills-user-tab-1">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="mb-3">{{__('Total New Users')}}</h6>
                                            <h5 id="total_New_Users_week">0</h5>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="mb-3">{{__('Total Active Users')}}</h6>
                                            <h5 id="total_Active_Users_week">0</h5>
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-md-10">
                                   <div id="user-timeline-chart-week">
                                        <div class="loader" id="progress">
                                      <div class="spinner text-center" style="align-items: center;">
                                        <img height="320px"  src="{{asset('assets/images/loader.gif')}}" />
                                      </div>
                                    </div>
                                   </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="tab-pane fade" id="timeline-chart-month" role="tabpanel" aria-labelledby="pills-user-tab-2">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="mb-3">{{__('Total New Users')}}</h6>
                                            <h5 id="total_New_Users_15daysago">0</h5>
                                        </div>
                                    </div>
                                     <div class="card">
                                        <div class="card-body">
                                            <h6 class="mb-3">{{__('Total Active Users')}}</h6>
                                            <h5 id="total_Active_Users_15daysago">0</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                   <div   id="user-timeline-chart-month">
                                    <div class="loader " id="progress">
                                      <div class="spinner text-center" style="align-items: center;">
                                        <img height="264px"  src="{{asset('assets/images/loader.gif')}}" />
                                      </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="timeline-chart-year" role="tabpanel" aria-labelledby="pills-user-tab-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="mb-3">{{__('Total New Users')}}</h6>
                                            <h5 id="total_New_Users_year">0</h5>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="mb-3">{{__('Total Active Users')}}</h6>
                                            <h5 id="total_Active_Users_year">0</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                   <div   id="user-timeline-chart-year">
                                        <div class="loader " id="progress">
                                      <div class="spinner text-center" style="align-items: center;">
                                        <img height="264px"  src="{{asset('assets/images/loader.gif')}}" />
                                      </div>
                                    </div>
                                   </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{__('Users')}}</h5>
                        </div>
                        <div class="card-body" style="padding: 0px!important">
                            <div id="usersChart">
                                <div class="loader " id="progress">
                                  <div class="spinner text-center" style="align-items: center;">
                                    <img height="264px"  src="{{asset('assets/images/loader.gif')}}" />
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{__('Bounce Rate')}}</h5>
                        </div>
                        <div class="card-body" style="padding: 0px!important">
                            <div id="bounceRateChart">
                                <div class="loader " id="progress">
                                  <div class="spinner text-center" style="align-items: center;">
                                    <img height="264px"  src="{{asset('assets/images/loader.gif')}}" />
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{__('Session Duration')}}</h5>
                        </div>
                        <div class="card-body" style="padding: 0px!important">
                            <div id="sessionDuration">
                                <div class="loader " id="progress">
                                  <div class="spinner text-center" style="align-items: center;">
                                    <img height="264px"  src="{{asset('assets/images/loader.gif')}}" />
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-9">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{__('New Users by Location')}}</h5>
                        </div>
                        <div class="card-body" >
                                <div class="mapcontainer" id="mapcontainer" style="height: 350px"> 
                                </div>
                        </div>
                    </div>
                </div> 
                <div class="col-xxl-3">
                    <div class="card bg-primary">
                        <div class="card-header">
                            <h5 class="text-light">{{__('Live Active Users')}}</h5>
                        </div>
                        <div class="card-body text-light" style="text-align:  center!important;">
                          <div class="display-2">
                            <i class="ti ti-antenna-bars-5"> </i>
                          </div>
                          <div class="display-6" id="live_users">0</div>
                            <h5 class="text-light">{{__('Active Users')}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-xxl-7 d-flex">
                    <div class="card w-100 active-page-table">
                        <div class="card-header">
                            <h5>{{__('Top Active Pages')}}</h5>
                        </div>
                        <div class="card-body" >
                           <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">{{__('Active Page')}}</th>
                                        <th scope="col">{{__('screenPageViews')}}</th>
                                        <th scope="col">{{__('screenPageViewsPerUser')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="active_pages"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="col-xxl-5 d-flex">
                    <div class="card w-100">
                        <div class="card-header">
                            <h5>{{__('Sessions by device')}}</h5>
                        </div>
                        <div class="card-body" style="text-align: center!important;padding: 23px 0px!important" >
                          <div id="session_by_device">
                               <div class="loader " id="progress">
                                  <div class="spinner text-center" style="align-items: center;">
                                    <img height="500px"  src="{{asset('assets/images/loader.gif')}}" />
                                  </div>
                                </div>
                          </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <div id="share_dash_report" class="modal fade" tabindex="-1" aria-labelledby="exampleModalPopoversLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalPopoversLabel">{{__('Share Report')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('save-share-setting','dashboard') }}"  enctype="multipart/form-data"> @csrf
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="share_site"id="share_site">
                                 <div class="form-group col-md-4">
                                    <label for="name" class="col-form-label"> {{ __('New User & Returning User report') }} </label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input input-primary"
                                            name="new_user_report" id="new_user_report">
                                        <label class="form-check-label" for="new_user_report"></label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="name" class="col-form-label"> {{ __('User report') }} </label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input input-primary"
                                            name="user_report" id="user_report">
                                        <label class="form-check-label" for="user_report"></label>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="name" class="col-form-label"> {{ __('Bounce Rate report') }} </label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input input-primary"
                                            name="bounce_rate_report" id="bounce_rate_report">
                                        <label class="form-check-label" for="bounce_rate_report"></label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="name" class="col-form-label"> {{ __('Session Duration report') }} </label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input input-primary"
                                            name="session_duration_report" id="session_duration_report">
                                        <label class="form-check-label" for="session_duration_report"></label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="name" class="col-form-label"> {{ __('New Users by Location report') }} </label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input input-primary"
                                            name="user_location_report" id="user_location_report">
                                        <label class="form-check-label" for="user_location_report"></label>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="name" class="col-form-label"> {{ __('Live Active Users report') }} </label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input input-primary"
                                            name="live_user_report" id="live_user_report">
                                        <label class="form-check-label" for="live_user_report"></label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="name" class="col-form-label"> {{ __('Top Active Pages report') }} </label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input input-primary"
                                            name="page_report" id="page_report">
                                        <label class="form-check-label" for="page_report"></label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="name" class="col-form-label"> {{ __('Sessions by device report') }} </label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input input-primary"
                                            name="device_report" id="device_report">
                                        <label class="form-check-label" for="device_report"></label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
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
                            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" title="{{__('Copy')}}" class="btn  btn-primary" >{{__('Save changes')}}</button>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    
        
	@endif 
    
    <div class="col-xl-4 col-md-6">
        <div id="json_upload" class="modal fade" tabindex="-1" aria-labelledby="exampleModalPopoversLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalPopoversLabel">{{__('JSON Update')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('save-json') }}"  enctype="multipart/form-data" class="needs-validation" novalidate> 
                        @csrf
                        <div class="modal-body">
                            <div class="form-group col-md-12">
                                <label for="json_file" class="col-form-label">{{ __('Name') }}</label><x-required></x-required>
                                <input type="file" class="form-control" id="json_file" name="json_file" required />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn  btn-primary">{{__('Save changes')}}</button>
                           
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
	@endif
	@if (\Auth::user()->user_type == 'super admin')

		<div class="col-sm-12">
		    <div class="row">
		        <div class="col-xxl-7 ">
		            <div class="row">
		                <div class="col-lg-4 col-12">
		                    <div class="card">
		                        <div class="card-body">
		                            <div class="theme-avtar bg-primary">
		                                <i class="ti ti-home"></i>
		                            </div>
		                            <p class="text-muted text-sm mt-4 mb-2">{{__('Paid User')}}  : {{$totalPaidUsers}}</p>
		                            <h6 class="mb-3">{{__('Total Users')}}</h6>
		                            <h3 class="mb-0">{{$data}}<span class="text-success text-sm"></h3>
		                        </div>
		                    </div>
		                </div>
                        <div class="col-lg-4 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-info">
                                        <i class="ti ti-click"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{__('Order Amount')}}  :  {{$currency}}{{$totalOrderAmount}}</p>
                                    <h6 class="mb-3">{{__('Order')}}</h6>
                                    <h3 class="mb-0">{{$Order}}<span class="text-success text-sm"></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-warning">
                                        <i class="ti ti-report-money"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">
                                        {{__('Most purchase plan')}} :
                                        @if ($mostPlans)
                                            {{ $mostPlans->name }}
                                        @else
                                            -
                                        @endif 
                                    </p>
                                    <h6 class="mb-3">{{__('Plan')}}</h6>
                                    <h3 class="mb-0">{{$plan}}<span class="text-success text-sm"></h3>
                                </div>
                            </div>
                        </div>
		            </div>
		        </div>
                <div class="col-xxl-5">
                    <div class="card">
                        <div class="card-header">
                            <h5> {{ __('Recent Orders') }}</h5>
                        </div>
                        <div class="card-body">
                            <div id="traffic-chart1"></div>
                        </div>
                    </div>

                </div>
		    </div>
		</div>

	@endif
@if (\Auth::user()->user_type != 'super admin' && count($site)>=0 && \Auth::user()->can('manage dashboard'))

  <script type="text/javascript">
    $(function () {
        // dash_site_detail();
       
    });  
    
  </script>
 @push('script-page')
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

<link rel="stylesheet" href="{{asset('assets/vendor/jvectormap-next/jquery-jvectormap.css')}}">
<script src="{{asset('assets/vendor/jvectormap-next/jquery-jvectormap.min.js')}}"></script>
<script src="{{asset('assets/vendor/jvectormap-next/jquery-jvectormap-world-mill.js')}}"></script>
<script src="{{asset('assets/vendor/chart.js/dist/Chart.min.js')}}"></script>
<script src="{{asset('assets/vendor/chart.js/dist/Chart.extension.js')}}"></script>
<script type="text/javascript" src="{{asset('js/loader.js')}}"></script>
<script>
    function map_chart(data)
    {
         
          google.charts.load('current', {
            'packages':['geochart'],
          });
          google.charts.setOnLoadCallback(drawRegionsMap);
                var map_data = [['Country', 'Popularity']];
                $.each(data, function(k, val) {
                    map_data[k+1]=val;
                });
          
          function drawRegionsMap() {
              var data = google.visualization.arrayToDataTable(map_data);

            var options = {};

            var chart = new google.visualization.GeoChart(document.getElementById('mapcontainer'));

            chart.draw(data, options);
          } 
    }
</script>
    @endpush

    @endif
   
     @if (Auth::user()->user_type == 'super admin')
     <script src="{{asset('assets/js/plugins/apexcharts.min.js')}}"></script>
        <script>
            (function() {
                var options = {
                    chart: {
                        height: 200,
                        type: 'area',
                        toolbar: {
                            show: false,
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 2,
                        curve: 'smooth'
                    },
                    series: [{
                        name: '{{ __('Order') }}    ',
                        data: {!! json_encode($chartData['data']) !!}
                    }, ],
                    xaxis: {
                        categories: {!! json_encode($chartData['label']) !!},
                    },
                    colors: ['#ffa21d'],

                    grid: {
                        strokeDashArray: 4,
                    },
                    legend: {
                        show: false,
                    },

                };
                var chart = new ApexCharts(document.querySelector("#traffic-chart1"), options);
                chart.render();
            })();
        </script>
        @endif
@endsection