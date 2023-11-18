@extends('dashboard.views.layouts.app')

@section('dashboard')

<div class="top_nav">
    <div class="bread_crumb">Dashboard > <span>Contact</span></div>
</div>

<div class="categories">
    <div class="inner">
        <table>
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Email</td>
                    <td>Mobile</td>
                    <td>Address</td>
                    <td>Action</td>

                </tr>
            </thead>

            <tbody>
                @foreach ($contact as $contacts)
                <tr>
                    <td>{{ $contacts->name }}</td>
                    <td>{{ $contacts->email }}</td>
                    <td>{{ $contacts->tp_no }}</td>
                    <td>{{ $contacts->message }}</td>
                     <td><button onclick="deleteContact('{{ $contacts->id }}')">Detele</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script>
    function deleteContact(id) {
        if (id != "") {
            $.ajax({
                type: "post",
                url: "/delete-contact",
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
