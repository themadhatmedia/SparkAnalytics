@extends('layouts.admin')
@section('content')
@section('page-title')
    {{ __('Add Site') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page">{{ __('Add Site') }}</li>
@endsection
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Add Site') }}</h5>

            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('save-site') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="exampleFormControlSelect1">{{ __('Select Account Id') }}</label>
                        <select class="form-select" name="account_id" id="select_account_id" onchange="get_property()">
                            <option selected="" disabled="">{{ __('Select Account Id') }}</option>
                            @foreach ($account as $account_val)
                                <option value="{{ $account_val['id'] }}" data-id="{{ $account_val['name'] }}">
                                    {{ $account_val['id'] }} - {{ $account_val['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="site-name-div" style="display: none;">
                        <label class="form-label">{{ __('Name') }}:</label>
                        <input type="text" class="form-control" placeholder="Enter Name" name="site_name"
                            id="site_name">

                    </div>
                    <div class="form-group" id="select-property-div" style="display: none;">
                        <label class="form-label"
                            for="exampleFormControlSelect1">{{ __('Select Property Id') }}</label>
                        <select class="form-select" name="property_id" id="select_property_id">
                            <option selected="" disabled="">{{ __('Select Property Id') }} </option>
                        </select>
                    </div>
                    <div class="form-group" id="property-name-div" style="display: none;">
                        <label class="form-label">{{ __('Property Name') }}:</label>
                        <input type="text" class="form-control" placeholder="Enter Property Name"
                            name="property_name" id="property_name">
                    </div>

            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2">{{ __('Submit') }}</button>
                <button class="btn btn-secondary">{{ __('Clear') }}</button>
            </div>
            </form>
        </div>


    </div>
</div>

@endsection
