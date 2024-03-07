@extends('site-vendors.layout.app')

@section('vendor')

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
                        data: [{{ VendorOrderTotal('pending') }}, {{ VendorOrderTotal('delivered') }}, {{ VendorOrderTotal('canceled') }}],
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



<div class="stock">
    <canvas id="least_stock" ></canvas>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/stocks/least-available')
                .then(response => response.json())
                .then(data => {
                    const stockData = data.map(stock => stock.qty);
                    const stockLabels = data.map(stock => stock.pro_id);
    
                    // Render the chart
                    const ctx = document.getElementById('stockChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: stockLabels,
                            datasets: [{
                                label: 'Least Available Stocks',
                                data: stockData,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
    
</div>

@endsection
