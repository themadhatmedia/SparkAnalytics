@extends('layouts.share-link')
@section('breadcrumb')
   
    <li class="breadcrumb-item" id="current_site" data-siteid="{{$data->id}}">{{$data->site_name}}</li> 
@endsection
@section('lang-section')
@foreach($languages as $language)
    <a class="dropdown-item @if($language == $currantLang) text-danger @endif" href="{{route('site.analyse.link',[\Illuminate\Support\Facades\Crypt::encrypt($data->id),\Illuminate\Support\Facades\Crypt::encrypt($type),$language])}}">{{\Str::upper($language)}}</a>
@endforeach
@endsection

@section('content')
@if($data)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                       
                        <div class="col-md-10 ">
                          <div class=" ">
                              <ul class="nav nav-pills nav-fill row" id="pills-tab" role="tablist">
                                  @php $i=0; @endphp
                                  @foreach($dimension_option as $key => $dimension)
                                  @if($json->$key==1)
                                  <li class="nav-item col-sm-1" role="presentation">
                                      <button class="nav-link audience-analytics {{ ($i == 0 ) ? ' active' : '' }} " id="pills-tab-{{$i}} " id="pills-tab-1" onclick="get_audience_data()" data-bs-toggle="pill" data-value="{{$key}}"
                                          data-bs-target="#" type="button">{{$dimension}}</button>   
                                  </li>
                                  @endif
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
    </div>
    <div class="row">
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
                                 <div class="form-group col-md-2">
                                    <label for="name" class="col-form-label"> {{ __('Region') }} </label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input input-primary"
                                            name="region" id="region">
                                        <label class="form-check-label" for="region"></label>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="name" class="col-form-label"> {{ __('Audience') }} </label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input input-primary"
                                            name="organic_search" id="organic_search">
                                        <label class="form-check-label" for="organic_search"></label>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="name" class="col-form-label"> {{ __('Paid Search') }} </label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input input-primary"
                                            name="paid_search" id="paid_search">
                                        <label class="form-check-label" for="paid_search"></label>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="name" class="col-form-label"> {{ __('Bounce') }} </label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input input-primary"
                                            name="bounce" id="bounce">
                                        <label class="form-check-label" for="bounce"></label>
                                    </div>
                                </div>
                                
                                <div class="form-group col-md-2">
                                    <label for="name" class="col-form-label"> {{ __('Password Protected') }} </label>
                                    <div class="form-check form-switch">
                                        <input onclick="password_status()" type="checkbox" class="form-check-input input-primary"name="is_password" id="is_password">
                                        <label class="form-check-label" for="is_password"></label>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    
                                    <div style="display: none" id="password-box">
                                        <input type="text" class="form-control" placeholder="{{__('Enter Your Password')}}" name="password" id="password">
                                        <label class="form-check-label" for="password"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                         
                        <div class="modal-footer">
                            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" title="{{__('Copy')}}" class="btn  btn-primary" >{{__('Save changes')}}</button>
                            <div class="link"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<script type="text/javascript">
  $(function (){  
      var site = $('#current_site').attr('data-siteid');

        get_audience_data(site);
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
