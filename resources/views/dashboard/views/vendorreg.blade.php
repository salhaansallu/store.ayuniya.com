@extends('dashboard.views.layouts.vendorapp')

@section('vendor_reg')


@isset($error)
    <script>
        $(document).ready(function () {
            toastr.error('{{ $error }}', "Error");
            $("#vendorRegister :input").prop('readonly', true);
        });
    </script>
@endisset

<style>
    body{
        background-color: #F8F8F8 !important;
    }
</style>

<div class="vendor_form">
    <div class="header">
        <h1>Manufacturer register</h1>
    </div>
    <form action="" onsubmit="return false;" method="post" id="vendorRegister">
        @csrf

        <input type="hidden" name="_code" value="@isset($_code) {{ $_code }} @endisset">
        <div class="d-flex">
            <div class="input">
                <div class="label">Company name <span>*</span></div>
                <div class="txt_field">
                    <input type="text" name="name" id="name" placeholder="Enter company name" required>
                </div>
            </div>

            <div class="input">
                <div class="label">Company email</div>
                <div class="txt_field">
                    <input type="email" name="email" id="email"  placeholder="Enter company email" >
                </div>
            </div>
        </div>

        <div class="d-flex">
            <div class="input">
                <div class="label">Company contact number <span>*</span></div>
                <div class="txt_field">
                    <input type="number" name="number" id="number"  placeholder="Enter company number" required>
                </div>
            </div>

            <div class="input">
                <div class="label">Company FAX number </div>
                <div class="txt_field">
                    <input type="number" name="fax" id="fax"  placeholder="Enter company fax number" >
                </div>
            </div>
        </div>

        <div class="input">
            <div class="label">Address line 1 </div>
            <div class="txt_field">
                <input type="text" name="address1" id="address1"  placeholder="Enter company address line 1" >
            </div>
        </div>

        <div class="input">
            <div class="label">Address line 2 </div>
            <div class="txt_field">
                <input type="text" name="address2" id="address2"  placeholder="Enter company address line 2" >
            </div>
        </div>

        <div class="input">
            <div class="label">Company website URL</div>
            <div class="txt_field">
                <input type="text" name="website" id="website"  placeholder="Enter company website url" >
            </div>
        </div>

       <div class="d-flex">
        <div class="input">
            <div class="label">Business type  <span>*</span></div>
            <div class="txt_field">
                <input type="text" name="b_type" id="b_type" required placeholder="Enter business type" >
            </div>
        </div>

        <div class="input">
            <div class="label">Store name  <span>*</span></div>
            <div class="txt_field">
                <input type="text" name="s_name" id="s_name" required placeholder="Enter store name" >
            </div>
        </div>
       </div>

        <div class="d-flex">
        <div class="input">
            <div class="label">Factory approved license number  <span>*</span></div>
            <div class="txt_field">
                <input type="text" name="license" id="license" required placeholder=" Enter Factory approved license number" >
            </div>
        </div>

        <div class="input">
            <div class="label">Business registration number  <span>*</span></div>
            <div class="txt_field">
                <input type="text" name="registration_number" id="registration_number" required placeholder=" Enter Business registration number" >
            </div>
        </div>
        </div>

        <div class="input">
            <div class="label">Number of products  <span>*</span></div>
            <div class="txt_field">
                <input type="number" name="products" id="products" required placeholder="Enter number of products" >
            </div>
        </div>

        <div class="input">
            <div class="label">Banking information  <span>*</span></div>

            <div class="d-flex">
                <div class="check">
                    <input type="radio" required name="bank_type" id="bank_type" value="Bank transfer"> Bank transfer
                </div>
                <div class="check">
                    <input type="radio" required name="bank_type" id="bank_type" value="Cheque"> Cheque
                </div>
            </div>
        </div>

        <div class="input">
            <div class="label">Bank name  <span>*</span></div>
            <div class="txt_field">
                <input type="text" name="bankname" id="bankname" required placeholder="Enter bank name" >
            </div>
        </div>

        <div class="input">
            <div class="label">Bank branch name  <span>*</span></div>
            <div class="txt_field">
                <input type="text" name="branchname" id="branchname" required placeholder="Enter bank branch name" >
            </div>
        </div>

        <div class="input">
            <div class="label">Bank account name  <span>*</span></div>
            <div class="txt_field">
                <input type="text" name="accountname" id="accountname" required placeholder="Enter Bank account name" >
            </div>
        </div>

        <div class="input">
            <div class="label">Bank account number  <span>*</span></div>
            <div class="txt_field">
                <input type="text" name="accountnumber" id="accountnumber" required placeholder="Enter Bank account number" >
            </div>
        </div>

        <div class="vendor_register">
            <button type="submit">Register</button>
        </div>
    </form>
</div>

<script>
    $("#vendorRegister").submit(function () {
        $.ajax({
            type: "post",
            url: "/vendor-verify",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.error == 0) {
                    toastr.success(response.msg, "Success");
                    setInterval(() => {
                        location.reload();
                    }, 2000);
                }
                else {
                    toastr.error(response.msg, "Error");
                }
            }
        });
    });
</script>

@endsection
