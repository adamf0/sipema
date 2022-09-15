@extends('layouts.auth')

@section('content')
    <div class="mb-3">
        <h1 class="h3 mb-3 fw-normal text-center">Lupa Password</h1>
        <hr>
    </div>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group row">
            <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>

            <div class="mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            {{ __('Send Password Reset Link') }}
        </button>
        <div class="text-center">
            <hr>
            <a href="{{ route('login') }}" rel="noopener noreferrer">atau Login</a>
        </div>
    </form>
@endsection
