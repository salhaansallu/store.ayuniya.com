@extends('layouts.app')

@section('content')

@foreach ($products as $product)

<div class="bread_crumb"><a>{{ $category[0] }} > <span> {{ $subcategory[0] }}</span></a> </div>    
<div class="landing_main">
    <div class="landing_content">
        <div class="images">
            <div class="main_image">
                <img loading="lazy" id="main_image" src="{{ validate_image($product->banner) }}" alt="">
            </div>
            <hr>
            <div class="sub_images">
                <img loading="lazy" onclick="maxImage($(this).attr('src'))" src="{{ validate_image($product->banner) }}" alt="">
                @foreach ($product->varient as $varient)
                    <img loading="lazy" onclick="maxImage($(this).attr('src'))" src="{{ validate_image($varient->image_path) }}" alt="">
                @endforeach
            </div>
        </div>

        <div class="details">
            <div class="name">
                <h1>{{ $product->product_name }} </h1>
                <img onclick="navigator.clipboard.writeText('{{ env('APP_URL') }} {{ productURL($product->id, $product->product_name) }}'.replace(' ', ''));toastr.success('Copied to clipboard')" src="{{ asset('assets/images/icon/share.png') }}" alt="">
            </div>
            <div class="category">Category : <a href="{{ categoryURL($subcategory[1], $subcategory[0]) }}">{{ $subcategory[0] }}</a></div>
            <div class="short_des">
                {{ $product->short_des }}
            </div>
            
            <div class="price">
                <h3 id="sales_price">{{ min_price($product->varient)[0] }}</h3>
                {{-- <div class="offer"><del id="price">{{ min_price($product->varient)[1] }}</del></div> --}}
            </div>
           
            <div class="varient" id="varient_active">
                <div class="v_name">Varient : <span id="v_name"></span> <input type="hidden" id="_varientSku" name="_varientSku" value=""> </div>
                <div class="v_images">
                @foreach ($product->varient as $varient)
                    <img loading="lazy" onclick="getVarient('{{ $varient->sku }}')" src="{{ validate_image($varient->image_path) }}" alt="">
                @endforeach
                </div>
            </div>

            <div class="qty">
                Quantity : <input type="number" id="quantity" value="1" min="1" max="{{ $varient->qty }}">
            </div>

            <div class="action_btn" id="product_action_btns">
                <product-btn />
            </div>
        </div>

        <div class="description">
            <div class="row">
                <div class="col-12"><h6 class="head">Vendor details</h6></div>
                <div class="col-12" style="margin-left: 10px;"><b>Store : </b> {{ getVendor($product->vendor)[0]['store_name'] }}</div>
                <div class="col-12" style="margin-left: 10px;"><b>Registration number : </b> {{ getVendor($product->vendor)[0]['registration'] }}</div>
            </div>
            <div class="head"><b>Description of <span>{{ $product->product_name }}</span></b></div>
            <div class="content">
                {{ $product->long_des }}
            </div>
        </div>
    </div>

    <div class="md_description">
        <div class="head"><b>Description of <span>{{ $product->product_name }}</span></b></div>
        <div class="content">
            {{ $product->long_des }}
        </div>
    </div>
    <div class="recommended">
        <div class="head">Recommended for you</div>
        <div class="row row-cols-auto">
            @foreach ($includes as $item)
            <div class="col">
                <div class="img">
                    <a href="{{ productURL($item->id, $item->product_name) }}"><img src="{{ validate_image($item->banner) }}" alt=""></a>
                </div>
                <div class="detail">
                    <div class="name"><a href="{{ productURL($item->id, $item->product_name) }}">{{ $item->product_name }}</a></div>
                    <div class="sales_price">{{ min_price($item->varient)[0] }}</div>
                    {{-- <div class="price"><del>{{ min_price($item->varient)[1] }}</del></div> --}}
                    <div class="cart_btn">
                        <a href="{{ productURL($item->id, $item->product_name) }}"><button>View</button></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach

<script>
// ===============  Landing page image maximize script ============ //


function currency(price) {
    if (price+"".includes(".")) {
        return 'LKR '+price.toFixed(2);
    }
    else if(price == "" || price == 0 || price == "0") {
        return "";
    }
    else{
        return 'LKR '+price+'.00';
    }
}

function maxImage(path) {
    $("#main_image").attr('src', path);
}

function getVarient(sku) {
    if (sku == "") {
        toastr.error("Sorry, Something went wrong!", 'Error');
        location.reload();
    }
    else {
        $.ajax({
                type: "post",
                url: "/product/"+sku,
                data: {action: 'get_varient', _token: $('meta[name="csrf-token"]').attr('content')},
                dataType: "json",
                success: function (response) {
                    $("#price").text(currency(response[0].price));
                    $("#sales_price").text(currency(response[0].sales_price));
                    $("#v_name").text(response[0].v_name);
                    $("#_varientSku").val(response[0].sku);
                    $("#quantity").attr('max', response[0].qty);
                    document.cookie = "cartsku="+response[0].sku+"; path=";
                    toastr.success(response[0].v_name+" selected", "Success");
                    document.getElementById("varient_active").classList.add("varient_selected")
                    //console.log(response.sales_price);
                }
            });
    }
}
</script>

@endsection