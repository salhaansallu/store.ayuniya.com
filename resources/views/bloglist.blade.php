@extends('layouts.app')

@section('content')
    <div class="blog-container">
        <div class="blog_hero" style="background-image: url('{{ asset('assets/images/blog/bloghero.png') }}')">
            <div class="content">
                <h1>Explore 100+ articles related to Ayurveda</h1>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard
                    dummy text ever since.
                </p>
                <div class="searchbar">
                    <form action="">
                        <input type="text" placeholder="Search 100+ blogs here"><button class="primary_btn">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="site-container">
        <div class="blog-wrap">
            <div class="blog">
                <div class="img">
                    <img src="{{ asset('assets/images/blog/blog1.png') }}" alt="">
                </div>
                <div class="title">Evolution of ayurveda</div>
                <div class="description">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                </div>
                <div class="readmore">
                    <a href="" class="primary_btn">Read more</a>
                </div>
            </div>
            <div class="blog">
                <div class="img">
                    <img src="{{ asset('assets/images/blog/blog1.png') }}" alt="">
                </div>
                <div class="title">Evolution of ayurveda</div>
                <div class="description">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                </div>
                <div class="readmore">
                    <a href="" class="primary_btn">Read more</a>
                </div>
            </div>
            <div class="blog">
                <div class="img">
                    <img src="{{ asset('assets/images/blog/blog1.png') }}" alt="">
                </div>
                <div class="title">Evolution of ayurveda</div>
                <div class="description">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                </div>
                <div class="readmore">
                    <a href="" class="primary_btn">Read more</a>
                </div>
            </div>
            <div class="blog">
                <div class="text-blog">
                    <div class="title">Evolution of ayurveda</div>
                    <div class="description">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                    </div>
                    <div class="readmore">
                        <a href="" class="primary_btn">Read more <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="text-blog">
                    <div class="title">Evolution of ayurveda</div>
                    <div class="description">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                    </div>
                    <div class="readmore">
                        <a href="" class="primary_btn">Read more <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="text-blog">
                    <div class="title">Evolution of ayurveda</div>
                    <div class="description">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                    </div>
                    <div class="readmore">
                        <a href="" class="primary_btn">Read more <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="blog">
                <div class="img">
                    <img src="{{ asset('assets/images/blog/blog1.png') }}" alt="">
                </div>
                <div class="title">Evolution of ayurveda</div>
                <div class="description">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                </div>
                <div class="readmore">
                    <a href="" class="primary_btn">Read more</a>
                </div>
            </div>

            <div class="blog">
                <div class="img">
                    <img src="{{ asset('assets/images/blog/blog1.png') }}" alt="">
                </div>
                <div class="title">Evolution of ayurveda</div>
                <div class="description">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                </div>
                <div class="readmore">
                    <a href="" class="primary_btn">Read more</a>
                </div>
            </div>

            <div class="blog">
                <div class="img">
                    <img src="{{ asset('assets/images/blog/blog1.png') }}" alt="">
                </div>
                <div class="title">Evolution of ayurveda</div>
                <div class="description">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                </div>
                <div class="readmore">
                    <a href="" class="primary_btn">Read more</a>
                </div>
            </div>
            <div class="blog">
                <div class="img">
                    <img src="{{ asset('assets/images/blog/blog1.png') }}" alt="">
                </div>
                <div class="title">Evolution of ayurveda</div>
                <div class="description">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                </div>
                <div class="readmore">
                    <a href="" class="primary_btn">Read more</a>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-xxl-2 row-cols-xl-2 row-cols-lg-2">
                <div class="col">
                    <div class="blog w-100">
                        <div class="text-blog">
                            <div class="title">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                Lorem Ipsum has been.</div>
                            <div class="description">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                                been the industry's standard dummy text ever since Lorem Ipsum is simply dummy text of the
                                printing Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </div>
                            <div class="readmore">
                                <a href="" class="primary_btn">Read more <i
                                        class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                        <div class="text-blog">
                            <div class="title">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                Lorem Ipsum has been.</div>
                            <div class="description">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                                been the industry's standard dummy text ever since Lorem Ipsum is simply dummy text of the
                                printing Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </div>
                            <div class="readmore">
                                <a href="" class="primary_btn">Read more <i
                                        class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                        <div class="text-blog">
                            <div class="title">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                Lorem Ipsum has been.</div>
                            <div class="description">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                                been the industry's standard dummy text ever since Lorem Ipsum is simply dummy text of the
                                printing Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </div>
                            <div class="readmore">
                                <a href="" class="primary_btn">Read more <i
                                        class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="blog w-100 last-wide-blog">
                        <div class="img">
                            <img src="{{ asset('assets/images/blog/blog1.png') }}" alt="">
                        </div>
                        <div class="title">Evolution of ayurveda</div>
                        <div class="description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                            the industry's standard dummy text ever since Lorem Ipsum is simply dummy text of the printing
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </div>
                        <div class="readmore">
                            <a href="" class="primary_btn">Read more</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
