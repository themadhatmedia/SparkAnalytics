@extends('mail.common')
@section('content')
    {{ __('Hello') }},<br>

    {{ __('Welcome to ') }}{{ config('app.name') }}<br>

    {{ __('Email') }} : {{ $user }}<br>

    {{ __('Password') }} : {{ $password }}<br>


    <a href="{{ env('APP_URL') }}">{{ env('APP_URL') }}</a><br>

    {{ __('Thanks') }},<br>
    {{ config('app.name') }}
@endsection