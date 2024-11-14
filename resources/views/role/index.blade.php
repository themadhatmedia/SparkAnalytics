@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Roles') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Role') }}</li>
@endsection

@section('action-button')
    <div class="col-auto">
        <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal" data-bs-target="#exampleModal"
            data-url="{{ route('create.roles') }}" data-size="lg" data-bs-whatever="{{ __('Create New Role') }}"> <span
                class="text-white">
                <i class="ti ti-plus text-white" data-bs-toggle="tooltip"
                    data-bs-original-title="{{ __('Create Role') }}"></i></span>
        </a>
    </div>
@endsection

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Role') }} </th>
                                <th>{{ __('Permissions') }} </th>
                                <th width="200px">{{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td class="Role">{{ $role->name }}</td>
                                    <td class="Permission">
                                        @for ($j = 0; $j < count($role->permissions()->pluck('name')); $j++)
                                            <span
                                                class="badge rounded p-2 m-1 px-3 bg-primary">{{ $role->permissions()->pluck('name')[$j] }}</span>
                                        @endfor
                                    </td>
                                    <td class="Action">
                                        <span>
                                            @can('edit role')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="modal" data-size="lg" data-bs-target="#exampleModal"
                                                        data-url="{{ route('roles.edit', $role->id) }}"
                                                        data-bs-whatever="{{ __('Edit Role') }}"> <span class="text-white"> <i
                                                                class="ti ti-edit" data-bs-toggle="tooltip"
                                                                data-bs-original-title="{{ __('Edit Role') }}"></i></span></a>
                                                </div>
                                            @endcan
                                            @can('delete role')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id]]) !!}
                                                    <a href="#" class=" show_confirm">
                                                        <i class="ti ti-trash text-white" data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Delete Role') }}"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endcan
                                        </span>
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
