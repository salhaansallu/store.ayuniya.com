@extends('layouts.app')

@section('content')

<style>
    body{
        background-color: #F8F8F8 !important;
    }
</style>

<div class="login_form">
    <div class="header">
        <h1>Update password</h1>
    </div>
    <form action="{{ route('reset.password.post') }}" method="post">
        @csrf

        <div class="input">
            <div class="label">New password <span>*</span></div>
            <div class="txt_field">
                <input type="password" name="password" id="password" required placeholder="Enter password" required>
            </div>
        </div>

        <div class="input">
            <div class="label">Confirm new password <span>*</span></div>
            <div class="txt_field">
                <input type="password" name="repassword" id="repassword" required placeholder="Re-enter password" required>
            </div>
        </div>

        <div class="submit">
            <button type="submit">{{ __('Update Password') }}</button>
        </div>
    </form>
</div>

{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Confirm Password') }}</div>

                <div class="card-body">
                    {{ __('Please confirm your password before continuing.') }}

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Confirm Password') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
