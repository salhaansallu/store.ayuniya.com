@extends('layouts.app')

@section('content')

<style>
    body{
        background-color: #F8F8F8 !important;
    }
</style>

<div class="login_form">
    <div class="header">
        <h1>Reset password</h1>
    </div>
    <form action="{{ route('forget.password.post') }}" method="post">
        @csrf

        <div class="input">
            <div class="label">Email address <span>*</span></div>
            <div class="txt_field">
                <input type="email" name="email" id="email" required placeholder="Enter email address" required>
            </div>
        </div>

        <div class="submit">
            <button type="submit">{{ __('Send Password Reset Link') }}</button>
        </div>

        <div class="register">
            @if (Route::has('login'))
            Remember password? <a href="{{ route('login') }}">Login</a>
            @endif
        </div>
    </form>
</div>


{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
