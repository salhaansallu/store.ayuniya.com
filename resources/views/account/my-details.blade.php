@extends('account')

@section('account_content')
<div class="card">
    <form action="" id="personalDetails" onsubmit="return false;" method="POST">
        @csrf
        <input type="hidden" name="action" value="personalUpdate" id="">
        <div class="form_header">
            <h5>My details</h5>
            <div class="sub">Personal details</div>
        </div>

        <div class="txt_field">
            <div class="label">Full Name</div>
            <div class="input"><input type="text" name="username" value="{{ Auth::user()->name }}" required></div>
        </div>

        <div class="txt_field">
            <div class="label">Email address</div>
            <div class="input"><input type="email" name="email" value="{{ Auth::user()->email }}" required></div>
        </div>

        <div class="btn_update">
            <button type="submit" class="primary_btn">Update</button>
        </div>
    </form>
</div>
{{-- <div class="card">
    <form action="" method="post" id="updatenumber" onsubmit="return false;">
        @csrf
        <div class="form_header">
            <div class="sub">Phone number</div>
        </div>

        <div class="txt_field">
            <div class="label">Phone number</div>
            <div class="input"><input type="text" class="verify_field" value="{{ Auth::user()->number }}" id="number"> <button class="primary_btn" id="sendcode" type="button">Send verification code</button></div>
        </div>

        <div class="note">We have sent a verification code to above phone number please enter it in the below text field</div>

        <div class="txt_field d-flex">
            <div class="label">Enter verification code <input type="text" class="verify_code" id="otp"><button class="primary_btn" id="verify" type="button">Verify</button></div>
        </div>

        <div class="btn_update" style="margin-top: -30px">
            <button type="submit" id="updatenumberbtn" class="primary_btn">Update</button>
        </div>
    </form>
</div> --}}

<script>
    $("#personalDetails").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "/account-update",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.error == 0) {
                    toastr.success(response.msg, "Success");
                }
                else {
                    toastr.error(response.msg, "Error");
                }
            }
        });
    });

    $("#updatenumberbtn").click(function (e) {
        e.preventDefault();
        csrf = document.querySelector("#updatenumber input[type='hidden']").value;
        $.ajax({
            type: "post",
            url: "/account-update",
            data: {action: 'updatenumber', _token: csrf},
            dataType: "json",
            success: function (response) {
                if (response.error == 0) {
                    toastr.success(response.msg, "Success");
                }
                else {
                    toastr.error(response.msg, "Error");
                }
            }
        });
    });


    $("#sendcode").click(function () {
        number = $("#number").val();
        csrf = document.querySelector("#updatenumber input[type='hidden']").value;
        if(number != ""){
            $.ajax({
                type: "post",
                url: "/sendOtp",
                data: {number: number, _token: csrf},
                dataType: "JSON",
                success: function (data) {
                    console.log(data);
                    if (data.error == 0) {
                        toastr.success(data.message, "Success");
                    }
                    else {
                        toastr.error(data.message, "Error");
                    }
                }
            });
        }
        else{
            toastr.warning("Please enter phone number");
        }
    });


    $("#verify").click(function () {
        otpno = $("#otp").val();
        csrf = document.querySelector("#updatenumber input[type='hidden']").value;

        if (otp != "") {
            $.ajax({
                type: "post",
                url: "/verifyOtp",
                data: {otp: otpno, _token: csrf, action: "register_verify"},
                dataType: "JSON",
                success: function (result) {
                    if (result.error == 1) {
                        toastr.error(result.message, 'Error');
                    }
                    else if (result.error == 0) {
                        toastr.success(result.message, 'Success');
                    }
                    else{
                        toastr.error("Sorry, Something went wrong!", 'Error');
                    }
                }
            });
        }
    });
</script>
@endsection
