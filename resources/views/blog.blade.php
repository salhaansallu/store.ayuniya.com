@extends('layouts.app')

@section('content')
    <div class="row blog-container blog-details">
        <div class="col-12 col-xxl-8 col-xl-8">
            <div class="main_img">
                <img src="{{ asset('blog_images/' . $blog->blog_image) }}" alt="">
            </div>
            <div class="single-blog"><br>
                <div class="text-success"><h1>
                    {{ $blog->blog_Title }}</h1>
                </div>
                <div class="description"><h4>
                    <b>{{ $blog->blog_dis }}</b></h4>
                </div>
            </div>
            <div class="content">
                {!! $blog->blog_content !!}
            </div>
        </div>

        <div class="col-12 col-xxl-4 col-xl-4">
            <div class="blog">
                @foreach ($blogs as $blog)
                    <div class="text-blog">
                        <div class="img">
                            <img src="{{ asset('blog_images/' . $blog->blog_image) }}" alt="">
                        </div>
                        <div class="ctnt">
                            <div class="title">{{ $blog->blog_Title }}</div>
                            <div class="description">
                                {{ $blog->blog_dis }}
                            </div>

                            <div class="readmore">
                                <a href="{{ route('blog.getBlog', $blog->id) }}" class="primary_btn">Read more <i
                                        class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach





            </div>
        </div>
    </div>
    <div class="site-container">


        <div class="recomanded">
            <div class="head">
                <h4>More Blogs</h4>
            </div>

            <div class="blog-wrap">

                @foreach ($blogs as $blog)
                    <div class="blog">
                        <div class="img">
                            <img src="{{ asset('blog_images/' . $blog->blog_image) }}" alt="">
                        </div>
                        <div class="title">{{ $blog->blog_Title }}</div>
                        <div class="description">
                            {{ $blog->blog_dis }}
                        </div>
                        <div class="readmore">
                            <a href="{{ route('blog.getBlog', $blog->id) }}" class="primary_btn">Read more</a>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
