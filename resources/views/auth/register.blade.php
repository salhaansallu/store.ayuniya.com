@extends('layouts.app')

@section('content')

<style>
    body{
        background-color: #F8F8F8 !important;
    }
</style>

<div class="registration_form">
    <div class="head">
        <h1>Create an account <span>Already registered? <a href="{{ route('login') }}">Login</a></span></h1>
    </div>
    <form action="{{ route('register') }}" method="post" id="registrationForm">
        @csrf

        {{-- <div class="varification">
            <div class="step">Step 1</div>
            <div class="inner">
                <div class="input" style="margin-top: 0px">
                    <div class="label">Phone number <span>*</span></div>
                    <div class="txt_field d-flex">
                        <input type="text" name="number" id="number" value="{{ old('number') }}" placeholder="Enter phone number" required> <button type="button" id="sendcode">Send code</button>
                    </div>
                    @error('number')
                        <div class="response">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="input">
                    <div class="label">Verification code <span>*</span></div>
                    <div class="txt_field d-flex">
                        <input type="number" name="otp" id="otp" placeholder="Enter verification code" required> <button type="button" id="verify">Verify</button>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="signup">
            {{-- <div class="step">Step 2</div> --}}
            <div class="inner">
            <div class="input" style="margin-top: 0px">
                <div class="label">Full name <span>*</span></div>
                <div class="txt_field">
                    <input type="text" name="name" id="fullname" value="{{ old('name') }}" required placeholder="Enter full name" >
                </div>
                @error('name')
                    <div class="response">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="input">
                <div class="label">Email address <span>*</span></div>
                <div class="txt_field">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="Enter email address" >
                </div>
                @error('email')
                    <div class="response">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="input">
                <div class="label">Phone number <span>*</span></div>
                <div class="txt_field">
                    <input type="text" name="number" id="number" value="{{ old('number') }}" required placeholder="Enter phone number" >
                </div>
                @error('number')
                    <div class="response">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="input">
                <div class="label">Password <span>*</span></div>
                <div class="txt_field">
                    <input type="password" name="password" id="password" required placeholder="Enter password" >
                </div>
                @error('password')
                    <div class="response">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="input">
                <div class="label">Confirm Password <span>*</span></div>
                <div class="txt_field">
                    <input id="password-confirm" type="password" name="password_confirmation" required placeholder="Re-enter password" >
                </div>
            </div>

            <div class="submit">
                <button id="register_btn" type="submit" >Register</button>
            </div>
            </div>
        </div>
    </form>
</div>


<script>
    $("#sendcode").click(function (e) {
        e.preventDefault();
        number = $("#number").val();
        csrf = $('meta[name="csrf-token"]').attr('content');
        if (number != "") {
            $.ajax({
                type: "post",
                url: "/sendOtp",
                data: {number: number, _token: csrf},
                dataType: 'JSON',
                success: function (result) {

                    if (result.error == 1) {
                        //console.log(result.message);
                        toastr.error(result.message, 'Error');
                    }
                    else{
                        //console.log(result.message);
                        toastr.success(result.message, 'Success');
                    }
                }
            });
        }
        else{
            toastr.warning("Please enter phone number");
        }
    });


    $("#verify").click(function (e) {
        e.preventDefault();
        otpno = $("#otp").val();
        csrf = $('meta[name="csrf-token"]').attr('content');

        if (otp != "") {
            $.ajax({
                type: "post",
                url: "/verifyOtp",
                data: {otp: otpno, _token: csrf, action: "register_verify"},
                dataType: "JSON",
                success: function (response) {
                    if (response.error == 1) {
                        $("#otp_error").text("<div class='response'>"+response.message+"</div>");
                        toastr.error(response.message, 'Error');
                    }
                    else if (response.error == 0 && response.message == "Varified") {

                        toastr.success(response.message, 'Success');
                        $("#fullname").removeAttr("disabled");
                        $("#email").removeAttr("disabled");
                        $("#password").removeAttr("disabled");
                        $("#password-confirm").removeAttr("disabled");
                        $("#register_btn").removeAttr("disabled");

                    }
                    else{
                        toastr.error("Sorry, Something went wrong!", 'Error');
                    }
                }
            });
        }
    });
</script>


{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

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
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
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
