@extends('layouts.app')

@section('content')

<div class="account_nav">
    <div class="row row-cols-auto">
        <div class="col"><a href="/account/my-details">My details</a></div>
        <div class="col"><a href="/account/address-book">Address book</a></div>
        <div class="col"><a href="/account/orders">Orders</a></div>
        <div class="col"><a href="/account/change-password">Change password</a></div>
        <div class="col"><a href="#" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">Logout</a></div>
    </div>
</div>

<div class="main_account site-container">
    <div class="side_bar">
        <div class="brad_crumb">Home > <span>My account</span></div>

        <div class="bar">
            <div class="head">My account</div>
            <ul>
                <li><a href="/account/my-details">My details</a></li>
                <li><a href="/account/address-book">Address book</a></li>
                <li><a href="/account/orders">Orders</a></li>
                <li><a href="/account/change-password">Change password</a></li>
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">Logout</a></li>
                <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </ul>
        </div>
    </div>

    <div class="forms">
        @yield('account_content')
    </div>
</div>

@endsection
