@extends('dashboard.views.layouts.app')

@section('dashboard')

<div class="top_nav">
    <div class="bread_crumb">Dashboard > <span>Sub categories</span></div>
    <div class="create"><button class="secondary_btn" type="button" data-bs-toggle="modal" data-bs-target="#CreateModel"><i class="fa-solid fa-plus"></i> Add new</button></div>
</div>

<div class="categories">
    <div class="inner">
        <table>
            <thead>
                <tr>
                    <td>Sub category name</td>
                    <td>Parent category</td>
                    <td>Action</td>
                </tr>
            </thead>

            <tbody>
              @foreach ($category as $cat)
                @foreach ($cat->subcategories as $item)
                <tr>
                  <td>{{ $item->sub_category_name }}</td>
                  <td>{{ $cat->category_name }}</td>
                  <td><button onclick="updateCategory('{{ $item->id }}', '{{ $item->sub_category_name }}', '{{ $cat->id }}')" type="button"><i class="fa-solid fa-pen"></i></button> <button onclick="deleteCategory('{{ $item->id }}')"><i class="fa-solid fa-trash"></i></button></td>
                </tr>
                @endforeach
              @endforeach
            </tbody>
        </table>
    </div>
</div>



<!-- Update Modal -->
<div class="modal fade" id="UpdateModel" tabindex="-1" aria-labelledby="UpdateModelLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <form action="" method="post" id="CategoryUpdateForm" onsubmit="return false;">
            @csrf
            <div class="head">Update category</div>
            <div class="txt_field">
              <div class="label">Sub category name</div>
              <div class="input">
                  <input type="hidden" name="updateid" id="updateid" value="">
                  <input type="text" name="updatename" id="updatename" value="">
              </div>
            </div>
            <div class="txt_field">
              <div class="label">Select category</div>
              <div class="input">
                  <select name="updatecategory" id="updatecategory" required>
                    <option value=""></option>
                    @foreach (getCategories() as $categ)
                        <option value="{{ $categ->id }}">{{ $categ->category_name }}</option>
                    @endforeach
                  </select>
              </div>
            </div>
            <div class="update_btn">
              <button type="submit" class="secondary_btn">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>


<!-- Create Modal -->
<div class="modal fade" id="CreateModel" tabindex="-1" aria-labelledby="CreateModelLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <form action="" method="post" id="CreatesubCategory" onsubmit="return false;">
            @csrf
            <div class="head">Add new category</div>
            <div class="txt_field">
              <div class="label">Category name</div>
              <div class="input">
                  <input type="text" name="createname" id="createname">
              </div>
            </div>
            <div class="txt_field">
              <div class="label">Select category</div>
              <div class="input">
                  <select name="createcategory" id="createcategory" required>
                    <option value="">-- Select category --</option>
                    @foreach (getCategories() as $categ)
                        <option value="{{ $categ->id }}">{{ $categ->category_name }}</option>
                    @endforeach
                  </select>
              </div>
            </div>
            <div class="update_btn">
              <button type="submit" class="secondary_btn">Create</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>


<script>
  function updateCategory(id, name, category) {
    if (id != "" && id != " " && name != "" && name != " " && category != "" && category != " ") {
      $("#updateid").val(id);
      $("#updatename").val(name);
      $("#updatecategory").val(category);
      $("#UpdateModel").modal('show');
    }
    else {
      toastr.warning("Something went wrong");
    }
  }

  $("#CategoryUpdateForm").submit(function (e) {
    e.preventDefault();
    if ($("#updateid").val() != "" && $("#updateid").val() != " " && $("#updatename").val() != "" && $("#updatename").val() != " " && $("#updatecategory").val() !="" && $("#updatecategory").val() != " ") {
      $("#UpdateModel").modal('hide');
      $.ajax({
        type: "post",
        url: "/update-subcategory",
        data: $("#CategoryUpdateForm").serialize(),
        dataType: "json",
        success: function (response) {
          if (response.error == 0) {
            toastr.success(response.msg, "Success");
            setTimeout(() => {
              location.reload();
            }, 2000);
          }
          else{
            toastr.error(response.msg, "Error");
          }
        }
      });
    }
    else {
      toastr.warning("Please fill all fields");
    }
  });

  $("#CreatesubCategory").submit(function (e) {
    e.preventDefault();
    if ($("#createname").val() != "" && $("#createname").val() != " " && $("#createcategory").val() !="" && $("#createcategory").val() != " ") {
      $.ajax({
        type: "post",
        url: "/create-subcategory",
        data: $("#CreatesubCategory").serialize(),
        dataType: "json",
        success: function (create) {
          if (create.error == 0) {
            $("#CreateModel").modal('hide');
            toastr.success(create.msg, "Success");
            setTimeout(() => {
              location.reload();
            }, 2000);
          }
          else{
            toastr.error(create.msg, "Error");
          }
        }
      });
    }
    else {
      toastr.warning("Please fill all fields");
    }
  });


  function deleteCategory(id) {
    if (confirm("Are you sure you want to delete?") == true) {
      $.ajax({
      type: "post",
      url: "/delete-subcategory",
      data: {action: 'delete', id: id, _token: $("meta[name='csrf-token']").attr('content')},
      dataType: "json",
      success: function (deleted) {
        if (deleted.error == 0) {
          toastr.success(deleted.msg, "Success");
          setInterval(() => {
            location.reload();
          }, 2000);
        }
        else{
          toastr.error(deleted.msg, "Error");
        }
      }
    });
    }
  }
</script>

@endsection