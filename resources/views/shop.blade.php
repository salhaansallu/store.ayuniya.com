@extends('layouts.app')

@section('content')

<div class="container">
    <div class="shop_inner_nav">
        <div class="breadcrumb">
            <a href="">
            @isset($request)
            @if ($request == "shop")
                Home > <span>Shop</span>
            @endif
            @endisset

            @isset($request)
            @if ($request == "categories")
                Home > {{ $cat }} > <span>{{ $subcat }}</span>
            @endif
            @endisset

            @isset($request)
            @if ($request == "search")
                Home > Search > <span>{{ $keyword }}</span>
            @endif
            @endisset
            </a>
        </div>
        <div class="sort">
            <div class="results">
                {{-- <select name="" id="productSort">
                    <option value="default">Default storing</option>
                    <option value="lowtohigh">Price low-high</option>
                    <option value="hightolow">Price high-low</option>
                </select> --}}
            </div>
        </div>
    </div>
    <div class="md_filter">
        <div class="icon" data-bs-toggle="modal" data-bs-target="#filterModel" id="open_filter"><i class="fa-solid fa-filter"></i></div>
    </div>
</div>

<div class="container">
    <div class="main_content">
        <div class="filters">
            <div class="cat_filter">
                <div class="filter_head">Product categories</div>
                <div class="inner_cat">
                    <ul>
                        @isset($categories)
                            @foreach ($categories as $category)
                            <li><a data-bs-toggle="collapse" href="#{{ str_replace(' ', '-', $category->category_name) }}" role="button" aria-expanded="false" aria-controls="{{ str_replace(' ', '-', $category->category_name) }}">{{ $category->category_name }}</a>
                                <div class="collapse" id="{{ str_replace(' ', '-', $category->category_name) }}">
                                    <div class="card card-body">
                                        <ul>
                                            @foreach ($category->subcategories as $sub)
                                                <li><a href="{{ categoryURL($sub->id, $sub->sub_category_name) }}"> {{ $sub->sub_category_name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        @endisset
                    </ul>
                </div>
            </div>
            <div class="price_filter">
                <div class="filter_head">Filter by price</div>
                <div class="filter_price">
                    <input type="range" name="" id="price_range" min="10" max="10000" value="@php if(isset($_GET['priceFilter'])){ echo $_GET['priceFilter']; }else{echo 0;} @endphp">
                </div>
                <div class="filter_btn">
                    <button id="filter_btn">Filter</button> Price : Rs 10 - Rs <span id="max_price">@php if(isset($_GET['priceFilter'])){ echo $_GET['priceFilter']; }else{echo '10,000';} @endphp</span>
                </div>
            </div>
        </div>
    
        <div class="products">
            <div class="banner">
                <img src="{{ asset('assets/images/banner/product-page-banner.png') }}" alt="">
            </div>
    
            <div class="row row-cols-auto" id="products-data">

                @isset($filterproducts)
                @if ($filterproducts != "No product found")
                @foreach ($filterproducts as $product)
                @if ($product->sortedvarients != '[]' && $product->sortedvarients != ' ' && $product->sortedvarients != '')
                <div class="col">
                    <div class="img">
                        <a href="{{ productURL($product->id, $product->product_name) }}"><img src="{{ validate_image($product->banner) }}" alt=""></a>
                    </div>
                    <div class="detail">
                        <div class="name"><a href="{{ productURL($product->id, $product->product_name) }}">{{  $product->product_name }}</a></div>
                        <div class="sales_price">{{ min_price($product->sortedvarients)[0] }}</div>
                        {{-- <div class="price"><del>{{ min_price($product->sortedvarients)[1] }}</del></div> --}}
                        <div class="cart_btn">
                            <a href="{{ productURL($product->id, $product->product_name) }}"><button>View</button></a>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
                @else
                <div class="text-center m-auto mt-3 d-none d-xxl-block d-xl-block d-lg-block d-md-block d-sm-block" style="font-size: 17px;">
                    <div class="icon"><i class="fa-regular fa-circle-xmark fs-1 text-muted"></i></div>
                    <div class="resultmsg">{{ $filterproducts }}</div>
                </div>
                @endif
                @endisset

                @isset($products)
                    @if ($products != "No product found")
                    @foreach ($products as $product)
                    @if ($product->varient != '[]' && $product->varient != ' ' && $product->varient != '')
                    <div class="col">
                        <div class="img">
                            <a href="{{ productURL($product->id, $product->product_name) }}"><img src="{{ validate_image($product->banner) }}" alt=""></a>
                        </div>
                        <div class="detail">
                            <div class="name"><a href="{{ productURL($product->id, $product->product_name) }}">{{  $product->product_name }}</a></div>
                            <div class="sales_price">{{ min_price($product->varient)[0] }}</div>
                            {{-- <div class="price"><del>{{ min_price($product->varient)[1] }}</del></div> --}}
                            <div class="cart_btn">
                                <a href="{{ productURL($product->id, $product->product_name) }}"><button>View</button></a>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @else
                    <div class="text-center m-auto mt-3 d-none d-xxl-block d-xl-block d-lg-block d-md-block d-sm-block" style="font-size: 17px;">
                        <div class="icon"><i class="fa-regular fa-circle-xmark fs-1 text-muted"></i></div>
                        <div class="resultmsg">{{ $products }}</div>
                    </div>
                    @endif
                @endisset

                {{-- <product-components><product-components/> --}}
            </div>
            <div class="d-flex justify-content-center mt-5">
                @isset ($products)
                    @if ($products != "No product found" && $products->links()->paginator->hasPages())
                        {{ $products->appends(request()->query())->links() }}
                    @endif
                @endisset

                @isset ($filterproducts)
                    @if ($filterproducts != "No product found" && $filterproducts->links()->paginator->hasPages())
                        {{ $filterproducts->appends(request()->query())->links() }}
                    @endif
                @endisset
            </div>
        </div>
        @isset($products)
        @if ($products == "No product found")
        <div class="text-center m-auto mt-3 d-xxl-none d-xl-none d-lg-none d-md-none d-sm-none" style="font-size: 17px;">
            <div class="icon"><i class="fa-regular fa-circle-xmark fs-1 text-muted"></i></div>
            <div class="resultmsg">{{ $products }}</div>
        </div>
        @endif
        @endisset

        @isset($filterproducts)
        @if ($filterproducts == "No product found")
        <div class="text-center m-auto mt-3 d-xxl-none d-xl-none d-lg-none d-md-none d-sm-none" style="font-size: 17px;">
            <div class="icon"><i class="fa-regular fa-circle-xmark fs-1 text-muted"></i></div>
            <div class="resultmsg">{{ $filterproducts }}</div>
        </div>
        @endif
        @endisset
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="filterModel" tabindex="-1" aria-labelledby="filterModelLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="filter">
                <div class="cat_filter">
                    <div class="filter_head">Product categories</div>
                    <div class="inner_cat">
                        <ul>
                            @isset($categories)
                            @foreach ($categories as $category)
                            <li><a data-bs-toggle="collapse" href="#{{ str_replace(' ', '-', $category->category_name) }}" role="button" aria-expanded="false" aria-controls="{{ str_replace(' ', '-', $category->category_name) }}">{{ $category->category_name }}</a>
                                <div class="collapse" id="{{ str_replace(' ', '-', $category->category_name) }}">
                                    <div class="card card-body">
                                        <ul>
                                            @foreach ($category->subcategories as $sub)
                                                <li><a href="{{ categoryURL($sub->id, $sub->sub_category_name) }}"> {{ $sub->sub_category_name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        @endisset
                        </ul>
                    </div>
                </div>
                <div class="price_filter">
                    <div class="filter_head">Filter by price</div>
                    <div class="filter_price">
                        <input type="range" name="" id="md_price_range" min="10" max="10000" value="@php if(isset($_GET['priceFilter'])){ echo $_GET['priceFilter']; }else{echo 0;} @endphp">
                    </div>
                    <div class="filter_btn">
                        <button id="md_filter_btn">Filter</button> Price : Rs 10.00 - Rs <span id="md_max_price">@php if(isset($_GET['priceFilter'])){ echo $_GET['priceFilter']; }else{echo '10,000';} @endphp</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@endsection