<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard - {{ config('app.name') }}</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/brand/favicon.png') }}" type="image/x-icon">

    @isset($css)
        @vite(['resources/views/dashboard/sass/' . $css])
    @endisset

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
        integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @vite(['resources/js/app.js', 'resources/sass/app.scss', 'resources/views/dashboard/sass/app.scss'])
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>

<body>

    <div id="dashboard" class="main">
        <div class="left_nav" style="height: 100vh">
            <div class="brand">
                <img src="{{ asset('assets/images/dashboard/logo.png') }}" alt="">
            </div>
            <div class="nav_item">
                <ul>
                    @if (isVendor())
                        <li><a href="/vendor" class="active"><i class="fa-solid fa-house"></i> Dashboard</a></li>
                        <li><a href="/vendor/products"><i class="fa-solid fa-basket-shopping"></i> Products</a></li>
                        <li><a href="/vendor/orders"><i class="fa-solid fa-truck"></i> Orders ({{ VendorOrderTotal('pending') }})</a></li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="dashboard">
            <div class="top_section">
                <div class="bread_crumb">
                    <div class="dashboard_label">
                        <h1>Dashboard</h1>
                    </div>
                    <div class="greeting">Welcome back, {{ isVendor() ? explode(' ', Vendor()->company_name)[0] : '' }}</div>
                </div>
                <div class="analytics">
                    <div class="row row-cols-4">
                        <div class="col">
                            <div class="inner">
                                <div class="icon">
                                    <i class="fa-solid fa-truck"></i>
                                </div>
                                <div class="details">
                                    <div class="label">Total Orders</div>
                                    <div class="amt">{{ isVendor() ? VendorOrderTotal('delivered') + VendorOrderTotal('pending') : '00.00' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="inner">
                                <div class="icon">
                                    <i class="fa-regular fa-calendar-check"></i>
                                </div>
                                <div class="details">
                                    <div class="label">Pending Orders</div>
                                    <div class="amt">
                                        <div class="amt">{{ isVendor() ? VendorOrderTotal('pending') : '00.00' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="inner">
                                <div class="icon">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>
                                <div class="details">
                                    <div class="label">This Month's Sales</div>

                                    <div class="amt">{{ isVendor() ? currency(App\Http\Controllers\VendorPaymentsController::getOrders(date("Y") . "-". date('m') ."-1", date("Y") . "-". date('m') ."-31", true)) : '00.00' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="inner">
                                <div class="image">
                                    <img src="{{ asset('assets/images/brand/favicon.png') }}" style="height: 100%;"
                                        alt="">
                                </div>
                                <div class="logout"
                                    onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                                    Logout <i class="fa-solid fa-arrow-right-from-bracket"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form id="frm-logout" action="/vendor/logout" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>

            <div class="content">
                @yield('vendor')
            </div>

        </div>
    </div>
</body>

</html>
