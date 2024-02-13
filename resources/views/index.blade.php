@extends('layouts.app')

@section('content')

    <style>
        body {
            background-color: #fff;
        }
    </style>

    <div class="hero_section site-container">
        {{-- ================== Carousel slide ================== --}}
        <div id="heroCarousel" class="carousel carousel slide" data-bs-ride="carousel">

            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">

                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100"
                            src="https://image.ayuniya.com/banner/banner_1.webp" class="d-block w-100"
                            alt="Ayuniya Hero Banner">
                    </div>

                    <div class="carousel-item">
                        <img class="d-block w-100"
                            src="https://image.ayuniya.com/banner/banner_2.webp" class="d-block w-100"
                            alt="Ayuniya Hero Banner">
                    </div>

                    <div class="carousel-item">
                        <img class="d-block w-100"
                            src="https://image.ayuniya.com/banner/banner_3.webp" class="d-block w-100"
                            alt="Ayuniya Hero Banner">
                    </div>

                    <div class="carousel-item">
                        <img class="d-block w-100"
                            src="https://image.ayuniya.com/banner/banner_4.webp" class="d-block w-100"
                            alt="Ayuniya Hero Banner">
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ================== On Sale products ================== --}}

    <div class="onsale_products site-container">
        <div class="product_header">
            <h4>On Sale</h4>
            <b><a href="/shop"> see more </a></b>
        </div>
        <div class="wrapper">
            @foreach ($products as $product)
                <div class="col">
                    <div class="wrapper">

                        <div class="details">
                            <div class="item_name"><a
                                    href="{{ productURL($product->id, $product->product_name) }}">@if(strlen($product->product_name)>23) {{ substr($product->product_name, 0, 23) }}... @else {{ $product->product_name }} @endif</a>
                            </div>
                            <b>{{ min_price($product->varient)[0] }}</b>
                        </div>
                        <div class="image">
                            <a href="{{ productURL($product->id, $product->product_name) }}"><img loading="lazy"
                                    src="{{ validate_image($product->banner) }}" alt=""></a>
                        </div>

                    </div>

                </div>
            @endforeach

        </div>
    </div>

        {{-- ================== Categories ================== --}}

        <section class="categories site-container">
            <div class="product_header">
                <h4>Categories</h4><b><a href="/shop"> see more </a></b>
            </div>

            <div class="category">
                <div class="cato" onclick="location.href='/category/3/oil'" alt="">
                    <div class="wrapper">
                        <div class="details">
                            <div class="item_name"><a>Ayurvedic and Herbal Oils</a>
                            </div>
                            {{-- <b>dgrhryheyr</b> --}}
                            <div class="shop_now">
                                <a href="/category/3/oil"><button>Shop Now</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cato" onclick="location.href='/category/4/drinks'" alt="">
                    <div class="wrapper">
                        <div class="details">
                            <div class="item_name"><a>Natural & Herbal Drinks</a>
                            </div>
                            <b></b>
                            <div class="shop_now">
                                <a href="/category/4/drinks"><button>Shop Now</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cato" onclick="location.href='/category/6/herbal-porridge'" alt="">
                    <div class="wrapper">
                        <div class="details">
                            <div class="item_name"><a>Natural & Herbal Porridge</a>
                            </div>
                            <b></b>
                            <div class="shop_now">
                                <a href="/category/6/herbal-porridge"><button>Shop Now</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cato" onclick="location.href='category/1/foods'" alt="">
                    <div class="wrapper">
                        <div class="details">
                            <div class="item_name"><a>Organic Food</a>
                            </div>
                            <b></b>
                            <div class="shop_now">
                                <a href="category/1/foods"><button>Shop Now</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </section>


        {{-- ================== Add Banner 2 ================== --}}

        <div class="banner container d-none">
            <div class="inner-banner"></div>
        </div>

        @if (isset($_GET['pid']))
            <script>
                function getCookie(cookieName) {
                    let cookie = {};
                    document.cookie.split(';').forEach(function(el) {
                        let [key, value] = el.split('=');
                        cookie[key.trim()] = value;
                    })
                    return cookie[cookieName];
                }

                function setCookie(name, value) {
                    var expires = "";
                    document.cookie = name + "=" + (value || "") + "; path=/";
                }

                $(document).ready(function() {
                    if (getCookie('order_confirmed') == undefined) {
                        $("#orderConfimation").modal("show");
                        setCookie("order_confirmed", 'true');
                    }
                });
            </script>

            <div class="modal fade" id="orderConfimation" tabindex="-1" aria-labelledby="orderConfimationLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="border: 0;">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="thankyou">
                                <h1>Your order has been received</h1>
                                <div class="icon">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                                <h4>Thank you for your purchase !</h4>
                                <p>Your order ID : <span>{{ sanitize($_GET['pid']) }}</span></p>
                                <p>
                                    @if (sanitize($_GET['_region']) == 'Sri Lanka')
                                        Please make sure you have the payment when the order is received
                                    @else
                                        Our sales team will contact you shortly
                                    @endif
                                </p>
                                <div class="btn">
                                    <a href="/account/orders">Order details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    @endsection
