@extends('account')

@section('account_content')
<div class="card">
    <form action="" id="billingaddressform" method="post" onsubmit="return false;">
        @csrf
        <input type="hidden" name="action" value="billing_update">
        <div class="form_header">
            <h5>Address book</h5>
            <div class="sub">Update billing address</div>
        </div>
    
        <div class="txt_field">
            <div class="label">Address <span>*</span></div>
            <div class="input"><input type="text" name="address1" value="{{ getAddress('billing')['address1'] }}" placeholder="Enter address" required></div>
        </div>

        <div class="txt_field">
            <div class="label">Postal code <span>*</span></div>
            <div class="input"><input type="text" name="postal" value="{{ getAddress('billing')['postal'] }}" placeholder="Enter postal code" required></div>
        </div>

        <div class="txt_field">
            <div class="label">City <span>*</span></div>
            <div class="input"><input type="text" name="city" value="{{ getAddress('billing')['city'] }}" placeholder="Enter city" required></div>
        </div>

        <div class="txt_field">
            <div class="label">Country <span>*</span></div>
            <div class="input">
                <select name="country" id="country" required>
                    @empty(getAddress('billing')['country'])
                    <option value="">-- Select Country --</option>
                    @else
                    <option value="{{ getAddress('billing')['country'] }}">{{ getAddress('billing')['country'] }}</option>
                    <option value="" disabled></option>
                    @endempty
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="Canada">Canada</option>
                    <option value="Australia">Australia</option>
                    <option value="France">France</option>
                </select>
            </div>
        </div>
    
        <div class="btn_update">
            <button type="submit" class="primary_btn">Update</button>
        </div>
    </form>

    <form action="" id="shippingaddressform" method="POST" onsubmit="return false;">
        @csrf
        <input type="hidden" name="action" value="shipping_update">
        <div class="form_header">
            <div class="sub">Update shipping address</div>
        </div>
    
        <div class="txt_field">
            <div class="label">Address <span>*</span></div>
            <div class="input"><input type="text" name="address1" value="{{ getAddress('shipping')['address1'] }}" placeholder="Enter address" required></div>
        </div>

        <div class="txt_field">
            <div class="label">Postal code <span>*</span></div>
            <div class="input"><input type="text" name="postal" value="{{ getAddress('shipping')['postal'] }}" placeholder="Enter postal code" required></div>
        </div>

        <div class="txt_field">
            <div class="label">City <span>*</span></div>
            <div class="input"><input type="text" name="city" value="{{ getAddress('shipping')['city'] }}" placeholder="Enter city" required></div>
        </div>

        <div class="txt_field">
            <div class="label">Country <span>*</span></div>
            <div class="input">
                <select name="country" id="country" required>
                    @empty(getAddress('shipping')['country'])
                    <option value="">-- Select Country --</option>
                    @else
                    <option value="{{ getAddress('shipping')['country'] }}">{{ getAddress('shipping')['country'] }}</option>
                    <option value="" disabled></option>
                    @endempty
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="Canada">Canada</option>
                    <option value="Australia">Australia</option>
                    <option value="France">France</option>
                </select>
            </div>
        </div>
    
        <div class="btn_update">
            <button type="submit" class="primary_btn">Update</button>
        </div>
    </form>
</div>


<script>
    $("#billingaddressform").submit(function () {
        $.ajax({
            type: "post",
            url: "/account-update",
            data: $(this).serialize(),
            dataType: "json",
            success: function (data) {
                if (data.error == 0) {
                    toastr.success(data.msg, "Success");
                }
                else{
                    toastr.error(data.msg, "Error");
                }
            }
        });
    });

    $("#shippingaddressform").submit(function () {
        $.ajax({
            type: "post",
            url: "/account-update",
            data: $(this).serialize(),
            dataType: "json",
            success: function (data) {
                if (data.error == 0) {
                    toastr.success(data.msg, "Success");
                }
                else{
                    toastr.error(data.msg, "Error");
                }
            }
        });
    });
</script>
@endsection