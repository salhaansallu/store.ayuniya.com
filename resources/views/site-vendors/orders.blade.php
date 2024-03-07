@extends('site-vendors.layout.app')

@section('vendor')
    <div class="top_nav">
        <div class="bread_crumb">Dashboard > <span>Orders</span></div>
    </div>

    <div class="categories">
        <div class="inner">
            <table>
                <thead>
                    <tr>
                        <td>Order ID</td>
                        <td>Order date</td>
                        <td>Order total</td>
                        <td>Status</td>
                        <td>Action</td>
                    </tr>
                </thead>

                <tbody>
                    @foreach (VendorOrderTotal('pending', true) as $orders)
                        <tr>
                            <td onclick="getDetails('{{ Crypt::encrypt($orders->id) }}')">#{{ $orders->order_number }}</td>
                            <td>{{ $orders->created_at }}</td>
                            <td>{{ currency($orders->total * $orders->qty) }}</td>
                            <td>{{ orderStatus($orders) }}</td>
                            <td><button onclick="deleteOrder('{{ Crypt::encrypt($orders->id) }}')"><i class="fa-solid fa-trash"></i></button></td>
                        </tr>
                    @endforeach

                    @foreach (VendorOrderTotal('processing', true) as $processorders)
                        <tr>
                            <td onclick="getDetails('{{ Crypt::encrypt($processorders->id) }}')">#{{ $processorders->order_number }}</td>
                            <td>{{ $processorders->created_at }}</td>
                            <td>{{ currency($processorders->total * $processorders->qty) }}</td>
                            <td>{{ orderStatus($processorders) }}</td>
                            <td><button onclick="deleteOrder('{{ Crypt::encrypt($processorders->id) }}')"><i class="fa-solid fa-trash"></i></button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order details Modal -->
    <div class="modal fade" id="Orderdetails" tabindex="-1" aria-labelledby="OrderdetailsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="head">Order details</div>
                    <div class="user_details">
                        <div class="c_details">
                            <div class="head">Customer info</div>
                            <div class="name">Name : <span id="ordername">N/A</span></div>
                            <div class="name">Mobile number : <span id="ordernumber">N/A</span></div>
                            <div class="name">Email : <span id="orderemail">N/A</span></div>
                        </div>
                        <div class="c_details">
                            <div class="head">Billing Address</div>
                            <div class="name">
                                <p id="billingaddress">N/A</p>
                            </div>
                        </div>
                        <div class="c_details">
                            <div class="head">Shipping Address</div>
                            <div class="name">
                                <p id="shippingaddress">N/A</p>
                            </div>
                        </div>
                    </div>
                    <div class="products">
                        <table id="orderProducts">
                            {{-- Content load dynamically --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function getDetails(order_number) {
            if (order_number != "" && order_number != " ") {
                $.ajax({
                    type: "post",
                    url: "/vendor/get-vendor-order",
                    data: {
                        action: 'get_details',
                        order: order_number,
                        _token: $("meta[name='csrf-token']").attr('content')
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.error == 0) {
                            document.getElementById("orderProducts").innerHTML = "";
                            document.getElementById("orderProducts").innerHTML = response.data;
                            $("#Orderdetails").modal("show");
                            document.getElementById("ordername").innerHTML = response.user_details[0].name;
                            document.getElementById("ordernumber").innerHTML = response.user_details[0].number;
                            document.getElementById("orderemail").innerHTML = response.user_details[0].email;
                            if (response.billaddress != "") {
                                document.getElementById("billingaddress").innerHTML = response.user_details[0]
                                    .name + "<br>" + response.billaddress[0].address1 + "<br>" + response
                                    .billaddress[0].city + "<br>" + response.billaddress[0].postal + "<br>" +
                                    response.billaddress[0].country;
                            }
                            document.getElementById("shippingaddress").innerHTML = response.orders;
                            console.log(response.billaddress);
                        } else {
                            toastr.error(response.msg, "Error");
                        }
                    }
                });
            }
        }

        function deleteOrder(order_id) {
            if (confirm("Are you sure you want to delete?") == true) {

                if (order_id != "" && order_id != " ") {
                    $.ajax({
                        type: "post",
                        url: "/vendor/delete-order",
                        data: {
                            action: "delete",
                            order_id: order_id,
                            _token: $("meta[name='csrf-token']").attr('content')
                        },
                        dataType: "json",
                        success: function(result) {
                            if (result.error == 0) {
                                toastr.success(result.msg, "Success");
                                setInterval(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                toastr.error(result.msg, "Error");
                            }
                        }
                    });
                } else {
                    toastr.error("Sorry something went wrong", "Error");
                }

            }
        }

        function updateStatus(id, status) {
            if (id != "" && id != " ") {
                if (status == 0) {
                    status = 'canceled';
                }
                else if(status == 1) {
                    status = 'processing';
                }
                else if(status == 2) {
                    status = 'delivered';
                }
                else {
                    return false;
                }

                $.ajax({
                        type: "post",
                        url: "/vendor/update-status",
                        data: {
                            action: "update_status",
                            id: id,
                            status: status,
                            _token: $("meta[name='csrf-token']").attr('content')
                        },
                        dataType: "json",
                        success: function(responseStatus) {
                            if (responseStatus.error == 0) {
                                toastr.success(responseStatus.msg, "Success");
                                setInterval(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                toastr.error(responseStatus.msg, "Error");
                            }
                        }
                    });
            }
        }
    </script>
@endsection
