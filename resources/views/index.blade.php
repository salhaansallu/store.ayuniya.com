@extends('layouts.app')

@section('content')

<style>
    body{www
        background-color: #fff;
    }
</style>

<div class="hero_section">
    <div class="left-category">
        <div class="category_head">Top Categories</div>
        <ul>
            @isset($categories)
    
            @foreach ($categories as $category)
            <li><a data-bs-toggle="collapse" href="#{{ str_replace(' ', '_', $category->category_name) }}" role="button" aria-expanded="false" aria-controls="{{ $category->category_name }}"> {{ $category->category_name }} <span> <i class="fa-solid fa-angle-right"></i> </span></a>
                <div class="collapse" id="{{ str_replace(' ', '_', $category->category_name) }}">
                    <div class="card card-body">
                        <ul>
                            @foreach ($category->subcategories as $sub)
                            <li><a href="{{ categoryURL($sub->id, $sub->sub_category_name) }}"> {{ $sub->sub_category_name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            @endforeach
                
            @endisset
        </ul>
    </div>

    {{-- ================== Carousel ================== --}}
    <div id="heroCarousel" class="carousel carousel slide" data-bs-ride="carousel">
        {{-- <div class="carousel-indicators">
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div> --}}
        <div class="carousel-inner">
          <div class="carousel-item active" data-bs-interval="10000">
            <img onclick="location.href='/'" src="{{ asset('assets/images/carousel/carousal-banner-lg.jpg') }}" class="d-block w-100" alt="{{ config('app.name') }} Hero Banner">
            <div class="carousel-caption d-none d-md-block">
            </div>
          </div>
        </div>
        {{-- <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button> --}}
        {{-- <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button> --}}
    </div>
</div>

{{-- ================== Add Banner ================== --}}

    <div class="banner container">
      <div class="inner-banner">
        <a href="{{ route('adbooking1') }}"><img src="{{ asset('assets/images/banner/ad-banner-1.jpg') }}" alt=""></a>
      </div>
    </div>

{{-- ================== On Sale products ================== --}}

    <div class="onsale_products container">

        <div class="controls">
            <div id="leftcontrol" class="left_control">
                <i class="fa fa-angle-left"></i>
            </div>

            <div id="rightcontrol" class="right_control">
                <i class="fa fa-angle-right"></i>
            </div>
        </div>
        
        <div class="product_header">
            <h4>On Sale</h4>
        </div>

        <div class="product_body">
            <div id="productscroller" class="rows">

                @foreach ($products as $product)

                <div id="clientwidth" class="col">
                    <div class="product_wrap">
                        <div class="image">
                            <a href="{{ productURL($product->id, $product->product_name) }}"><img loading="lazy" src="{{ validate_image($product->banner) }}" alt=""></a>
                        </div>
                        <div class="details">
                            <div class="item_name"><a href="{{ productURL($product->id, $product->product_name) }}">{{ $product->product_name }}</a></div>
                            <b>{{ min_price($product->varient)[0] }}</b>
                            <div class="sales_price">
                                <del>{{ min_price($product->varient)[1] }}</del>
                            </div>
                            <div class="add_cart_btn">
                                <a href="{{ productURL($product->id, $product->product_name) }}"><button>View</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                    
                @endforeach
                
            </div>
        </div>
        <div class="all_products">
            <a href="/shop">See all Products</a>
        </div>
    </div>


{{-- ================== Categories ================== --}}

    <section class="categories container">
        <div class="heading">
            <div class="headline"></div>
            <h2>Explore popular categories</h2>
        </div>

        <div class="category d-flex">
            <div class="cat1">
                <img loading="lazy" onclick="location.href='/shop'" src="{{ asset('assets/images/categories/cat-1.jpg') }}" alt="">
            </div>
            <div class="cat2">
                <img loading="lazy" onclick="location.href='/shop'" src="{{ asset('assets/images/categories/cat-2.jpg') }}" alt=""><br>
                <img loading="lazy" onclick="location.href='/shop'" src="{{ asset('assets/images/categories/cat-3.jpg') }}" alt="">
            </div>
        </div>

        <div class="footer">
            <b><a href="/shop"> Explore all categories <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i> </a></b>
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
            document.cookie.split(';').forEach(function (el) {
                let [key, value] = el.split('=');
                cookie[key.trim()] = value;
            })
            return cookie[cookieName];
        }

        function setCookie(name, value) {
            var expires = "";
            document.cookie = name + "=" + (value || "") + "; path=/";
        }

        $(document).ready(function () {
            if (getCookie('order_confirmed') == undefined) {
                $("#orderConfimation").modal("show");
                setCookie("order_confirmed", 'true');
            }
        });
    </script>

    <div class="modal fade" id="orderConfimation" tabindex="-1" aria-labelledby="orderConfimationLabel" aria-hidden="true">
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
                    <p>Please make sure you have the payment when the order is received</p>
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
