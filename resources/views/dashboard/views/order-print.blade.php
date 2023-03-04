@extends('dashboard.views.layouts.app')

@section('dashboard')

<div class="top_nav">
    <div class="bread_crumb">Dashboard > <span>Print orders</span></div>
    <div class="create"><button class="secondary_btn" type="button" id="PrintOrders" onclick="printinv()">Print orders</button></div>
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
                </tr>
            </thead>

            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>#{{ $order->order_number }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>{{ getOrderTotal($order->orders) }}</td>
                        <td>{{ orderStatus($order) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function printinv() {
        $("#PrintOrders").prop("disabled", "true");
        print = setInterval(() => {
        $.ajax({
            type: "post",
            url: "/print-orders",
            data: {_token: $("meta[name='csrf-token']").attr('content')},
            dataType: "json",
            success: function (response) {
                if (response.error==0) {
                    console.log(response.msg);
                    let objFra = document.createElement('iframe');
                    objFra.style.visibility = 'hidden';
                    objFra.src = response.msg;
                    document.body.appendChild(objFra);
                    objFra.contentWindow.focus();
                    objFra.contentWindow.print();
                }
                else {
                    $("#PrintOrders").removeAttr("disabled");
                    toastr.warning(response.msg);
                    setTimeout(() => {
                        location.reload();
                    }, 5000);
                }
            }
        });
    }, 10000);
    }
</script>


@endsection