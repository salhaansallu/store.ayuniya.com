@extends('dashboard.views.layouts.app')

@section('dashboard')
    <div class="top_nav">
        <div class="bread_crumb">Dashboard > <span>Categories</span></div>
        <div class="create"><button class="secondary_btn" type="button" data-bs-toggle="modal" data-bs-target="#CreateModel"><i
                    class="fa-solid fa-plus"></i> Add new</button></div>
    </div>

    <div class="categories">
        <div class="inner">
            <table>
                <thead>
                    <tr>
                        <td>Category name</td>
                        <td>Sub category count</td>
                        <td>Action</td>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->category_name }}</td>
                            <td>{{ count($category->subcategories) }}</td>
                            <td><button type="button"
                                    onclick="updateCategory('{{ $category->id }}', '{{ $category->category_name }}')"><i
                                        class="fa-solid fa-pen"></i></button> <button
                                    onclick="deleteCategory('{{ $category->id }}')"><i
                                        class="fa-solid fa-trash"></i></button></td>
                        </tr>
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
                    <form action="" onsubmit="return false;" method="post" id="CategoryUpdateForm">
                        @csrf
                        <div class="head">Update category</div>
                        <div class="txt_field">
                            <div class="label">Category name</div>
                            <div class="input">
                                <input type="hidden" name="updateid" id="updateid" value="" required> <input
                                    type="text" name="updatename" id="updatename" value="" required> <button
                                    class="secondary_btn" type="submit">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Modal -->
    <div class="modal fade" id="CreateModel" tabindex="-1" aria-labelledby="CreateModelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="" onsubmit="return false;" method="post" id="AddCategoryForm">
                        @csrf
                        <div class="head">Add new category</div>
                        <div class="txt_field">
                            <div class="label">Category name</div>
                            <div class="input">
                                <input type="text" name="createcategory" id="createcategory" required> <button
                                    type="submit" class="secondary_btn">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateCategory(id, name) {
            if (id != "" && id != " " && name != "" && name != " ") {
                $("#updateid").val(id);
                $("#updatename").val(name);
                $("#UpdateModel").modal('show');
            } else {
                toastr.warning("Something went wrong");
            }
        }

        $("#CategoryUpdateForm").submit(function(e) {
            e.preventDefault();
            if ($("#updateid").val() != "" && $("#updateid").val() != " " && $("#updatename").val() != "" && $(
                    "#updatename").val() != " ") {
                $("#UpdateModel").modal('hide');
                $.ajax({
                    type: "post",
                    url: "/update-category",
                    data: $("#CategoryUpdateForm").serialize(),
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
                toastr.warning("Please fill category name");
            }
        });

        $("#AddCategoryForm").submit(function(e) {
            e.preventDefault();
            if ($("createcategory").val() != "" && $("createcategory").val() != " ") {
                $.ajax({
                    type: "post",
                    url: "/create-category",
                    data: $("#AddCategoryForm").serialize(),
                    dataType: "json",
                    success: function(result) {
                        if (result.error == 0) {
                            toastr.success(result.msg, "Success");
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            toastr.error(result.msg, "Error");
                        }
                    }
                });
            }
        });

        function deleteCategory(id) {
            if (confirm("Are you sure you want to delete?") == true) {
                $.ajax({
                    type: "post",
                    url: "/delete-category",
                    data: {
                        action: 'delete',
                        id: id,
                        _token: $("meta[name='csrf-token']").attr('content')
                    },
                    dataType: "json",
                    success: function(deleted) {
                        if (deleted.error == 0) {
                            toastr.success(deleted.msg, "Success");
                            setInterval(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            toastr.error(deleted.msg, "Error");
                        }
                    }
                });
            }
        }
    </script>
@endsection
