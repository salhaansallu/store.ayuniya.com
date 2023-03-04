<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#64BF47"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @isset($keyword)
    <meta name="keyword" content="{{ $keyword }}">
    @endisset

    @isset($metaDes)
    <meta name="description" content="{{ $metaDes }}">
    @endisset

    <!-- Title -->
    @isset($title)
        <title>{{ $title }}</title>
        <meta name="og:title" content="{{ $title }}">
    @endisset

    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('assets/images/brand/favicon.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/brand/header_logo.png') }}">

    @isset($css)
        @vite(['resources/sass/'.$css])
    @endisset

    @isset($js)
        @vite(['resources/js/'.$js])
    @endisset
    
    @php
        if($_SERVER['REQUEST_URI'] == '/login'){
            echo '<title>Login - '. config('app.name') .'</title>';
        }
        elseif($_SERVER['REQUEST_URI'] == '/register'){
            echo '<title>Register - '. config('app.name') .'</title>';
        }
        elseif($_SERVER['REQUEST_URI'] == '/password/reset'){
            echo '<title>Reset your password</title>';
        }
    @endphp

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    @vite(['resources/js/app.js', 'resources/js/custom.js', 'resources/sass/app.scss'])

    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-97KG29K9WK"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-97KG29K9WK');
</script>
    
</head>
<body>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
            new google.translate.TranslateElement({pageLanguage: 'en'}, 'md_google_translate_element');

            var googleDiv = $("#google_translate_element .skiptranslate");
            var googleDivChild = $("#google_translate_element .skiptranslate div");
            googleDivChild.next().remove();

            googleDiv.contents().filter(function(){
                return this.nodeType === 3 && $.trim(this.nodeValue) !== '';
            }).remove();

            var mdgoogleDiv = $("#md_google_translate_element .skiptranslate");
            var mdgoogleDivChild = $("#md_google_translate_element .skiptranslate div");
            mdgoogleDivChild.next().remove();

            mdgoogleDiv.contents().filter(function(){
                return this.nodeType === 3 && $.trim(this.nodeValue) !== '';
            }).remove();
        }
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <style>
        #google_translate_element {
            margin-top: -2px;
            margin-left: 5px;
        }
        #google_translate_element, #google_translate_element * {
            display: inline;
        }
        .goog-te-banner-frame{ display:none !important; }
        body{ position: unset !important; }
    </style>

    <div id="app">
        <nav>
            <div class="top_nav">
                <div class="col">
                    @guest
                        <p><i class="fa-regular fa-user"></i> Hello, <span class="nav-username">Guest</span></p>
                    @else
                        <p><i class="fa-regular fa-user"></i> Hello, <span class="nav-username">{{ Auth::user()->name }}</span></p>
                    @endguest
                </div>

                <div class="col d-flex">
                    <p><i class="fa-solid fa-language"></i> Language : <div style="display: inline;" id="google_translate_element" nostranslate></div></p>
                </div>
            </div>
            <div class="bottom_nav">
                <div class="list_items container">
                    <div class="brand"><img src="{{ asset('assets/images/brand/header_logo.png') }}" alt="{{ config('app.name') }} Logo"></div>

                    <div class="nav_list">
                        <div class="search">
                            <form action="/" method="get" onsubmit="return false;">
                                <div class="searchbar" id="searchbar">
                                    <pc-search />
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="cart" onclick="location.href='/cart';">
                        <p><i class="fa-solid fa-cart-shopping"></i> <span class="item_count" id="item_count">{{ getCartCount() }}</span></p>
                    </div>

                </div>
                <ul>
                    <li><a class="@if($_SERVER['REQUEST_URI'] == '/') active @endif" href="/">Home</a></li>
                    <li><a class="@if($_SERVER['REQUEST_URI'] == '/appointment') active @endif" href="#">Services</a>
                    <ul>
                        <li><a href="/services">Hospital Booking</a></li>
                        <li><a href="/services">Salon Booking</a></li>
                    </ul>
                    </li>
                    <li><a class="@if($_SERVER['REQUEST_URI'] == '/shop') active @endif" href="/shop">Shop</a></li>
                    <li><a class="@if($_SERVER['REQUEST_URI'] == '/about-us') active @endif" href="/about-us">About</a></li>
                    @guest
                        <li><a class="@if($_SERVER['REQUEST_URI'] == '/login' || $_SERVER['REQUEST_URI'] == '/register') active @endif" href="{{ route('login') }}">Login/Register</a></li>
                        @else
                            <li><a class="@if($_SERVER['REQUEST_URI'] == '/account') active @endif" href="/account">Account</a></li>
                    @endguest
                </ul>
            </div>
            
            <div class="mobile_top_nav">
                <div class="brand">
                    <img src="{{ asset('assets/images/brand/header_logo.png') }}" alt="{{ config('app.name') }} Logo">
                </div>

                <div id="open_menu">
                    <i class="fa fa-bars"></i>
                </div>
            </div>

            <div id="menu" class="mobile-nav">
                <div class="inner_nav">
                    <div id="nav_close">&times;</div>
                    <div class="brand_logo">
                        <img src="{{ asset('assets/images/brand/header_logo.png') }}" alt="{{ config('app.name') }} Logo">
                    </div>
                    <div class="items">
                        <ul>
                            <li><a class="@if($_SERVER['REQUEST_URI'] == '/') active @endif" class="active" href="/">Home</a></li>
                            <li><a class="@if($_SERVER['REQUEST_URI'] == '/appointment') active @endif" href="#">Services</a>
                                <ul>
                                    <li><a href="/services">Hospital Booking</a></li>
                                    <li><a href="/services">Salon Booking</a></li>
                                </ul>
                            </li>
                            <li><a class="@if($_SERVER['REQUEST_URI'] == '/shop') active @endif" href="/shop">Shop</a></li>
                            <li><a class="@if($_SERVER['REQUEST_URI'] == '/about-us') active @endif" href="/about-us">About</a></li>
                            @guest
                                <li><a class="@if($_SERVER['REQUEST_URI'] == '/login' || $_SERVER['REQUEST_URI'] == '/register') active @endif" href="{{ route('login') }}">Login/Register</a></li>
                                @else
                                    <li><a class="@if($_SERVER['REQUEST_URI'] == '/account') active @endif" href="/account">Account</a></li>
                            @endguest
                            <li style="color: #64bf47;">
                                <i class="fa-solid fa-language"></i> Language : <div style="display: inline;" id="md_google_translate_element" nostranslate></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mobile_bottom_nav">
                <div class="searchbox">
                    <form  action="" onsubmit="return false;">
                        <div id="md_search" class="mobile_search_wrap">
                            <md-search />
                        </div>
                        <div onclick="location.href='/cart';" class="cart">
                            <p><i class="fa-solid fa-cart-shopping"></i></p>
                        </div>    
                    </form>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <style>
        .container .about p {
            line-height: 21px;
            font-family: auto;
            font-weight: normal;
        }
    </style>

    <footer>
        <div class="container">
            <div class="row row-cols-1 row-cols-xl-3 row-cols-lg-3 row-cols-md-3 row-cols-sm-2">
                <div class="col">
                    <div class="brand">
                        <img src="{{ asset('assets/images/brand/header_logo.png') }}" alt="{{ config('app.name') }} Logo">
                    </div>
                    <div class="about">
                        <p>Ayuniya is the world's first automated herbal product marketplace and automated ayurvedic and herbal medicine hospital booking service</p>
                    </div>
                    <div class="contact_d">
                        <div class="dtl"><i class="fa fa-phone"></i> <a href="tel:+947612345678">+94 81 242 1848</a></div>
                        <div class="dtl"><i class="fa fa-envelope"></i> <a href="mailto:info@ayuniya.com">info@ayuniya.com</a></div>
                        <div class="dtl"><i class="fa-solid fa-location-dot"></i> Kandy, Sri Lanka</div>
                    </div>
                </div>

                <div class="col">
                    <div class="head mt-lg-5 mt-md-5 mt-sm-3">Quick links</div>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/appointment">Service</a></li>
                        <li><a href="/shop">Shop</a></li>
                        <li><a href="/about-us">About Us</a></li>
                        @guest
                        <li><a href="{{ route('login') }}">Login/Register</a></li>
                        @else
                        <li><a href="/account">Account</a></li>
                        @endguest
                    </ul>
                </div>

                <div class="col">
                    <div class="head mt-lg-5 mt-md-5 mt-sm-3">Customer service</div>
                    <ul>
                        <li><a href="/about-us/#Conatct">Contact Us</a></li>
                    </ul>
                    <div class="head">Social links</div>
                    
                    <div class="icons">
                        <ul style="margin-left: 0;">
                            <li><a href=""><i class="fa-brands fa-facebook-f"></i></a></li>
                            <li><a href=""><i class="fa-brands fa-instagram"></i></a></li>
                            <li><a href=""><i class="fa-brands fa-twitter"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            {{-- <div class="footer_category">
                <div class="head">Featured Categories</div>
                <ul>
                    <li><a href="">Category 1,</a></li>
                    <li><a href="">Category 1,</a></li>
                    <li><a href="">Category 1,</a></li>
                    <li><a href="">Category 1,</a></li>
                    <li><a href="">Category 1,</a></li>
                    <li><a href="">See more...</a></li>
                </ul>
            </div> --}}
            <div class="copyright">
                Copyright &copy; @php echo date('Y'); @endphp DEF TESNO (PVT) LTD. All Rights Recieved
            </div>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>




<script>
// ======== Mobile Navigation========= //

var open_menu = document.getElementById("open_menu");
var nav_close = document.getElementById("nav_close");
var menu = document.getElementById("menu");

$(open_menu).click(function () {
    menu.classList.add("mobile_nav_open");
});

$(nav_close).click(function () {
    menu.classList.remove("mobile_nav_open");
});

</script>



</body>
</html>
