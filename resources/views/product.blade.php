@extends('layouts.app')

@section('content')
    <style>
        .button_pre {
            background-color: var(--primary--);
            /* Green */
            color: white;
            width: 200px;
            padding-top: 5px;
            padding-bottom: 5px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            font-size: 18px;
            font-family: Arial, Helvetica, sans-serif;
            border: none;
            outline: none;
            cursor: pointer;
            margin-top: 5px;
        }
    </style>
    @foreach ($products as $product)
        {{-- <div class="bread_crumb site-container"><a>{{ $category[0] }} > <span> {{ $subcategory[0] }}</span></a> </div> --}}
        <div class="landing_main site-container">
            <div class="landing_content">
                <div class="images">
                    <div class="main_image">
                        <img loading="lazy" id="main_image" src="{{ validate_image($product->banner) }}" alt="">
                    </div>

                    <div class="sub_images">
                        <img loading="lazy" onclick="maxImage($(this).attr('src'))"
                            src="{{ validate_image($product->banner) }}" alt="">
                        @foreach ($product->varient as $varient)
                            <img loading="lazy" onclick="maxImage($(this).attr('src'))"
                                src="{{ validate_image($varient->image_path) }}" alt="">
                        @endforeach
                    </div>
                </div>

                <div class="details">
                    <a style="text-decoration: none;" href="{{ categoryURL($subcategory[1], $subcategory[0]) }}">
                        <div class="top_title">{{ $subcategory[0] }}</div>
                    </a>
                    <div class="name">
                        <h1>{{ $product->product_name }} </h1>
                        <img onclick="navigator.clipboard.writeText('{{ env('APP_URL') }} {{ productURL($product->id, $product->product_name) }}'.replace(' ', ''));toastr.success('Copied to clipboard')"
                            src="{{ asset('assets/images/icon/share.png') }}" alt="">
                    </div>

                    <div class="short_des">
                        {{ $product->short_des }}
                    </div>

                    <div class="price">
                        <h3 id="sales_price">{{ min_price($product->varient)[0] }}</h3>
                        {{-- <div class="offer"><del id="price">{{ min_price($product->varient)[1] }}</del></div> --}}
                    </div>

                    <div class="varient" id="varient_active">
                        <div class="v_name">Varient : <span id="v_name"></span> <input type="hidden" id="_varientSku"
                                name="_varientSku" value=""> </div>
                        <div class="v_images">
                            @foreach ($product->varient as $varient)
                                <img loading="lazy" onclick="getVarient('{{ $varient->sku }}')"
                                    src="{{ validate_image($varient->image_path) }}" alt="">
                            @endforeach
                        </div>
                    </div>

                    <div class="qty">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-secondary btn-number" data-type="minus"
                                    data-field="quant[1]">
                                    -
                                </button>
                            </span>
                            <input type="text" id="quantity" max="{{ $varient->qty }}" name="quant[1]"
                                class="form-control input-number" value="1" min="1" style="width: 50px;">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-secondary btn-number" data-type="plus"
                                    data-field="quant[1]">
                                    +
                                </button>
                            </span>
                        </div>
                    </div>

                    @if ($varient->qty < 1)
                        <b class="text-danger mt-3 d-block">Sorry, this product is out of stock</b>
                    @else
                        <div class="action_btn" id="product_action_btns">
                            <product-btn />
                        </div>
                    @endif
                </div>

                <div class="description">
                    <div class="head"><b>Description of</b></div>
                    <div class="content">
                        {{ $product->long_des }}
                    </div>
                </div>
            </div>

            <div class="md_description">
                <div class="head"><b>Description </b></div>
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
                                <a href="{{ productURL($item->id, $item->product_name) }}"><img
                                        src="{{ validate_image($item->banner) }}" alt=""></a>
                            </div>
                            <div class="detail">
                                <div class="name"><a
                                        href="{{ productURL($item->id, $item->product_name) }}">{{ $item->product_name }}</a>
                                </div>
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
            if (price + "".includes(".")) {
                return 'LKR ' + price.toFixed(2);
            } else if (price == "" || price == 0 || price == "0") {
                return "";
            } else {
                return 'LKR ' + price + '.00';
            }
        }

        function maxImage(path) {
            $("#main_image").attr('src', path);
        }

        function getVarient(sku) {
            if (sku == "") {
                toastr.error("Sorry, Something went wrong!", 'Error');
                location.reload();
            } else {
                $.ajax({
                    type: "post",
                    url: "/product/" + sku,
                    data: {
                        action: 'get_varient',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(response) {
                        $("#price").text(currency(response[0].price));
                        $("#sales_price").text(currency(response[0].sales_price));
                        $("#v_name").text(response[0].v_name);
                        $("#_varientSku").val(response[0].sku);
                        $("#quantity").attr('max', response[0].qty);
                        document.cookie = "cartsku=" + response[0].sku + "; path=";
                        toastr.success(response[0].v_name + " selected", "Success");
                        document.getElementById("varient_active").classList.add("varient_selected")
                        //console.log(response.sales_price);
                    }
                });
            }
        }

        getVarient('{{ $products[0]->varient[0]->sku }}');
    </script>


    <script>
        $(document).ready(function() {
            $('.btn-number').click(function(e) {
                e.preventDefault();

                var fieldName = $(this).attr('data-field');
                var type = $(this).attr('data-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseInt(input.val());

                if (!isNaN(currentVal)) {
                    if (type == 'minus') {
                        if (currentVal > input.attr('min')) {
                            input.val(currentVal - 1).change();
                        }
                    } else if (type == 'plus') {
                        if (currentVal < input.attr('max')) {
                            input.val(currentVal + 1).change();
                        }
                    }
                } else {
                    input.val(1);
                }
            });

            $('.input-number').focusin(function() {
                $(this).data('oldValue', $(this).val());
            });

            $('.input-number').change(function() {
                var minValue = parseInt($(this).attr('min'));
                var maxValue = parseInt($(this).attr('max'));
                var valueCurrent = parseInt($(this).val());

                if (valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus'][data-field='" + $(this).attr('name') + "']")
                        .removeAttr('disabled');
                } else {
                    alert('Sorry, the minimum value was reached');
                    $(this).val($(this).data('oldValue'));
                }

                if (valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + $(this).attr('name') + "']")
                        .removeAttr('disabled');
                } else {
                    alert('Sorry, the maximum value was reached');
                    $(this).val($(this).data('oldValue'));
                }
            });

            $(".input-number").keydown(function(e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    $(this).blur();
                }
            });
        });
    </script>

    </script>
    <script>
        function placePreOrder(productId, productName, quantity) {
            $.ajax({
                type: "POST",
                url: "/store-preorder",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                    product_name: productName,
                    quantity: quantity,
                },
                success: function(response) {
                    // Handle the success response, e.g., show a success message
                    toastr.success(response.message);
                },
                error: function(xhr, status, error) {
                    // Handle errors if necessary
                    console.error(xhr.responseText);
                    toastr.error('An error occurred while placing the preorder.');
                }
            });
        }
    </script>
@endsection
