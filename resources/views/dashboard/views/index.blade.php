@extends('dashboard.views.layouts.app')

@section('dashboard')

    <div class="chart">
        <div class="sales">
            <canvas id="salesChart" ></canvas>

            <script>
                const ctx = 'salesChart';
                const salse = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Sales',
                            data: [
                                {{ $sales['jan'] }},
                                {{ $sales['feb'] }},
                                {{ $sales['mar'] }},
                                {{ $sales['apr'] }},
                                {{ $sales['may'] }},
                                {{ $sales['jun'] }},
                                {{ $sales['jul'] }},
                                {{ $sales['aug'] }},
                                {{ $sales['sep'] }},
                                {{ $sales['oct'] }},
                                {{ $sales['nov'] }},
                                {{ $sales['dec'] }},
                            ],
                            backgroundColor:'rgba(0, 168, 255, 1)',
                            borderColor: 'rgba(0, 168, 255, 1)',
                            borderWidth: 3
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: false
                            }
                        },
                        maintainAspectRatio: false,
                    }
                });
            </script>
        </div>

        <div class="sales">
            <canvas id="orderChart" ></canvas>

            <script>
                const orderChart = 'orderChart';
                const orders = new Chart(orderChart, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Delivered', 'Canceled'],
                        datasets: [{
                            label: 'Orders',
                            data: [{{ orderTotal('pending') }}, {{ orderTotal('delivered') }}, {{ orderTotal('canceled') }}],
                            backgroundColor:['rgba(255, 159, 64, 1)','rgba(0, 168, 255, 1)','rgba(255, 68, 68, 1)'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                    }
                });
            </script>
        </div>
    </div>

    <div class="low_stock">
        <div class="inner">
            <div class="head">Low stock items</div>
            <table>
                <thead>
                    <tr>
                        <td></td>
                        <td>Product Sku</td>
                        <td>Product stock</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($low_stock as $low)
                    <tr>
                        <td><img src="{{ validate_image($low->image_path) }}" alt=""> <p>{{ $low->product_name }}</p></td>
                        <td>{{ $low->sku }}</td>
                        <td>{{ $low->qty }}</td>
                        <td><a href="{{ request()->getRequestUri() }}/products?edit={{ $low->pro_id }}"><button class="update">Update</button></a></td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>
        </div>
    </div>

    <div class="low_stock" style="padding-top: 0;">
        <div class="inner">
            <div class="head">Today's appointments</div>
            <table>
                <thead>
                    <tr>
                        <td></td>
                        <td>Date and Time</td>
                        <td>Mobile</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $app)
                        <tr>
                            <td><img src="{{ asset('assets/images/dashboard/user_icon.png') }}" alt=""></td>
                            <td>{{ date("d-m-Y h:i A", strtotime($app->app_date)) }}</td>
                            <td>{{ $app->number }}</td>
                            <td><button class="update" onclick="book('{{ $app->app_id }}')">Accept</button> <button onclick="decline('{{ $app->app_id }}')" class="delete">Decline</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function book(id) {
        if (id != "") {
            $.ajax({
                type: "post",
                url: "/book",
                data: {action: "book", id: id, _token: $("meta[name='csrf-token']").attr('content')},
                dataType: "json",
                success: function (response) {
                    if (response.error == 0) {
                        toastr.success(response.msg, 'Success');
                        setInterval(() => {
                            location.reload();
                        }, 2000);
                    }
                    else {
                        toastr.error(response.msg, 'Error');
                    }
                }
            });
        }
    }

    function decline(id) {
        if (id != "") {
            $.ajax({
                type: "post",
                url: "/book",
                data: {action: "decline", id: id, _token: $("meta[name='csrf-token']").attr('content')},
                dataType: "json",
                success: function (decline) {
                    if (decline.error == 0) {
                        toastr.success(decline.msg, 'Success');
                        setInterval(() => {
                            location.reload();
                        }, 2000);
                    }
                    else {
                        toastr.error(decline.msg, 'Error');
                    }
                }
            });
        }
    }
</script>
@endsection