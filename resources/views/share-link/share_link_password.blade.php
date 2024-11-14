@extends('layouts.auth')
@section('page-title')
@endsection

@php
    $currantLang = basename(App::getLocale());
@endphp

@section('content')
    <div class="card">
        <div class="row align-items-center text-start">
            <div class="col-xl-6">
                <div class="card-body">
                    <div class="">
                        <h2 class="h3">{{ __('Password required') }}</h2>
                        <h6>{{ __('This document is password-protected. Please enter a password.') }}</h6>
                    </div>
                    <form method="POST" action="{{ route($route, $param) }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="">
                            <div class="form-group ">
                                <label class="form-control-label mt-2 mb-2">{{ __('Password') }}</label>
                                <div class="input-group input-group-merge">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    @if (isset($error) && $error != '')
                                        <strong class="text-danger pt-2">{{ $error }}</strong>
                                    @endisset
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit"
                                class="btn-login btn btn-primary btn-block mt-2">{{ __('Save') }}</button>
                        </div>
                    </div>
                    {{ Form::close() }}
            </div>
        </div>
        <div class="col-xl-6 img-card-side">
            <div class="auth-img-content">
                <img src="{{ asset('assets/images/auth/img-auth-3.svg') }}" alt="" class="img-fluid">
                <h3 class="text-white mb-4 mt-5">{{ __('Attention is the new currency') }}</h3>
                <p class="text-white">
                    {{ __("The more effortless the writing looks, the more effort the writer
                                            actually put into the process.") }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    // $(document).ready(function () {
    //     $(".error-msg").on('click', function () {
    //         $(".error-msg").removeClass('d-none');
    //     })
    // });
</script>
