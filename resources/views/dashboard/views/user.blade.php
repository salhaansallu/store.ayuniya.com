@extends('dashboard.views.layouts.app')

@section('dashboard')

<div class="top_nav">
    <div class="bread_crumb">Dashboard > <span>User information</span></div>
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
                    <td></td>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->number }}</td>
                    <td>{{ getUserAddress($user->id)['address1'] }} <br>{{ getUserAddress($user->id)['postal'] }} <br>{{ getUserAddress($user->id)['city'] }} <br>{{ getUserAddress($user->id)['country'] }}</td>
                    <td><button onclick="deleteUser('{{ $user->id }}')">Detele</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script>
    function deleteUser(id) { 
        if (id != "") {
            $.ajax({
                type: "post",
                url: "/delete-user",
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