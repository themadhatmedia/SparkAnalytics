@extends('layouts.share-link')
@section('breadcrumb')
   
    <li class="breadcrumb-item" id="current_site" data-siteid="{{$data->id}}">{{$data->site_name}}</li> 
@endsection
@section('lang-section')
@foreach($languages as $language)

    <a class="dropdown-item @if($language == $currantLang) text-danger @endif" href="{{route('site.dashboard.link',[\Illuminate\Support\Facades\Crypt::encrypt($data->id),"standard",$language])}}">{{\Str::upper($language)}}</a>@endforeach
@endsection

@section('content')
 @if($data) 
    <div class="col-sm-12 ">
        @if($json->new_user_report==1)
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
                                            <h6 class="mb-3">{{__('Total New Visitors')}}</h6>
                                            <h5 id="total_visitor_week">0</h5>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="mb-3">{{__('Total Returning Visitor')}}</h6>
                                            <h5 id="total_returning_visitor_week">0</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                   <div   id="user-timeline-chart-week">
                                        <div class="loader " id="progress">
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
                                            <h6 class="mb-3">{{__('Total New Visitors')}}</h6>
                                            <h5 id="total_visitor_15daysago">0</h5>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="mb-3">{{__('Total Returning Visitor')}}</h6>
                                            <h5 id="total_returning_visitor_15daysago">0</h5>
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
                                            <h6 class="mb-3">{{__('Total New Visitors')}}</h6>
                                            <h5 id="total_visitor_year">0</h5>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="mb-3">{{__('Total Returning Visitor')}}</h6>
                                            <h5 id="total_returning_visitor_year">0</h5>
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
        @endif
        <div class="row">
            @if($json->user_report==1)
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
            @endif
            @if($json->bounce_rate_report==1)
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
            @endif
            @if($json->session_duration_report==1)
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
            @endif
        </div>
        <div class="row">
            @if($json->user_location_report==1)
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
             @endif
             @if($json->live_user_report==1)
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
            @endif
        </div>
        <div class="row ">
            @if($json->page_report==1)
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
                                    <th scope="col">{{__('Active Users')}}</th>
                                    <th scope="col">{{__('% New Sessions')}}</th>
                                </tr>
                                </thead>
                                <tbody id="active_pages"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> 
            @endif
            @if($json->device_report==1)
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
            @endif
        </div>
    </div>
    @else
    <div class="col-md-12" style="height: 200px; ">
        <div class="alert alert-primary alert-dismissible fade show text-center" role="alert">
            {{__('No Data Found !')}}
        </div>
    </div>
 @endif

@endsection
