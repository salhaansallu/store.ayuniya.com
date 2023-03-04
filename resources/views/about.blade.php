@extends('layouts.app')

@section('content')

<style>
    body{
        background-color: #fff
    }
</style>

<div class="carousel">
    <div class="bg" style="background-image: url('{{ asset('assets/images/about/banner.png') }}');"></div>
</div>

<div class="services">
    <div class="about-header"><h1>What we do</h1></div>
    <div class="row row-cols-2">
        <div class="col">
            <div class="image">
                <img src="{{ asset('assets/images/about/brand.png') }}" alt="">
            </div>
            <div class="name">eCommerce service</div>
            <div class="text">World's first fully automated herbal medicine market place.</div>
        </div>

        <div class="col">
            <div class="image">
                <img src="{{ asset('assets/images/about/brand.png') }}" alt="">
            </div>
            <div class="name">Booking service</div>
            <div class="text">World's first fully automated all in one booking service including ayurvedic and herbal medicine hospitals.</div>
        </div>
    </div>
</div>


<div class="about_ctnt">
    <div class="row row-cols-2">
        <div class="col">
            <div class="content">
                <div class="head">Proud member of DEF TESNO (PVT) LTD</div>
                <div class="sub">Ayuniya is the world's first automated herbal product marketplace and automated ayurvedic and herbal medicine hospital booking service proudly presented by <strong>DEF TESNO (PVT) LTD</strong></div>
                <div class="learn_more">
                    <a href="">Learn more</a>
                </div>
            </div>
        </div>

        <div class="col">
            <img src="{{ asset('assets/images/about/ayuniya-retail.jpg') }}" alt="">
        </div>
    </div>
</div>

<div class="banner">
    <img src="{{ asset('assets/images/about/banner-1.png') }}" alt="">
    <div class="caption">
        World's first fully automated herbal medicine market place.
        {{-- <div class="sub">
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima, doloremque molestiae aliquam deleniti distinctio corporis amet reiciendis ipsa
        </div> --}}

        <div class="shop_now">
            <a href="/shop">Shop now</a>
        </div>
    </div>
</div>

<div class="testimonial">
    <div class="about-header"><h1>What people say about us</h1></div>

    <div class="row row-cols-2">
        <div class="col">
            <div class="qoute">
                <i class="fa-solid fa-quote-left"></i>
            </div>
            <div class="say">
                This is a useful and easy service for people who want to buy medicines online. People will appreciate the convenience to get these medicines from the comfort of their own home and having them delivered right to their door.

                <div class="commenter">
                    Siyarata Ayurveda (PVT) LTD
                </div>
            </div>
        </div>

        <div class="col">
            <div class="qoute">
                <i class="fa-solid fa-quote-left"></i>
            </div>
            <div class="say">
                I’m in out side of Sri Lanka and I wanted to buy Goraka choornaya 
and ordered the goraka choornaya from Ayuniya website they delivered the the Products within a week to my home in Sri Lanka. Thank you so much.
                <div class="commenter">
                Ayshwarya Ayurveda
                </div>
            </div>
        </div>
    </div>
</div>


<div class="stay_in_touch" id="Conatct">
    <div class="about-header"><h1>Stay in touch</h1></div>

    <div class="row row-cols-2">
        <div class="col">
            <div class="icon">
                <i class="fa-solid fa-phone"></i>
            </div>
            <div class="header">For inquiries</div>
            <div class="sub">
                If you have any question call or contact us
            </div>
            <div class="dtls">
                <div class="email">Email : <span>info@ayuniya.com</span></div>
                <div class="email">Phone number : <span>+94 81 242 1848</span></div>
                <div class="email">Address : <span>Kandy, Sri Lanka</span></div>
            </div>
        </div>

        <div class="col">
            <form method="post" onsubmit="alert('This feature is currently not available');return false;">
                <div class="head">Send us a message</div>

                <div class="txt_field">
                    <div class="label">Full name</div>
                    <div class="input">
                        <input type="text" name="" id="">
                    </div>
                </div>

                <div class="txt_field">
                    <div class="label">Email</div>
                    <div class="input">
                        <input type="text" name="" id="">
                    </div>
                </div>

                <div class="txt_field">
                    <div class="label">Phone number</div>
                    <div class="input">
                        <input type="text" name="" id="">
                    </div>
                </div>

                <div class="txt_field">
                    <div class="label">Message</div>
                    <div class="input">
                        <textarea name="" id="" cols="30" rows="7"></textarea>
                    </div>
                </div>

                <button type="submit" class="primary_btn" style="margin-bottom: 0;">Send</button>

            </form>
        </div>
    </div>
</div>
    
@endsection
