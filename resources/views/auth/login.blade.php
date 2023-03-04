@extends('layouts.app')

@section('content')

<style>
    body{
        background-color: #F8F8F8 !important;
    }
</style>

<div class="login_form">
    <div class="header">
        <h1>Login</h1>
    </div>
    <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="input">
            <div class="label">Email address <span>*</span></div>
            <div class="txt_field">
                <input type="email" name="email" id="email" required placeholder="Enter email address" required>
            </div>
        </div>

        <div class="input">
            <div class="label">Password <span>*</span></div>
            {{-- @if (Route::has('password.request'))
                <div class="passreset">Forgot password? <a href="{{ route('password.request') }}">Reset</a></div>
            @endif --}}
            <div class="txt_field">
                <input type="password" name="password" id="password" required placeholder="Minimum 8 characters" required>
            </div>
        </div>

        <div class="show_pass">
            <input id="show_pass" type="checkbox" name="" id=""> Show password
        </div>

        <div class="submit" id="login_btn">
            <button type="submit">Login</button>
        </div>
        @error('email')
        <div class="response">
            {{ $message }}
        </div>
        @enderror


        @error('password')
        <div class="response">
            {{ $message }}
        </div>
        @enderror

        <div class="register">
            Not registered? <a href="{{ route('register') }}">Register</a>
        </div>
    </form>
</div>

{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
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

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
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



<script>

// ======== Login password shower ====== //

var show_Pass = document.getElementById("show_pass");

$(show_Pass).click(function () {
if (show_Pass.checked) {
    document.getElementById("password").setAttribute('type', 'text');
}
else{
    document.getElementById("password").setAttribute('type', 'password');
}
});

</script>

@endsection
