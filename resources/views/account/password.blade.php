@extends('account')

@section('account_content')
<div class="card">
    <form action="" method="POST" id="passwordUpdate" onsubmit="return false;">
        @csrf
        <div class="form_header">
            <h5>Change password</h5>
            <div class="sub">Change account password</div>
        </div>
    
        <div class="txt_field">
            <div class="label">Old password</div>
            <div class="input"><input type="password" id="oldPassword" name="oldPassword" placeholder="Enter old password" required></div>
        </div>
    
        <div class="txt_field">
            <div class="label">New password</div>
            <div class="input"><input type="password" id="newPassword" name="newPassword" placeholder="Enter new password" required></div>
        </div>

        <div class="txt_field">
            <div class="label">Confirm new password</div>
            <div class="input"><input type="password" id="cPassword" name="cPassword" placeholder="Confirm new password" required></div>
        </div>
    
        <div class="btn_update">
            <button type="submit" class="primary_btn">Update</button>
        </div>
    </form>
</div>

<script>
    $("#passwordUpdate").submit(function () {
        if ($("#oldPassword").val() != "" || $("#newPassword").val() != "" || $("#cPassword").val() != "") {
            $.ajax({
                type: "post",
                url: "/password-update",
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.error == 0) {
                        toastr.success(response.msg, "Success");
                        $("#oldPassword").val("");
                        $("#newPassword").val("");
                        $("#cPassword").val("");
                    }
                    else{
                        toastr.error(response.msg, "Error");
                    }
                }
            });
        }
        else{
            toastr.error("Please fill all fields", "Error");
        }
    });
</script>
@endsection