<?php
$avatar_path = \App\Models\Utility::get_file('avatars/');
$avatar = url($avatar_path) . '/';
?>

@extends('layouts.admin')
@section('page-title')
    @if (\Auth::user()->user_type == 'super admin')
        {{ __('Manage Companies') }}
    @else
        {{ __('Manage User') }}
    @endif
@endsection
@section('action-button')
    <div class="col-auto">
        <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal"data-bs-target="#create_user"
            data-size="lg" data-bs-whatever="Create New User">
            <span class="text-white">
                <i class="ti ti-plus" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create User') }}"></i>
            </span>
        </a>

        @if (Auth::user()->user_type == 'company')
            <a href="{{ route('userlog.index') }}" class="btn btn-sm btn-primary btn-icon m-1" data-url="" data-size="lg"
                data-bs-whatever="{{ __('UserlogDetail') }}"> <span class="text-white">
                    <i class="ti ti-user" data-bs-toggle="tooltip"
                        data-bs-original-title="{{ __('Userlog Detail') }}"></i></span>
            </a>
        @endif

    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    @if (\Auth::user()->user_type == 'super admin')
        <li class="breadcrumb-item" aria-current="page">{{ __('Companies') }}</li>
    @else
        <li class="breadcrumb-item" aria-current="page">{{ __('User') }}</li>
    @endif
@endsection
@section('content')
    @if (count($user) > 0)
        @if (\Auth::user()->user_type == 'super admin')
            @foreach ($user as $val)

                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div class="card text-center">
                        <div class="card-header border-0 pb-0">
                            <div class="col-6 text-center Id mb-2 d-flex">
                                <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" data-size="lg"
                                    data-bs-whatever="{{ __('Upgrade Plan') }}" data-title="{{ __('Upgrade Plan') }}"
                                    data-url="{{ route('users.change.plan', $val->id) }}">

                                    <span> {{ __('Upgrade Plan') }} </span>
                                </a>

                            </div>
                            <div class="card-header-right">
                                <div class="btn-group card-option">
                                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#edit_user" data-size="lg" onclick="edit_user(<?= $val->id ?>)">
                                            <i class="ti ti-edit"></i>
                                            <span> {{ __('Edit') }} </span>
                                        </a>
                                        <a href="{{ route('login.with.admin', $val->id) }}" class="dropdown-item">
                                            <i class="ti ti-replace py-1"></i>
                                            <span>{{ __('Login As Company') }}</span>
                                        </a>
                                        <a href="#" class="dropdown-item"
                                            data-bs-toggle="modal"data-bs-target="#reset_password" data-size="lg"
                                            data-title="{{ 'Reset Password' }}"
                                            onclick="reset_password(<?= $val->id ?>,'Reset Password')">
                                            <i class="ti ti-adjustments"></i>
                                            <span>{{ __('Reset Password') }}</span>
                                        </a>
                                        @if ($val->is_login_enable == 1)
                                            <a href="{{ route('user.login', \Crypt::encrypt($val->id)) }}"
                                                class="dropdown-item">
                                                <i class="ti ti-road-sign"></i>
                                                <span class="text-danger"> {{ __('Login Disable') }}</span>
                                            </a>
                                        @elseif ($val->is_login_enable == 0 && $val->password == null)
                                            <a href="#" class="dropdown-item"
                                                data-bs-toggle="modal"data-bs-target="#reset_password" data-size="lg"
                                                data-bs-whatever="{{ 'New Password' }}"
                                                onclick="reset_password(<?= $val->id ?> , 'New Password')">
                                                <i class="ti ti-road-sign"></i>
                                                <span class="text-success"> {{ __('Login Enable') }}</span>
                                            </a>
                                        @else
                                            <a href="{{ route('user.login', \Crypt::encrypt($val->id)) }}"
                                                class="dropdown-item">
                                                <i class="ti ti-road-sign"></i>
                                                <span class="text-success"> {{ __('Login Enable') }}</span>
                                            </a>
                                        @endif
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['delete-user', [$val->id]]]) !!}
                                        <a href="#" class="dropdown-item show_confirm"><i class="ti ti-trash"></i>
                                            <span>{{ __('Delete') }}</span>
                                        </a>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="avatar me-3">
                                <a href="{{ !empty($val->avatar) ? $avatar . $val->avatar : $avatar . 'avatar.png' }}"
                                    target="_blank">
                                    <img src="{{ !empty($val->avatar) ? $avatar . $val->avatar : $avatar . 'avatar.png' }}"
                                        class="img-user wid-80 rounded-circle">
                                </a>
                            </div>
                            <h4 class=" mt-4">
                                {{ $val->name }}
                            </h4>
                            <small>{{ $val->email }}</small>


                            <div class="mt-4">
                                <div class="row justify-content-between align-items-center">
                                    <div class="col-6 text-center">
                                        <span class="d-block font-bold mb-0">{{ $val->plan_name }}</span>
                                    </div>
                                    <div class="col-6 text-center Id mt-2 ">
                                        <a href="#" data-url="{{ route('company.info', $val->id) }}" data-size="lg"
                                            data-ajax-popup="true" class="btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal" data-title="{{ __('Company Info') }}"
                                            data-bs-whatever="{{ __('Company Info') }}">{{ __('AdminHub') }}</a>
                                    </div>


                                    <div class="col-12">
                                        <hr class="my-3">
                                    </div>
                                    <div class="col-12 text-center pb-2">

                                        <span class="text-dark text-xs">{{ __('Plan Expired') }} :
                                            <?= date('M d,Y', strtotime($val->plan_expire_date)) ?></span>

                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div class="h6 mb-0">{{ $val->site_count }}</div>
                                    <span class="text-sm text-muted">{{ __('Site') }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        @if (\Auth::user()->user_type != 'super admin')
            @foreach ($user as $val)
                <div class="col-xl-3">
                    <div class="card  text-center">
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex justify-content-between align-items-center   ">
                                <h6 class="mb-0">
                                    <div class="badge p-2 px-3 rounded bg-primary">{{ $val->user_type }}</div>
                                </h6>
                            </div>
                            <div class="card-header-right">
                                <div class="btn-group card-option">
                                    @if ($val->user_status == 1)
                                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="#" class="dropdown-item"
                                                data-bs-toggle="modal"data-bs-target="#edit_user" data-size="lg"
                                                onclick="edit_user(<?= $val->id ?>)"
                                                data-bs-whatever="{{ 'Edit User' }}">
                                                <i class="ti ti-edit"></i>
                                                <span data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Edit Users') }}">{{ __('Edit') }}</span>
                                            </a>

                                            <a href="{{ route('userlog.index', ['month' => '', 'user' => $val->id]) }}"
                                                class="dropdown-item" data-bs-toggle="tooltip"
                                                data-bs-original-title="{{ __('Loged Details') }}">
                                                <i class="ti ti-history"></i>
                                                <span class="ml-2">{{ __('Loged Details') }}</span></a>

                                            <a href="#" class="dropdown-item"
                                                data-bs-toggle="modal"data-bs-target="#reset_password" data-size="lg"
                                                data-title="{{ 'Reset Password' }}"
                                                onclick="reset_password(<?= $val->id ?>,'Reset Password')">
                                                <i class="ti ti-adjustments"></i>
                                                <span>{{ __('Reset Password') }}</span>
                                            </a>
                                            @if ($val->is_login_enable == 1)
                                                <a href="{{ route('user.login', \Crypt::encrypt($val->id)) }}"
                                                    class="dropdown-item">
                                                    <i class="ti ti-road-sign"></i>
                                                    <span class="text-danger"> {{ __('Login Disable') }}</span>
                                                </a>
                                            @elseif ($val->is_login_enable == 0 && $val->password == null)
                                                <a href="#" class="dropdown-item"
                                                    data-bs-toggle="modal"data-bs-target="#reset_password" data-size="lg"
                                                    data-title="{{ 'New Password' }}"
                                                    onclick="reset_password(<?= $val->id ?>,'New Password')">
                                                    <i class="ti ti-road-sign"></i>
                                                    <span class="text-success"> {{ __('Login Enable') }}</span>
                                                </a>
                                            @else
                                                <a href="{{ route('user.login', \Crypt::encrypt($val->id)) }}"
                                                    class="dropdown-item">
                                                    <i class="ti ti-road-sign"></i>
                                                    <span class="text-success"> {{ __('Login Enable') }}</span>
                                                </a>
                                            @endif
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['delete-user', [$val->id]]]) !!}
                                            <a href="#" class="dropdown-item show_confirm" data-bs-toggle="tooltip"
                                                data-bs-original-title="{{ __('Delete User') }}"><i
                                                    class="ti ti-trash"></i>
                                                <span>{{ __('Delete') }}</span>
                                            </a>
                                            {!! Form::close() !!}
                                        </div>
                                    @else
                                        <a href="#" class="action-item px-2"><i class="ti ti-lock"></i></a>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="avatar my-4">
                                <a
                                    href="{{ !empty($val->avatar) ? $avatar . $val->avatar : $avatar . 'avatar.png' }}"target="_blank">
                                    <img src="{{ !empty($val->avatar) ? $avatar . $val->avatar : $avatar . 'avatar.png' }}"
                                        class="img-user wid-100 rounded-circle">
                                </a>
                            </div>
                            <h4 class="mt-2 text-primary">
                                <a href="#" class="text-title">{{ $val->name }}</a>
                            </h4>
                            <span>{{ $val->email }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    @endif
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <a href="#" class="btn-addnew-project " data-bs-toggle="modal" data-bs-target="#create_user"
            data-bs-whatever="Create New User">
            <div class="bg-primary proj-add-icon">
                <i class="ti ti-plus" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create User') }}"></i>
            </div>
            <h6 class="mt-4 mb-2">{{ __('New User') }}</h6>
            <p class="text-muted text-center">{{ __('Click here to add new user') }}</p>
        </a>
    </div>
    <div class="modal fade " id="create_user" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog moda">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="myLargeModalLabel">{{ __('Create New User') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('save-user') }}" class="needs-validation" novalidate> @csrf
                    <div class="modal-body">
                        <div class="form-group" id="site-name-div">
                            <label class="form-label">{{ __('Name') }}</label><x-required></x-required>
                            <input type="text" class="form-control" required placeholder="{{ __('Enter Name') }}"
                                name="name" id="name">

                        </div>

                        <div class="form-group" id="property-name-div">
                            <label class="form-label">{{ __('Email') }}</label><x-required></x-required>
                            <input type="text" class="form-control" required placeholder="{{ __('Enter Email') }}"
                                name="email" id="email">
                        </div>
                        @if (\Auth::user()->user_type != 'super admin')
                            <div class="form-group" id="view-name-div">
                                <label class="form-label">{{ __('Role') }}</label><x-required></x-required>
                                <select class="form-control" name="role" id=role required> 
                                    <option disabled="" value="" selected="">{{ __('Select role') }}</option>
                                    @foreach ($role as $val)
                                        <option value="{{ $val->name }}">{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                             
                            
                        @endif
                        <div class="col-md-5 mb-3">
                            <label for="password_switch">{{ __('Login is enable') }}</label>
                            <div class="form-check form-switch custom-switch-v1 float-end">
                                <input type="checkbox" name="password_switch"
                                    class="form-check-input input-primary pointer" value="on" id="password_switch">
                                <label class="form-check-label" for="password_switch"></label>
                            </div>
                        </div>
                        <div class="col-md-12 ps_div d-none">
                            <div class="form-group">
                                {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}
                                {{ Form::password('password', ['class' => 'form-control', 'placeholder' => __('Enter Password'), 'minlength' => '6']) }}
                                @error('password')
                                    <small class="invalid-password" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn  btn-light"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button class="btn btn-primary me-2">{{ __('Create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade " id="reset_password" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog moda">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="myLargeModalLabel">{{ __('Reset password') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('reset.password') }}" class="needs-validation" novalidate> @csrf
                    <div class="modal-body">

                        <input autocomplete="" type="hidden" name="resete_id" id="resete_id">
                        <div class="form-group" id="view-name-div">
                            <label class="form-label">{{ __('Password') }}</label><x-required></x-required>
                            <input autocomplete="" type="password" class="form-control"
                                placeholder="{{ __('Enter Password') }}" required name="password" id="new_password">
                        </div>
                        <div class="form-group" id="view-name-div">
                            <label class="form-label">{{ __('Confirm Password') }}</label><x-required></x-required>
                            <input autocomplete="" type="password" class="form-control"
                                placeholder="{{ __('Enter Confirm Password') }}" required name="confirm_password"
                                id="confirm_password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-light"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button class="btn btn-primary me-2">{{ __('Update') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    </div>

    <div class="modal fade " id="edit_user" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog moda">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="myLargeModalLabel">{{ __('Edit User') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('update-user') }}" class="needs-validation" novalidate> @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="user_id">
                        <div class="form-group" id="site-name-div">
                            <label class="form-label">{{ __('Name') }}</label><x-required></x-required>
                            <input type="text" class="form-control" required placeholder="{{ __('Enter Name') }}"
                                name="name" id="edit_name">

                        </div>

                        <div class="form-group" id="property-name-div">
                            <label class="form-label">{{ __('Email') }}</label><x-required></x-required>
                            <input type="text" class="form-control" required placeholder="{{ __('Enter Email') }}"
                                name="email" id="edit_email">
                        </div>


                        @if (\Auth::user()->user_type == 'company')
                            <div class="form-group" id="view-name-div">
                                <label class="form-label">{{ __('Role') }}</label><x-required></x-required>
                                <select class="form-control" name="role" id=edit_role>
                                    <option disabled="" value="" selected="">{{ __('Select role') }}</option>
                                    @foreach ($role as $val)
                                        <option value="{{ $val->id }}">{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-light"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button class="btn  btn-primary">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    {{-- Password  --}}
    <script>
        $(document).on('change', '#password_switch', function() {
            if ($(this).is(':checked')) {
                $('.ps_div').removeClass('d-none');
                $('#password').attr("required", true);

            } else {
                $('.ps_div').addClass('d-none');
                $('#password').val(null);
                $('#password').removeAttr("required");
            }
        });
        $(document).on('click', '.login_enable', function() {
            setTimeout(function() {
                $('.modal-body').append($('<input>', {
                    type: 'hidden',
                    val: 'true',
                    name: 'login_enable'
                }));
            }, 2000);
        });
    </script>
@endsection
