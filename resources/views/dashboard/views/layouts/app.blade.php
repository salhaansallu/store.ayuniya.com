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
        <div class="left_nav">
            <div class="brand">
                <img src="{{ asset('assets/images/dashboard/logo.png') }}" alt="">
            </div>
            <div class="nav_item">
                <ul>
                    @if (isAdmin())
                        <li><a href="/web-admin" class="active"><i class="fa-solid fa-house"></i> Dashboard</a></li>
                        <li><a href="/web-admin/categories"><i class="fa-solid fa-table-cells"></i> Categories</a></li>
                        <li><a href="/web-admin/sub-categories"><i class="fa-solid fa-table-cells"></i> Sub
                                categories</a></li>
                        <li><a href="/web-admin/products"><i class="fa-solid fa-basket-shopping"></i> Products</a></li>
                        <li><a href="/web-admin/contacts"><i class="fa-solid fa-message"></i> Messages</a></li>
                        <li><a href="/web-admin/orders"><i class="fa-solid fa-truck"></i> Orders ({{ orderTotal('pending') }})</a></li>
                        <li><a href="/web-admin/deposit-orders"><i class="fa-regular fa-money-bill-1"></i> Deposit Orders</a></li>
                        <li><a href="/web-admin/vendor-register"><i class="fa-solid fa-store"></i> Manufacturer register</a>
                        </li>
                        <li><a href="/web-admin/vendors"><i class="fa-solid fa-store"></i> Manufacturers</a></li>
                        <li><a href="/web-admin/payments"><i class="fa-solid fa-sack-dollar"></i> Payments</a></li>
                        <li><a href="/web-admin/blogs"><i class="fa-solid fa-pen-nib"></i> Blog Posts</a></li>
                        <li><a href="/web-admin/appointment"><i class="fa-solid fa-clipboard-check"></i> Appointments
                                ({{ appointments(date('Y-m-d'), date('Y-m-d', strtotime('+365 days')), true) }})</a>
                        </li>
                        <li><a href="/web-admin/users"><i class="fa-regular fa-user"></i> Users</a></li>
                        <li><a href="/print-orders" class="active"><i class="fa-solid fa-print"></i> Print orders</a>
                        </li>
                    @endif
                    @if (isOrderManager())
                        <li><a href="/print-orders" class="active"><i class="fa-solid fa-print"></i> Print orders</a>
                        </li>
                    @endif
                    @if (isCustomerCareManager())
                        <li><a href="/web-admin/contacts"><i class="fa-solid fa-message"></i> Messages</a></li>
                    @endif
                    @if (isAccountManager())
                        <li><a href="/web-admin/vendor-register"><i class="fa-solid fa-store"></i> Manufacturer register</a></li>
                        <li><a href="/web-admin/vendors"><i class="fa-solid fa-store"></i> Manufacturers</a></li>
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
                    <div class="greeting">Welcome back, {{ Auth::user()->name }}</div>
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
                                    <div class="amt">{{ orderTotal('delivered') + orderTotal('pending') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="inner">
                                <div class="icon">
                                    <i class="fa-regular fa-calendar-check"></i>
                                </div>
                                <div class="details">
                                    <div class="label">Today's Appontments</div>
                                    <div class="amt">
                                        {{ appointments(date('Y-m-d'), date('Y-m-d', strtotime('tomorrow')), true) }}
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
                                    <div class="label">Today's Sales</div>

                                    <div class="amt">{{ getOrders(date('Y-m-d'), date('Y-m-d'), true) }}</div>
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

            <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>

            <div class="content">
                @yield('dashboard')
            </div>

        </div>
    </div>
</body>

</html>
