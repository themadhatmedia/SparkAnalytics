
@extends('layouts.admin')
@push('scripts')
@endpush
@section('page-title')
    {{__('Alert')}}
@endsection
@section('title')
    {{__('Alert')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Home')}}</a></li>
    <li class="breadcrumb-item">{{__('Alert')}}</li>
@endsection
@section('action-button')
<div class="d-flex align-items-center flex-wrap gap-3">
    
    <div class="">
      <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#add_alerts_modal" ><span>
        <i class="ti ti-plus" data-bs-toggle="tooltip" data-bs-original-title="{{__('Create Alert')}}"></i></span></button>
    </div>
</div>
@endsection
@section('content')
<div class="col-sm-12">
    <div class="card">
        <div class="card-body table-bAletr-style">
           
            <div class="table-responsive overflow_hidden">
                <table id="pc-dt-simple" class="table datatable align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="name"> {{__('Id')}}</th>
                            <th scope="col">{{__('Title')}}</th>
                            <th scope="col">{{__('Site Name')}}</th>
                            <th scope="col" class="sort" data-sort="completion"> {{__('Metric')}}</th>
                            <th scope="col">{{__('Description')}}</th>
                            <th scope="col" class="sort" data-sort="completion"> {{__('Duration')}}</th>
                            <th scope="col" class="sort" data-sort="completion"> {{__('Email Notification')}}</th>
                            <th scope="col" class="sort" data-sort="completion"> {{__('Slack Notification')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $val)
                            <tr>
                                <td>{{$val->id}}</td>
                                <td>{{$val->title}}</td>
                                <td>{{$val->site->site_name}}</td>
                                <td>{{$val->metric}}</td>
                                <td>{{$val->description}}</td>
                                <td>{{$val->duration}}</td>
                                <td>
                                    @if($val->email_notification == '1')
                                       {{__('On')}}
                                    @else
                                         {{__('Off')}}
                                    @endif
                                </td>
                                <td>
                                    @if($val->slack_notification == '1')
                                       {{__('On')}}
                                    @else
                                         {{__('Off')}}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="add_alerts_modal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalPopoversLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalPopoversLabel">{{__('Add Alert')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('save-aletr') }}"  enctype="multipart/form-data" class="needs-validation" novalidate> @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="title" class="col-form-label">{{ __('Alert Title') }}</label><x-required></x-required>
                            <div class="form-icon-user">
                                <input class="form-control" type="text"  id="title"name="title" placeholder="{{ __('Alert Title') }}" required>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="site_id" class="col-form-label"> {{ __('Site') }} </label>
                            <select class="form-select" name="site_id" id="site_id">
                                <option selected="" disabled="">{{__('Select Site')}}</option>
                                @foreach($site as $val) 
                                    <option value="{{$val->id}}">{{$val->site_name}}</option>
                                @endforeach
                                
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="metric" class="col-form-label"> {{ __('Metric') }} </label>
                            <select class="form-select" name="metric" id="metric">
                                <option selected="" disabled="">{{__('Select Metrics')}}</option>
                               @foreach($metric_option as $key => $val) <option value="{{$key}}">{{$val}}</option> @endforeach
                            </select>
                        </div>
                         <div class="form-group col-md-3">
                            <label for="email_notification" class="col-form-label"> {{ __('Email Nofication') }} </label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input input-primary"name="email_notification" id="email_notification">
                                <label class="form-check-label" for="email_notification"></label>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="slack_notification" class="col-form-label"> {{ __('Slack notification') }} </label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input input-primary"
                                    name="slack_notification" id="slack_notification">
                                <label class="form-check-label" for="slack_notification"></label>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="name" class="col-form-label"> {{ __('Frequency of alerts') }} </label> 
                            <select class="form-select" name="duration" id="duration">
                                <option selected="" disabled="">{{__('Select Duration')}}</option>
                                <option value="daily">{{__('Daily')}}</option>
                                <option value="weekly">{{__('Weekly')}}</option>
                                <option value="monthly">{{__('Monthly')}}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12 mb-0">
                            <div class="form-group">
                                <label for="description" class="col-form-label">{{__('Description')}}</label>
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>
                        </div>
                        
                    </div>
                </div>
                 
                <div class="modal-footer">
                    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" title="{{__('Copy')}}" class="btn  btn-primary" >{{__('Create')}}</button>
                    
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

