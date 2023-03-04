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
        @vite(['resources/views/dashboard/sass/'.$css])
    @endisset

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @vite(['resources/js/app.js', 'resources/sass/app.scss', 'resources/views/dashboard/sass/app.scss'])
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>
<body>

    <div id="dashboard" class="main">
        <div class="dashboard">
            <div class="content">
                @yield('vendor_reg')
            </div>
        </div>
    </div>
    
</body>
</html>