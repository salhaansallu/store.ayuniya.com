@extends('dashboard.views.layouts.app')

@section('dashboard')
    <div class="top_nav">
        <div class="bread_crumb">Dashboard > <span>Recurring Orders</span></div>
        <div class="create"><button class="secondary_btn" onclick="pay('all', 'all')">Bill pending orders</button></div>
    </div>

    <div class="categories">
        <div class="inner">
            <table>
                <thead>
                    <tr>
                        <td>Cart ID</td>
                        <td>Recurring Date</td>
                        <td>Products</td>
                        <td>Customer</td>
                        <td>Mobile No</td>
                        <td>Action</td>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->cart_id }}</td>
                            <td>{{ date('d-m-Y', strtotime('+1 month', strtotime(strtotime($order->updated_at)))) }}</td>
                            <td>{{ $order->products->count() }}</td>
                            <td>{{ getUserDetails($order->user_id)[0]->name }}</td>
                            <td>{{ getUserDetails($order->user_id)[0]->number }}</td>
                            <td><button onclick="pay({{ $order->cart_id }}, 'bill')">Bill</button> <button
                                    onclick="pay({{ $order->cart_id }}, 'delete')">Delete</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <script>
        function pay(id, type) {
            if (id == 'all') {
                $.ajax({
                    type: "post",
                    url: "/web-admin/recurring-orders",
                    data: {
                        order_id: 'all',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.error == 0) {
                            toastr.success(response.msg, "Success");
                        } else {
                            toastr.error(response.msg, "Error");
                        }
                    }
                });
            } else {
                $.ajax({
                    type: "post",
                    url: "/web-admin/recurring-orders",
                    data: {
                        order_id: id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.error == 0) {
                            toastr.success(response.msg, "Success");
                        } else {
                            toastr.error(response.msg, "Error");
                        }
                    }
                });
            }
        }
    </script>
@endsection
