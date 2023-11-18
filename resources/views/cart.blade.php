@extends('layouts.app')

@section('content')
    <div class="cart">
        <div class="header">
            <h1>Your shopping cart</h1>
        </div>

        <div class="table_head">
            <div class="row row-cols-auto">
                <div class="col item">Item</div>
                <div class="col price">Price</div>
                <div class="col qty">QTY</div>
                <div class="col total">Total</div>
                <div class="col remove">Remove</div>
            </div>
        </div>

        <div class="table_data">
            @isset($carts)
            @if (is_object($carts))
            @foreach ($carts as $cart)
            <div class="row row-cols-auto" id="{{ $cart->product_id }}">
                <div class="col item">
                    <div class="dtls">
                        <div class="img"><img src="{{ validate_image(getProducts($cart->product_id)['banner']) }}" alt=""></div>
                        <div class="item_name">
                            <div class="name"><a href="{{ productURL(getProducts($cart->product_id)['id'], getProducts($cart->product_id)['product_name']) }}">{{ getProducts($cart->product_id)['product_name'] }}</a></div>
                            {{-- <div class="category"><a href=""></a></div> --}}
                            <div class="sku">{{ getProducts($cart->product_id)['varient'][0]['sku'] }}</div>
                            <div class="veriant">{{ getProducts($cart->product_id)['varient'][0]['v_name'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col price">{{ currency(getProducts($cart->product_id)['varient'][0]['sales_price']) }}</div>
                <div class="col qty"><input type="number" value="{{ $cart->cart_qty }}" min="1" max="10"><button id="update_cart" onclick="updateCart('{{ $cart->product_id }}', document.querySelector('#{{ $cart->product_id }} input').value)">Update</button></div>
                <div class="col total">{{ currency(getProducts($cart->product_id)['varient'][0]['sales_price']*$cart->cart_qty) }}</div>
                <div class="col remove"><i onclick="deletecartajax('{{ $cart->product_id }}')" class="fa fa-trash"></i></div>
            </div>
            @endforeach
            @else
                <div class="col-12 text-center mt-4" style="font-size: 17px;">{{ $carts }}</div>
            @endif
            @endisset

        </div>
    </div>

    <div class="checkout">
        <div class="inner">
            <div class="total_inc mt-lg-3 mt-md-3" id="total_inc">Cart Total: <span>{{ get_cart_total() }}</span></div>
            <div class="checkout_btn mt-lg-3 mt-md-3"><a href="/checkout"><button><i class="fa-regular fa-credit-card"></i> Proceed to checkout</button></a></div>
        </div>
    </div>


    <script>
        function updateCart(pro_sku, pro_qty){
            $.ajax({
                type: "post",
                url: "/cart",
                data: {action: 'update_cart', sku: pro_sku, qty: pro_qty, _token: '{{ csrf_token() }}'},
                dataType: "json",
                success: function (response) {
                    if (response.error == 1) {
                        toastr.error(response.msg, "Error");
                        if (response.msg == "not_loggedin") {
                            location.href="/login";
                        }
                    }
                    else if(response.error == 0) {
                        toastr.success(response.msg, "Success");
                        if (response.msg == "Cart updated successfully") {
                            location.reload();
                        }
                    }
                    else{
                        toastr.error("Sorry, something went wrong", "Error");
                    }
                }
            });
        }

        function deletecartajax(value){
            if(value != "" || value != " ") {
                $.ajax({
                    type: "post",
                    url: "/cart",
                    data: {action: 'delete_cart', sku: value, _token: '{{ csrf_token() }}'},
                    dataType: "json",
                    success: function (response) {
                        if (response.error == 0) {
                            toastr.success(response.msg, "Success");
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                        else{
                            toastr.error(response.msg, "Error")
                        }
                    }
                });
            }
            else{
                toastr.error("Sorry, something went wrong. Please refresh the page", "Error");
            }
        }
    </script>
@endsection
