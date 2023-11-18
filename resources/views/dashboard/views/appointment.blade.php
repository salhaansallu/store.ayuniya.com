@extends('dashboard.views.layouts.app')

@section('dashboard')

<div class="top_nav">
    <div class="bread_crumb">Dashboard > <span>Appointments</span></div>
</div>

<div class="categories">
    <div class="inner">
        <table>
            <thead>
                <tr>
                    <td></td>
                    <td>Date and Time</td>
                    <td>Mobile</td>
                    <td>Action</td>
                </tr>
            </thead>

            <tbody>
                @foreach ($apps as $app)
                    <tr>
                        <td><img src="{{ asset('assets/images/dashboard/user_icon.png') }}" alt=""> {{ $app->name }}</td>
                        <td>{{ date("d-m-Y h:i A", strtotime($app->app_date)) }}</td>
                        <td>{{ $app->number }}</td>
                        <td>@if(empty($app->status) || $app->status == "declined") <button onclick="book('{{ $app->app_id }}')">Accept</button> @endif @if(empty($app->status) || $app->status == "booked") <button onclick="decline('{{ $app->app_id }}')" style="background-color: red;">Decline</button> @endif</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script>
    function book(id) {
        if (id != "") {
            $.ajax({
                type: "post",
                url: "/book",
                data: {action: "book", id: id, _token: $("meta[name='csrf-token']").attr('content')},
                dataType: "json",
                success: function (response) {
                    if (response.error == 0) {
                        toastr.success(response.msg, 'Success');
                        setInterval(() => {
                            location.reload();
                        }, 2000);
                    }
                    else {
                        toastr.error(response.msg, 'Error');
                    }
                }
            });
        }
    }

    function decline(id) {
        if (id != "") {
            $.ajax({
                type: "post",
                url: "/book",
                data: {action: "decline", id: id, _token: $("meta[name='csrf-token']").attr('content')},
                dataType: "json",
                success: function (decline) {
                    if (decline.error == 0) {
                        toastr.success(decline.msg, 'Success');
                        setInterval(() => {
                            location.reload();
                        }, 2000);
                    }
                    else {
                        toastr.error(decline.msg, 'Error');
                    }
                }
            });
        }
    }
</script>

@endsection