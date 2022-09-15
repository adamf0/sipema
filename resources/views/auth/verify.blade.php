@extends('layouts.auth')

@section('content')
    <h1 class="h3 mb-3 fw-normal text-center">Verifikasi Alamat E-Mail</h1>
    <hr>
    @if (session('resent'))
        <div class="alert alert-success" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif

    {{ __('Before proceeding, please check your email for a verification link.') }}
    {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
@endsection
