@extends('dashboard.views.layouts.app')

@section('dashboard')

    <style>
        form .input {
            margin-top: 0;
        }
    </style>

    <div class="top_nav">
        <div class="bread_crumb">Dashboard > <span>Manufacturers</span></div>
        <div class="create"><button class="secondary_btn mx-2" type="button"
                onclick="location.href='/web-admin/vendor-register';"><i class="fa-solid fa-plus"></i> Add new</button>
            <button class="secondary_btn" type="button" id="Register_code"><i class="fa-solid fa-plus"></i> Register
                Code</button></div>
    </div>

    <div class="products">
        <div class="inner">
            <table>
                <thead>
                    <tr>
                        <td>Company</td>
                        <td>Company email</td>
                        <td>Company No.</td>
                        <td>Business type</td>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($vendors as $vendor)
                        <tr>
                            <td>
                                <p>{{ $vendor->company_name }}({{ $vendor->store_name }})</p>
                            </td>
                            <td>{{ $vendor->company_email }}</td>
                            <td>{{ $vendor->company_number }}</td>
                            <td>{{ $vendor->business_type }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            @isset($products)
                @if ($products->links()->paginator->hasPages())
                    {{ $products->appends(request()->query())->links() }}
                @endif
            @endisset
        </div>
    </div>

    <div class="modal fade" id="VendorCode" tabindex="-1" aria-labelledby="VendorCodeLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="VendorCodeLabel">Vendor registration code</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="basic-url" class="form-label">Your vanity URL</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon3">https://store.ayuniya.com/vendor-register/</span>
                            <input type="text" class="form-control" id="basic-url" readonly aria-describedby="basic-addon3 basic-addon4">
                        </div>
                        <div class="form-text" id="basic-addon4">URL has been copied to your clipboard</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#Register_code").click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "post",
                    url: "/register-vendorcode",
                    data: {
                        reg_Vendorcode: true,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.error == 0) {
                          $("#basic-url").val(response.vendorCode);
                          navigator.clipboard.writeText('https\u003A//store.ayuniya.com/vendor-register/'+response.vendorCode)
                        } else {
                            toastr.error(response.msg, "Error");
                            $("#basic-url").val('Error');
                        }
                    }
                });
                $("#VendorCode").modal('toggle');
            });
        });
    </script>

@endsection
