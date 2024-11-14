@extends('layouts.admin')

@section('page-title') {{__('User Profile')}} @endsection

@push('script-page')
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Profile')}}</li>
@endsection

@section('content')
<div class="row">
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top" style="top:30px">
                    <div class="list-group list-group-flush" id="useradd-sidenav">
                        <a href="#useradd-1" class="list-group-item list-group-item-action border-0">{{ __('Personal Info') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        <a href="#useradd-2" class="list-group-item list-group-item-action border-0">{{__('Change Password')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                    </div>
                </div>
            </div>


            <div class="col-xl-9">
                <div id="useradd-1">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Personal Information') }}</h5>
                            <small> {{__('Details about your personal information')}}</small>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{route('account.update')}}" enctype="multipart/form-data" class="needs-validation" novalidate> 
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label text-dark">{{__('Name')}}</label><x-required></x-required>
                                        <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" id="fullname" placeholder="{{ __('Enter Your Name') }}" value="{{ $user->name }}" required autocomplete="name">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="email" class="col-form-label text-dark">{{__('Email')}}</label><x-required></x-required>
                                        <input class="form-control @error('email') is-invalid @enderror" name="email" type="text" id="email" placeholder="{{ __('Enter Your Email Address') }}" value="{{ $user->email }}" required autocomplete="email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <div class="choose-files ">
                                            <label for="avatar">
                                                <div class=" bg-primary profile_update"> <i class="ti ti-upload px-1"></i>{{__('Choose file here')}}</div>
                                                <input style="margin-top: -40px;" type="file" class="form-control file" name="avatar" id="avatar" data-filename="profile_update">
                                            </label>
                                        </div>
                                    <span class="text-xs text-muted">{{ __('Please upload a valid image file. Size of image should not be more than 2MB.')}}</span>
                                        @error('avatar')
                                            <span class="invalid-feedback text-danger text-xs" role="alert">{{ $message }}</span>
                                        @enderror

                                    </div>

                                </div>
                                <div class="col-lg-12 text-end">
                                    <input type="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                </div>
                            </div>
                            </form>
                            @if($user->avatar!='')
                                <form action="{{route('delete.avatar')}}" method="post" id="delete_avatar">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                            @auth('web')
                            @endauth
                        </div>

                    </div>
                </div>

                <div id="useradd-2">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Change Password') }}</h5>
                            <small> {{__('Details about your account password change')}}</small>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{route('update.password')}}" class="needs-validation" novalidate>
                                @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{Form::label('old_password',__('Current Password'),['class'=>'col-form-label text-dark'])}}<x-required></x-required>
                                            <input class="form-control @error('old_password') is-invalid @enderror" name="old_password" type="password" id="old_password" required autocomplete="old_password" placeholder="{{ __('Enter Old Password') }}">
                                                @error('old_password')
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                                @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{Form::label('password',__('New Password'),['class'=>'col-form-label text-dark'])}}<x-required></x-required>
                                            <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" required autocomplete="new-password" id="password" placeholder="{{ __('Enter Your Password') }}">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong> </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{Form::label('confirm_password',__('Re-type New Password'),['class'=>'col-form-label text-dark'])}}
                                            {{Form::password('confirm_password',array('class'=>'form-control','placeholder'=>__('Enter Re-type New Password')))}}
                                            @error('confirm_password')
                                            <span class="invalid-confirm_password" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer pr-0">
                                {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>


@endsection
