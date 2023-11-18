@extends('layouts.app')

@section('content')
    <div class="site-container">
        <div class="blog_hero" style="background-image: url('{{ asset('assets/images/blog/bloghero.png') }}')">
            <div class="content">
                <h1>Explore 100+ articles related to Ayurveda</h1>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard
                    dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make
                    a type specimen book. 
                </p>
                <div class="searchbar">
                    <form action="">
                        <input type="text" placeholder="Search 100+ blogs here"><button class="primary_btn">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
