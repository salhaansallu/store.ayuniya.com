@extends('account')

@section('account_content')
<div class="card">
    <form action="">
        <div class="form_header">
            <h5>Orders</h5>
            <div class="sub">All orders</div>
        </div>
    
        @isset($orders)
            @if ($orders != "No orders")
                @foreach ($orders as $order)
                    <div class="orders">
                        <div class="order_head">Order ID : #{{ $order->order_number }} <span>Date: {{ date_format($order->created_at, "d-m-Y") }}</span> <span>Total: {{ getOrderTotal($order->orders, $order->delivery_charge, $order->discount) }}</span> <span>Discount: {{ currency($order->discount) }}</span></div>
                        <div class="products">
                            @foreach ($order->orders as $item)
                                <div class="item">
                                    <div class="image">
                                        <div class="img">
                                            <img src="{{ validate_image(getProducts($item->product_id)['varient'][0]['image_path']) }}" alt="">
                                        </div>
                                        <div class="dtls">
                                            <div class="name"><a href="{{ productURL(getProducts($item->product_id)['id'], getProducts($item->product_id)['product_name']) }}">{{ getProducts($item->product_id)['product_name'] }}</a></div>
                                            <div class="cat">{{ getProductCategory(getProducts($item->product_id)['category'])[0]['sub_category_name'] }}</div>
                                            <div class="price">{{ currency($item->total/$item->qty) }} x {{ $item->qty }}</div>
                                        </div>
                                    </div>
                                    <div class="status {{ $order->status }}">{{ orderStatus($order) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                @else
                <div class="col-12 text-center fs-4 mb-3">{{ $orders }}</div>
            @endif
        @endisset
        
    </form>
</div>
@endsection