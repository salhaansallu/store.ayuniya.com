@extends('site-vendors.layout.app')

@section('vendor')

<style>
  form .input {
    margin-top: 0;
  }
</style>

@isset($editproducts)
<script>
  $(document).ready(function () {
    $("#UpdateModelbtn").click();
  });
</script>
@endisset

<div class="top_nav">
    <div class="bread_crumb">Dashboard > <span>Products</span></div>
    <div class="create"><button class="secondary_btn" type="button" data-bs-toggle="modal" data-bs-target="#CreateModel"><i class="fa-solid fa-plus"></i> Add new</button> @isset($editproducts) <button class="secondary_btn" type="button" data-bs-toggle="modal" data-bs-target="#UpdateModel" id="UpdateModelbtn">Open edit</button> @endisset</div>
</div>

<div class="products">
    <div class="inner">
        <table>
            <thead>
                <tr>
                    <td>Product</td>
                    <td>Category</td>
                    <td>Price</td>
                    <td>Varients</td>
                    <td>Action</td>
                </tr>
            </thead>

            <tbody>
              @foreach ($products as $product)
              <tr>
                <td><img src="{{ validate_image($product->banner) }}" alt=""> <p>{{ $product->product_name }}</p></td>
                <td>{{ getProductCategory($product->category)[0]['sub_category_name'] }}</td>
                <td>{{ min_price($product->varient)[0] }}</td>
                <td>{{ count($product->varient) }}</td>
                <td><a href="?edit={{ $product->id }}"><button><i class="fa-solid fa-pen"></i></button></a> <button onclick="deleteProduct('{{ $product->id }}')"><i class="fa-solid fa-trash"></i></button></td>
            </tr>
              @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
      @isset ($products)
      @if ($products->links()->paginator->hasPages())
          {{ $products->appends(request()->query())->links() }}
      @endif
      @endisset
    </div>
</div>



<!-- Update Modal -->
<div class="modal fade" id="UpdateModel" tabindex="-1" aria-labelledby="UpdateModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-body">
          <form action="" method="post" onsubmit="return false;" id="UpdateForm" enctype="multipart/form-data">
            @csrf
            <div class="head">Update Product</div>
            <label for="banner" class="pictureupload">
              <i class="fa-solid fa-cloud-arrow-up"></i>
              <p>Click and upload image (only if the image needs to be updated)</p>
              <input type="file" name="banner" style="display: none" id="banner" accept="image/*">
            </label>

            @isset($editproducts)
            <input type="hidden" name="var_count" id="var_count" value="{{ count($editproducts[0]->varient) }}">
            @foreach ($editproducts as $pro)
            <input type="hidden" name="pro_id" id="pro_id" value="{{ $pro->id }}">
            <div class="txt_field">
              <div class="label">Product name</div>
              <div class="input">
                  <input type="text" name="name" id="name" value="{{ $pro->product_name }}" required>
              </div>
            </div>

            <div class="txt_field">
              <div class="label">Select category</div>
              <div class="input">
                  <select name="category" id="category" required>
                    <option value="{{ getProductCategory($pro->category)[0]['id'] }}">{{ getProductCategory($pro->category)[0]['sub_category_name'] }}</option>
                      @foreach (getCategories() as $cat)
                          @foreach ($cat->subcategories as $sub)
                              <option value="{{ $sub->id }}">{{ $sub->sub_category_name }}</option>
                          @endforeach
                      @endforeach
                  </select>
              </div>
            </div>

            <div class="txt_field">
              <div class="label">Short description</div>
              <div class="input">
                  <textarea name="shortdes" id="shortdes" cols="30" rows="4" required>{{ $pro->short_des }}</textarea>
              </div>
            </div>

            <div class="txt_field">
              <div class="label">Long description</div>
              <div class="input">
                  <textarea name="longdes" id="longdes" cols="30" rows="10" required>{{ $pro->long_des }}</textarea>
              </div>
            </div>

            <div class="varients">
              <div class="varient">
                <table>
                  <tr>
                    <th>Image</th>
                    <th>Variant name</th>
                    <th>Unit</th>
                    <th>QTY</th>
                    <th>Price</th>
                    <th>Sales price</th>
                    <th>Weight</th>
                    <th>Active</th>
                    <th>Delete</th>
                  </tr>

                  @foreach ($pro->varient as $key => $var)
                  <tr>
                    <td><input type="file" accept="image/*" name="var_image{{ $key+1 }}" id=""></td>
                    <td><input type="text" name="v_name{{ $key+1 }}" id="" style="width: 160px;" value="{{ $var->v_name }}" required></td>
                    <td>
                        <select name="unit{{ $key+1 }}" id="" style="width: 80px;" required>
                            <option value="{{ strtolower($var->unit) }}">{{ ucfirst($var->unit) }}</option>
                            <option value="pcs">Pcs</option>
                            <option value="kg">Kg</option>
                            <option value="l">L</option>
                        </select>
                    </td>
                    <td><input type="hidden" name="id{{ $key+1 }}" value="{{ $var->id }}" id="id" required><input type="number" name="qty{{ $key+1 }}" value="{{ $var->qty }}" id="qty" style="width: 60px;" required></td>
                    <td><input type="text" name="price{{ $key+1 }}" value="{{ $var->price }}" id="price" required></td>
                    <td><input type="text" name="sales_price{{ $key+1 }}" value="{{ $var->sales_price }}" id="sales_price" required></td>
                    <td><input type="text" name="weight{{ $key+1 }}" value="{{ $var->weight }}" id="weight" required></td>
                    <td>
                        <select name="active{{ $key+1 }}" id="" style="width: 80px;">
                            <option value="active">Yes</option>
                            <option value="">No</option>
                        </select>
                    </td>
                    <td><div class="del" onclick="deleteVarient('{{ $var->id }}')"><i class="fa-solid fa-trash"></i></div></td>
                </tr>
                  @endforeach
                </table>
              </div>
            </div>

            @endforeach
            @endisset

            <div class="create_btn mt-3">
              <button type="submit" class="secondary_btn" id="productUpdateBtn">Update product</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>


<!-- Create Modal -->
<div class="modal fade" id="CreateModel" tabindex="-1" aria-labelledby="CreateModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-body">
          <form action="/create-product" method="post" enctype="multipart/form-data" onsubmit="return false;" id="create_product">
            @csrf
            <div class="head">Add Product</div>
          <label for="bannerimage" class="pictureupload">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <p>Click to upload image</p>
            <input type="file" name="bannerimage" style="display: none" id="bannerimage" accept="image/*">
          </label>

          <div class="txt_field">
            <div class="label">Product name</div>
            <div class="input">
                <input type="text" name="name" id="name" required>
            </div>
          </div>

          <div class="txt_field">
            <div class="label">Select category</div>
            <div class="input">
                <select name="category" id="category" required>
                    @foreach (getCategories() as $cat)
                        @foreach ($cat->subcategories as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->sub_category_name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>
          </div>

          <div class="txt_field">
            <div class="label">Short description</div>
            <div class="input">
                <textarea required name="shortdes" id="shortdes" cols="30" rows="4"></textarea>
            </div>
          </div>

          <div class="txt_field">
            <div class="label">Long description</div>
            <div class="input">
                <textarea required name="longdes" id="longdes" cols="30" rows="10"></textarea>
            </div>
          </div>

          <div id="varient" class="varients">
            <pro-varient />
          </div>
          
          <div class="create_btn">
            <button type="submit" class="secondary_btn">Create product</button>
          </div>
          </form>
        </div>
      </div>
    </div>
</div>

<script>
  $("#create_product").submit(function (e) { 
    e.preventDefault();
    var postData = new FormData($("#create_product")[0]);
    $("#productUpdateBtn").attr("disabled", "");
    $.ajax({
      type: "post",
      url: "/create-product",
      data: postData,
      dataType: "json",
      processData: false,
      contentType: false,
      success: function (response) {
      $("#productUpdateBtn").removeAttr("disabled");
        if (response.error==0) {
          toastr.success(response.msg, "Success");
          setInterval(() => {
            location.reload();
          }, 2000);
        }
        else{
          toastr.error(response.msg, "Error");
        }
      }
    });
  });

  $("#UpdateForm").submit(function (e) { 
    e.preventDefault();
    var Data = new FormData($("#UpdateForm")[0]);
    $("#productUpdateBtn").attr("disabled", "");
    $.ajax({
      type: "post",
      url: "/update-product",
      data: Data,
      dataType: "json",
      processData: false,
      contentType: false,
      success: function (update) {
      $("#productUpdateBtn").removeAttr("disabled");
        console.log(update);
        if (update.error==0) {
          toastr.success(update.msg, "Success");
          setInterval(() => {
            location.href="/web-admin/products";
          }, 2000);
        }
        else{
          toastr.error(update.msg, "Error");
        }
      }
    });
  });

  function deleteProduct(id) {
    if (id != "") {
      $.ajax({
        type: "post",
        url: "/delete-product",
        data: {action: 'delete', id: id, _token: $("meta[name='csrf-token']").attr('content')},
        dataType: "json",
        success: function (deletepro) {
        if (deletepro.error==0) {
          toastr.success(deletepro.msg, "Success");
          setInterval(() => {
            location.href="/web-admin/products";
          }, 2000);
        }
        else{
          toastr.error(deletepro.msg, "Error");
        }
        }
      });
    }
  }

  function deleteVarient(id) {
    if (id != "") {
      $.ajax({
        type: "post",
        url: "/delete-varient",
        data: {action: 'delete', id: id, _token: $("meta[name='csrf-token']").attr('content')},
        dataType: "json",
        success: function (deleteVarient) {
          if (deleteVarient.error==0) {
            toastr.success(deleteVarient.msg, "Success");
            setInterval(() => {
              location.reload();
            }, 2000);
          }
          else{
            toastr.error(deleteVarient.msg, "Error");
          }
        }
      });
    }
  }
</script>

@endsection
