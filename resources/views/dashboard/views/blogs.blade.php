@extends('dashboard.views.layouts.app')

@section('dashboard')
    <div class="top_nav">
        <div class="bread_crumb">Dashboard > <span>Blogs</span></div>
        <div class="create"><button class="secondary_btn" type="button" data-bs-toggle="modal" data-bs-target="#CreateModel"><i
                    class="fa-solid fa-plus"></i> Add new</button>
        </div>
    </div>

    <div class="blogs">
        <!-- Display success message if it exists -->
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <!-- Display error message if it exists -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

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
                            <td> <!-- Inside the foreach loop where the update button is generated -->
                                <button type="button"
                                    onclick="updateBlog('{{ $blog->id }}','{{ $blog->blog_image }}', '{{ $blog->blog_Title }}','{{ $blog->blog_dis }}')">
                                    <i class="fa-solid fa-pen"></i>
                                </button>


                                <button onclick="deleteBlogs('{{ $blog->id }}')"><i
                                        class="fa-solid fa-trash"></i></button>
                            </td>

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
                    <form action="{{ route('create-blogs') }}" method="post" enctype="multipart/form-data"
                        id="create_blog">
                        @csrf
                        <div class="head">Add Blog post</div>

                        <label for="blog_image" class="pictureupload">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <p id="selectedFileName">Click to upload image</p>
                            <input type="file" name="blog_image" style="display: none" id="blog_image" accept="image/*"
                                onchange="updateFileName(event)">
                        </label>



                        <div class="txt_field">
                            <div class="label">Blog Title </div>
                            <div class="input">
                                <input type="text" name="blog_Title" id="blog_Title" required>
                            </div>
                        </div>

                        <div class="txt_field">
                            <div class="label">Blog Description </div>
                            <div class="input">
                                <input type="text" name="blog_dis" id="blog_dis" required>
                            </div>
                        </div>

                        <div class="txt_field">
                            <div class="label">Blog Content</div>
                            <div class="input">
                                <textarea required name="blog_content" id="blog_content" cols="30" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="create_btn">
                            <button type="submit" class="secondary_btn">Create Blog</button>
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
                    <form action="" onsubmit="return false;" method="post" id="BlogUpdateForm">
                        @csrf
                        <div class="head">Update Blog</div>


                        <div class="txt_field">
                            <div class="label">Blog Image</div>
                            <div class="input">
                                <label for="blog_image" class="pictureupload">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <p id="selectedFileName">Click to upload image</p>
                                </label>
                            </div>
                            <input type="text" name="updateimage" style="display: none" id="updateimage" accept="image/*"
                                required>
                        </div>

                        <div class="txt_field">
                            <div class="label">Blog Title</div>
                            <div class="input">
                                <input type="text" name="updatename" id="updatename" value="" required>
                                <input type="hidden" name="updateid" id="updateid" value="" required>
                            </div>
                        </div>

                        <div class="txt_field">
                            <div class="label">Blog Description</div>
                            <div class="input">
                                <input type="text" name="updatedis" id="updatedis" value="" required>
                                <input type="hidden" name="updatedisid" id="updatedisid" value="" required>
                            </div>
                        </div>


                        <div class="txt_field">
                            <div class="label">Blog Content</div>
                            <div class="input">
                                 <textarea input type="text" name="updatecont" id="updatecont"  cols="30" rows="5" value="" required>
                                 </textarea>

                            </div>
                        </div>
                        <button class="secondary_btn" type="submit">Update</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $("#create_blog").submit(function(e) {
            e.preventDefault();
            var postData = new FormData($("#create_blog")[0]);
            $(".secondary_btn").attr("disabled", true); // Assuming this is the correct button class

            $.ajax({
                type: "post",
                url: "{{ route('create-blogs') }}",
                data: postData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $(".secondary_btn").removeAttr("disabled");

                    if (response.error == 0) {
                        toastr.success(response.msg, "Success");
                        $('#CreateModel').modal('hide'); // Close the modal

                        // Redirect to the blogs page after a short delay
                        setTimeout(function() {
                            window.location.href = "{{ url('/web-admin/blogs') }}";
                        }, 1500);
                    } else {
                        toastr.error(response.msg, "Error");
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error(error, "Error");
                    $(".secondary_btn").removeAttr("disabled");
                }
            });
        });

        function updateBlog(id, blog_image, blog_Title, blog_dis, blog_content) {
            if (id != "" && id != " " && blog_image != "" && blog_image != " " && blog_Title != "" && blog_Title != " " &&
                blog_dis != "" && blog_dis != " " && blog_content != "" && blog_content != " ") {
                $("#updateid").val(id);
                $("#updateimage").val(blog_image);
                $("#updatename").val(blog_Title);
                $("#updatedis").val(blog_dis);
                $("#updatecont").val(blog_content);
                $("#UpdateModel").modal('show');
            } else {
                toastr.warning("Something went wrong");
            }
        }

        $("#BlogUpdateForm").submit(function(e) {
            e.preventDefault();
            if ($("#updateid").val() != "" && $("#updateid").val() != " " && $("#updateimage").val() != "" && $(
                    "#updateimage").val() != " " && $("#updatename").val() != "" && $(
                    "#updatename").val() != " " && $("#updatedis").val() != "" && $("#updatedis").val() != " " && $(
                    "#updatecont").val() != "" && $("#updatecont").val() != " ") {
                $("#UpdateModel").modal('hide');
                $.ajax({
                    type: "post",
                    url: "/update-blogs",
                    data: $("#BlogUpdateForm").serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.error == 0) {
                            toastr.success(response.msg, "Success");
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            toastr.error(response.msg, "Error");
                        }
                    }
                });
            } else {
                toastr.warning("Please fill Blog name");
            }
        });


        function deleteBlogs(id) {
            if (id != "") {
                $.ajax({
                    type: "post",
                    url: "/delete-blogs",
                    data: {
                        action: 'delete',
                        id: id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.error == 0) {
                            toastr.success(response.msg, "Success");
                            setInterval(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            toastr.error(response.msg, "Error");
                        }
                    }
                });
            } else {
                toastr.error("Sorry something went wrong", "Error");
            }
        }
    </script>
@endsection
