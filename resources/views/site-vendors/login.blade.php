@extends('site-vendors.layout.app')

@section('vendor')

<style>
    body{
        background-color: #F8F8F8 !important;
    }
</style>

<div class="login_form">
    <div class="header">
        <h1>Login</h1>
    </div>
    <form action="/vendor/login" method="post">
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
    </form>
</div>

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
