@extends('dashboard.views.layouts.app')

@section('dashboard')
<div class="top_nav">
    <div class="bread_crumb">Dashboard > <span>Blogs</span></div>
    <div class="create"><button class="secondary_btn" type="button" data-bs-toggle="modal" data-bs-target="#CreateModel"><i class="fa-solid fa-plus"></i> Add new</button> @isset($editBlogs) <button class="secondary_btn" type="button" data-bs-toggle="modal" data-bs-target="#UpdateModel" id="UpdateModelbtn">Open edit</button> @endisset</div>
</div>

<div class="blogs">
    <div class="inner">
        <table>
            <thead>
                <tr>
                    <td>Blog image</td>
                    <td>Blog Title</td>
                    <td>Blog Discription</td>
                    <td>Action</td>
                </tr>
            </thead>

            <tbody>
              @foreach ($blogs as $blog)
              <tr>
                <td><img src="{{ validate_image($blog->blog_image) }}"> </td>
                <td>{{ $blog->blog_Title }}</td>
                <td>{{ $blog->blog_dis }}</td>
               <td><button type="button" onclick="updateBlogs('{{ $blog->id }}', '{{ $blog->blog_Title}}')"><i class="fa-solid fa-pen"></i></button> <button onclick="deleteBlogs('{{ $blog->id }}')"><i class="fa-solid fa-trash"></i></button></td>

            </tr>
              @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Create Modal -->
<div class="modal fade" id="CreateModel" tabindex="-1" aria-labelledby="CreateModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-body">
          <form action="/create-blogs" method="post" enctype="multipart/form-data" onsubmit="return false;" id="create_blog">
            @csrf
            <div class="head">Add Blog post</div>
          <label for="blog_image" class="pictureupload">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <p>Click to upload image</p>
            <input type="file" name="blog_image" style="display: none" id="blog_image" accept="image/*">
          </label>

          <div class="txt_field">
            <div class="label">Blog Title </div>
            <div class="input">
                <input type="text" name="blog_Title" id="blog_Title" required>
            </div>
          </div>


          <div class="txt_field">
            <div class="label">blog description</div>
            <div class="input">
                <textarea required name="blog_dis" id="blog_dis" cols="30" rows="10"></textarea>
            </div>
          </div>


          <div class="create_btn">
            <button type="submit" class="secondary_btn">Create Blogs</button>
          </div>
          </form>
        </div>
      </div>
    </div>
</div>
<!-- Update Modal -->
<div class="modal fade" id="UpdateModel" tabindex="-1" aria-labelledby="UpdateModelLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
            <form action="/create-blogs" method="post" enctype="multipart/form-data" onsubmit="return false;" id="create_blog">
                @csrf
                <div class="head">Add Blog post</div>
              <label for="blog_image" class="pictureupload">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <p>Click to upload image</p>
                <input type="file" name="blog_image" style="display: none" id="blog_image" accept="image/*">
              </label>

              <div class="txt_field">
                <div class="label">Blog Title </div>
                <div class="input">
                    <input type="text" name="blog_Title" id="blog_Title" required>
                </div>
              </div>


              <div class="txt_field">
                <div class="label">blog description</div>
                <div class="input">
                    <textarea required name="blog_dis" id="blog_dis" cols="30" rows="10"></textarea>
                </div>
              </div>


              <div class="create_btn">
                <button type="submit" class="secondary_btn">Create Blogs</button>
              </div>
        </div>
      </div>
    </div>
</div>


<script>
     $("#create_blog").submit(function (e) {
    e.preventDefault();
    var postData = new FormData($("#create_blog")[0]);
    $("#blogUpdateBtn").attr("disabled", "");
    $.ajax({
      type: "post",
      url: "/create-blogs",
      data: postData,
      dataType: "json",
      processData: false,
      contentType: false,
      success: function (response) {
      $("#blogUpdateBtn").removeAttr("disabled");
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

  function updateBlogs(id, name) {
    if (id != "" && id != " " && name != "" && name != " ") {
      $("#updateid").val(id);
      $("#updatename").val(name);
      $("#UpdateModel").modal('show');
    }
    else {
      toastr.warning("Something went wrong");
    }
  }

  $("#BlogUpdateForm").submit(function (e) {
    e.preventDefault();
    if ($("#updateid").val() != "" && $("#updateid").val() != " " && $("#updatename").val() != "" && $("#updatename").val() != " ") {
      $("#UpdateModel").modal('hide');
      $.ajax({
        type: "post",
        url: "/update-blogs",
        data: $("#BlogUpdateForm").serialize(),
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
      toastr.warning("Please fill blog title");
    }
  });

    function deleteBlogs(id) {
        if (id != "") {
            $.ajax({
                type: "post",
                url: "/delete-blogs",
                data: {action: 'delete', id: id, _token: $('meta[name="csrf-token"]').attr('content')},
                dataType: "json",
                success: function (response) {
                    if (response.error == 0) {
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
        }
        else{
            toastr.error("Sorry something went wrong", "Error");
        }
    }



</script>

@endsection
