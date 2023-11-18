@extends('dashboard.views.layouts.app')

@section('dashboard')

<div class="top_nav">
    <div class="bread_crumb">Dashboard > <span>Pre Orders</span></div>
</div>

<div class="categories">
    <div class="inner">
        <table>
            <thead>
                <tr>
                    <td>Product ID</td>
                    <td>User ID</td>
                    <td>Product Name</td>
                    <td>Quantity</td>
                    <td>Action</td>

                </tr>
            </thead>

            <tbody>
                @foreach ($preorder as $Preoders)
                <tr>
                    <td>{{ $Preoders->product_id }}</td>
                    <td>{{ $Preoders->user_id }}</td>
                    <td>{{ $Preoders->product_name }}</td>
                    <td>{{ $Preoders->quantity}}</td>
                     <td><button onclick="deletePreorder('{{ $Preoders->id}}')"><i
                        class="fa-solid fa-trash"></i></button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script>
    function deletePreorder(id) {
        if (id != "") {
            $.ajax({
                type: "post",
                url: "/delete-preorder",
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
