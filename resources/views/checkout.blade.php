@extends('layouts.app')

@section('content')

<div class="checkout">
    <div class="container m-auto row row-cols-auto">
        <div class="col overview">
            @isset($products)
            <div class="head">Order overview <span>(@isset($qty) 1 @else {{ getCartCount() }} @if(getCartCount() > 1) items @else item @endif @endisset)</span></div>
            <div class="products">
                @foreach ($products as $product)
                <div class="item">
                    <div class="details">
                        <div class="image">
                            <img src="{{ validate_image($product->image_path) }}" alt="">
                        </div>
                        <div class="dtls">
                            <div class="name"><b>{{ $product->product_name }}</b></div>
                            <div class="cat">varient: {{ $product->v_name }}</div>
                            <div class="price_qty">
                                <div class="qty">Qty: @isset($qty) {{ $qty }} @else {{ $product->cart_qty }} @endisset</div>
                                <div class="price">@isset($qty) {{ currency($product->sales_price * $qty) }} @else {{ currency($product->sales_price * $product->cart_qty) }} @endisset</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endisset
        </div>

        <div class="col address">
            <div class="head">Delivery address</div>

            @if (getAddress("shipping")['has'] == true)
            <div class="txt_field">
                <div class="label">Delivery address <span>*</span></div>
                <div class="input"><input type="text" name="address1" id="address1" placeholder="delivery address" value="{{ getAddress("shipping")['address1'] }}"></div>
            </div>

            <div class="txt_field">
                <div class="label">Postal code <span>*</span></div>
                <div class="input"><input type="text" name="postal" id="postal" placeholder="postal code" value="{{ getAddress("shipping")['postal'] }}"></div>
            </div>

            <div class="txt_field">
                <div class="label">City <span>*</span></div>
                <div class="input"><input type="text" name="city" id="city" placeholder="city" value="{{ getAddress("shipping")['city'] }}"></div>
            </div>

            <div class="txt_field">
                <div class="label">Country <span>*</span></div>
                <div class="input">
                    <select name="country" id="country">
                        @empty(getAddress('shipping')['country'])
                        <option value="">-- Select Country --</option>
                        @else
                        <option value="{{ getAddress('shipping')['country'] }}">{{ getAddress('shipping')['country'] }}</option>
                        <option value="" disabled></option>
                        @endempty
                        @foreach (country("get") as $del_country)
                        <option value="{{ $del_country }}">{{ $del_country }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @else

            <div class="txt_field">
                <div class="label">Delivery address <span>*</span></div>
                <div class="input"><input type="text" name="address1" id="address1" placeholder="delivery address" value=""></div>
            </div>

            <div class="txt_field">
                <div class="label">Postal code <span>*</span></div>
                <div class="input"><input type="text" name="postal" id="postal" placeholder="postal code" value=""></div>
            </div>

            <div class="txt_field">
                <div class="label">City <span>*</span></div>
                <div class="input"><input type="text" name="city" id="city" placeholder="city" value=""></div>
            </div>

            <div class="txt_field">
                <div class="label">Country <span>*</span></div>
                <div class="input">
                    <select name="country" id="country">
                        @foreach (country("get") as $del_country)
                        <option value="{{ $del_country }}">{{ $del_country }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            {{-- <div class="remember">
                <input type="checkbox" name="" id=""> Save address for next time
            </div> --}}
        </div>

        <div class="col summary">
            <div class="head">Order summary</div>

            <div class="amount_wrap">
                <div class="sub_total">
                    <div class="txt">Sub total :</div>
                    <div class="amount" id="order_sub_total">@isset($qty) {{ currency($products[0]->sales_price * $qty) }} @else {{ get_cart_total() }} @endisset</div>
                </div>
    
                <div class="sub_total">
                    <div class="txt">Delivery charge :</div>
                    <div class="amount" id="order_delivery">@isset($qty) {{ currency(getDelivery($products[0]->sku, $qty)) }} @else {{ currency(getDelivery($products)) }} @endisset</div>
                </div>

                <div class="sub_total">
                    <div class="txt">Total weight :</div>
                    <div class="amount">@isset($qty) {{ $products[0]->weight*$qty }} kg @else {{ getTotalWeight($products) }} kg @endisset</div>
                </div>
            </div>

            <div class="total">
                <div class="txt">ORDER TOTAL</div>
                <div class="amount" id="order_total">@isset($qty) {{ currency(($products[0]->sales_price * $qty)+getDelivery($products[0]->sku, $qty)) }} @else {{ currency(get_cart_total(false)+getDelivery($products)) }} @endisset</div>
            </div>
            
            

            @isset($qty)
                <div id="checkout_btn">
                    <div class="proceed">
                        <checkout-btn :sku="'{{ $products[0]->sku }}'" :qty="'{{ $qty }}'" ><checkout-btn />
                    </div>
                </div>
            @else
                <div id="cartcheckout_btn">
                    <div class="proceed">
                        <cartcheckout-btn  />
                    </div>
                </div>
            @endisset

        </div>
    </div>
</div>

<script>
@isset ($qty)

$("#country").change(function () {
    $.ajax({
        type: "post",
        url: "/get-total",
        data: {get_total: "product", country: this.value, sku: '{{ $products[0]->sku }}', qty: '{{ $qty }}', _token: '{{ csrf_token() }}'},
        dataType: "json",
        success: function (response) {
            if (response.error == 0) {
                $("#order_sub_total").html(response.subtotal);
                $("#order_delivery").html(response.del);
                $("#order_total").html(response.total);
            }
            else {
                toastr.error(response.msg, "Error");
            }
        }
    });
});

@else

$("#country").change(function () {
    $.ajax({
        type: "post",
        url: "/get-total",
        data: {get_total: "cart", country: this.value, _token: '{{ csrf_token() }}'},
        dataType: "json",
        success: function (response) {
            if (response.error == 0) {
                $("#order_sub_total").html(response.subtotal);
                $("#order_delivery").html(response.del);
                $("#order_total").html(response.total);
            }
            else {
                toastr.error(response.msg, "Error");
            }
        }
    });
});

@endisset
</script>
    
@endsection