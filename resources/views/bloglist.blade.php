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
            {{-- <div class="blog">
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
            </div> --}}

            {{--
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
            </div> --}}

            <div class="row row-cols-1 row-cols-xxl-2 row-cols-xl-2 row-cols-lg-2">
                <div class="col">
                    <div class="blog w-100">


                        <div class="text-blog">
                            <div class="title">
                                <h2>History of Muhandiram Vedha Pramparawa

                                    &

                                    Dr. M.R.S.R.Muhandirm</h2>
                            </div>
                            <div class="description">
                                After the reign of kings, Sri Lanka's traditional medicine was carried through generations
                                of Sri
                                lanka’s Traditional Doctors.
                                Among the ancient Traditional practitioner of Sri Lanka a generation known as Mukanthram was
                                honored as Veda Mukandram
                                According to Ranks of the British Muhandirams Veda Muhandirams (titular) - awarded as an
                                honor for native physicians
                                Lokhu Banda Muhandim was the senior Most Person of Muhendram Veda Paramparava who
                                specialized in Sarvanga (general) Treatments. During the British era, he fought for Natty
                                and went to
                                prission.
                                Muhandram's generation does not stop with Lokupanda Muhandram, it continues with Dr. Ranjan
                                Muhandram's father, but he is not much involved in Vedakama.
                                But his son Dr.Ranjan Muhandram showed great interest in learning traditional healing
                                methods from
                                his grandfather. Today he is the only person who practices the traditional treatment method
                                of
                                Muhandiram Parampara.
                                As a gift of God, Dr. Ranjan Muhandra's mother Reeta Ranjan Jayasinghe also belongs to One
                                of
                                Famous Vedha Pramparawa known as Galagama Hamumatha. She practices Sarvanga Vedagama, her
                                Father Practice Visha Vedakama and Sarvanga Vedakama, also her mother was Specialised in
                                Ancient Eye Treatments. Dr.Ranjan Muhandiram’s Uncle galagama hamumahatha who was famous
                                sarwanga treatment Specialist in matara and Embilipitiya, Sabaragamuwa area.
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="blog w-100 last-wide-blog">
                        <div class="img">
                            <img src="{{ asset('assets/images/blog/blog1.png') }}" alt="">
                        </div>

                        <div class="description">
                            <br>
                            Dr.Ranjan Muhandram is the only current traditional doctor successor of Muhandram Veda
                            Parambarawa and Galagamuwa Veda Parambarawa . his Treatment method are very unique than other
                            srilanka’s traditional practitioners because he has a great treatment methods came from both
                            genarations.
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
