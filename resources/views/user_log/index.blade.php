@php
    $avatar = \App\Models\Utility::get_file('avatars/');
@endphp

@extends('layouts.admin')
@section('page-title')
    {{ __('Manage User Logs') }}
@endsection

@section('action-button')
    
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('User Logs') }}</li>
@endsection
@section('content')

<div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
    <div class=" mt-2 " id="multiCollapseExample1" style="">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => ['userlog.index'], 'method' => 'get', 'id' => 'userlogin_filter']) }}
                <div class="d-flex align-items-center justify-content-end">
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                        <div class="btn-box">
                            {{ Form::label('select_month', __('Select Month'), ['class' => 'form-label']) }}
                            <input type="month" name="month" class="form-control" value="{{ isset($_GET['month']) ? $_GET['month'] : '' }}" placeholder ="">
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                        <div class="btn-box">
                            {{ Form::label('user', __('user'), ['class' => 'form-label']) }}
                            {{ Form::select('user', $usersList, isset($_GET['user']) ? $_GET['user'] : '', ['class' => 'form-control select ','id'=>'user_id']) }}
                        </div>
                    </div>
                    
                    <div class="col-auto float-end ms-2 mt-4">
                        <a href="#" class="btn btn-sm btn-primary"
                            onclick="document.getElementById('userlogin_filter').submit(); return false;"
                            data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                        </a>

                        <a href="{{ route('userlog.index') }}" class="btn btn-sm btn-danger"
                            data-bs-toggle="tooltip" title="" data-bs-original-title="Reset">
                            <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                        </a>
                    </div>
                </div>
                {{ Form::close()}}
            </div>
        </div>
    </div>
</div>

<div class="col-xl-12">
    <div class="card">
        <div class="card-header card-body table-border-style">
            <h5></h5>
            <div class="table-responsive">
                <table class="table" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th>{{ __('Name')}}</th>
                            <th>{{ __('Role')}}</th>
                            <th>{{ __('Ip')}}</th>
                            <th>{{ __('Last Login At')}}</th>
                            <th>{{ __('Country')}}</th>
                            <th>{{ __('Device Type')}}</th>
                            <th>{{ __('Os Name')}}</th>
                            <th>{{ __('Details')}}</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                                <tr>
                                @php
                                    $json = json_decode($user->details);
                                @endphp
                                
                                <td>{{ $user->user_name }}</td>
                                <td><span class="badge rounded p-2 m-1 px-3 bg-primary">{{ $user->type}}</span></td>
                                <td>{{ $user->ip}}</td>
                                <td>{{ $user->date}}</td>
                                <td>{{ isset($json->country) ? $json->country : ""}}</td>
                                <td>{{ isset($json->device_type) ? $json->device_type : ""}}</td>
                                <td>{{ isset($json->os_name)? $json->os_name :""}}</td>
                                <td> 
                                    <div class="action-btn bg-info ms-2">
                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                            data-bs-toggle="modal" data-size="lg" data-bs-target="#exampleModal"
                                            data-url="{{ route('userlog.view', [$user->id]) }}"
                                            data-bs-whatever="{{ __('View User Logs') }}" data-size="lg"> <span
                                                class="text-white"> <i class="ti ti-eye" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('View') }}"></i></span></a>
                                    </div>

                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['userlog.destroy', $user->id]]) !!}
                                        <a href="#!" class="mx-3 btn btn-sm align-items-center show_confirm ">
                                            <i class="ti ti-trash text-white" data-bs-toggle="tooltip"
                                                data-bs-original-title="{{ __('Delete') }}"></i>
                                        </a>
                                        {!! Form::close() !!}


                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
