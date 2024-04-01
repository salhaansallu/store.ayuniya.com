@extends('dashboard.views.layouts.app')

@section('dashboard')
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
                        <td>Profit</td>
                        <td>Status</td>
                        <td>Action</td>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($d_orders as $orders)
                        <tr>
                            <td onclick="getDetails('{{ $orders->order_number }}')">#{{ $orders->order_number }}</td>
                            <td>{{ $orders->created_at }}</td>
                            <td>{{ getOrderTotal($orders->orders) }}</td>
                            <td>0.00</td>
                            <td>{{ orderStatus($orders) }}</td>
                            <td>
                                <button onclick="deleteOrder('{{ $orders->id }}')"><i class="fa-solid fa-trash"></i></button>
                                <button onclick="window.open('https:\u002F\u002Fimage.ayuniya.com/payment-slip/{{ $orders->slip }}', '_blank')"><i class="fa-solid fa-receipt"></i></button>
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td class="p-1 text-center bg-light" colspan="6">Prossing orders</td>
                    </tr>

                    @foreach ($p_orders as $orders)
                        <tr>
                            <td onclick="getDetails('{{ $orders->order_number }}')">#{{ $orders->order_number }}</td>
                            <td>{{ $orders->created_at }}</td>
                            <td>{{ getOrderTotal($orders->orders) }}</td>
                            <td>0.00</td>
                            <td>{{ orderStatus($orders) }}</td>
                            <td>
                                <button onclick="deleteOrder('{{ $orders->id }}')"><i class="fa-solid fa-trash"></i></button>
                                <button onclick="window.open('https:\u002F\u002Fimage.ayuniya.com/payment-slip/{{ $orders->slip }}', '_blank')"><i class="fa-solid fa-receipt"></i></button>
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td class="p-1 text-center bg-light" colspan="6">Delivered orders</td>
                    </tr>

                    @foreach ($del_orders as $orders)
                        <tr>
                            <td onclick="getDetails('{{ $orders->order_number }}')">#{{ $orders->order_number }}</td>
                            <td>{{ $orders->created_at }}</td>
                            <td>{{ getOrderTotal($orders->orders) }}</td>
                            <td>0.00</td>
                            <td>{{ orderStatus($orders) }}</td>
                            <td>
                                <button onclick="deleteOrder('{{ $orders->id }}')"><i class="fa-solid fa-trash"></i></button>
                                <button onclick="window.open('https:\u002F\u002Fimage.ayuniya.com/payment-slip/{{ $orders->slip }}', '_blank')"><i class="fa-solid fa-receipt"></i></button>
                            </td>
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
                    url: "/get-order",
                    data: {
                        action: 'get_details',
                        order_number: order_number,
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
                        url: "/delete-order",
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
                    $.ajax({
                        type: "post",
                        url: "/update-status",
                        data: {
                            action: "update_status",
                            id: id,
                            status: "canceled",
                            _token: $("meta[name='csrf-token']").attr('content')
                        },
                        dataType: "json",
                        success: function(cancel) {
                            if (cancel.error == 0) {
                                toastr.success(cancel.msg, "Success");
                                setInterval(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                toastr.success(cancel.msg, "Error");
                            }
                        }
                    });
                }
                if (status == 1) {
                    $.ajax({
                        type: "post",
                        url: "/update-status",
                        data: {
                            action: "update_status",
                            id: id,
                            status: "processing",
                            _token: $("meta[name='csrf-token']").attr('content')
                        },
                        dataType: "json",
                        success: function(processing) {
                            //console.log(deliver);
                            if (processing.error == 0) {
                                toastr.success(processing.msg+" Pro", "Success");
                                setInterval(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                toastr.success(processing.msg, "Error");
                            }
                        }
                    });
                }
                if (status == 2) {
                    courier_name = prompt("Enter courier service name");
                    track_code = prompt("Enter courier tracking code");
                    track_link = prompt("Enter parcel tracking link");

                    if (courier_name && track_code && track_link) {
                        $.ajax({
                            type: "post",
                            url: "/update-status",
                            data: {
                                action: "update_status",
                                id: id,
                                status: "delivered",
                                _token: $("meta[name='csrf-token']").attr('content'),
                                courier_name: courier_name,
                                track_code: track_code,
                                track_link: track_link
                            },
                            dataType: "json",
                            success: function(deliver) {
                                //console.log(deliver);
                                if (deliver.error == 0) {
                                    toastr.success(deliver.msg, "Success");
                                    setInterval(() => {
                                        location.reload();
                                    }, 2000);
                                } else {
                                    toastr.success(deliver.msg, "Error");
                                }
                            }
                        });
                    }
                    else {
                        toastr.error("Please fill delivery details", "Error");
                    }
                }
            }
        }
    </script>
@endsection
