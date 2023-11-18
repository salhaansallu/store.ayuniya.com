@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row row-cols-lg-4 row-cols-md-3">
            <div class="col">
                <a href="/appointment">
                    <div class="logo">
                        <img src="{{ validateCompanyLogo("hospital.png") }}" alt="">
                    </div>
                    <div class="c_name">
                        <b>Siyrata Muhandiram Hospital</b>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection