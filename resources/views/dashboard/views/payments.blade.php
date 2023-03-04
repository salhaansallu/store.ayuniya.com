@extends('dashboard.views.layouts.app')

@section('dashboard')

<div class="top_nav">
    <div class="bread_crumb">Dashboard > <span>Vendor payments</span></div>
</div>

<div class="categories">
    <div class="inner">
        <table>
            <thead>
                <tr>
                    <td></td>
                    <td>Date</td>
                    <td>Total amount</td>
                    <td>Action</td>
                </tr>
            </thead>

            <tbody>
                @foreach ($reports as $report)
                    @if (getVendorTotal($report->id)['total'] != 0)
                    <tr>
                        <td><img src="{{ asset('assets/images/dashboard/store_icon.png') }}" alt=""> {{ $report->company_name }} ({{ $report->store_name }})</td>
                        <td>{{ date_format(getVendorTotal($report->id)['mindate'], "d-m-Y") }} - {{ date_format(getVendorTotal($report->id)['maxdate'], "d-m-Y") }}</td>
                        <td>{{ getVendorTotal($report->id)['total'] }}</td>
                        <td><button onclick="pay({{ $report->id }}, '{{ $report->payment_type }}')">Pay</button> <button onclick="pay({{ $report->id }}, 'cancel')">Cancel</button></td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script>
    function pay(id, type) {
        if (type == "Cheque") {
            cheque_no = prompt("Enter cheque number");
            if (cheque_no.length > 0) {
                $.ajax({
                    type: "post",
                    url: "/vendor-pay",
                    data: {action: 'pay', vendor: id, payment_type: type, cheque_no: cheque_no, _token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: "json",
                    success: function (response) {
                        if (response.error == 0) {
                            toastr.success(response.msg, "Success");
                            setInterval(() => {
                                window.open(response.url, '_blank');
                                location.reload()
                            }, 1000);
                        }
                        else{
                            toastr.error(response.msg, "Error");
                        }
                    }
                    
                });
            }
            else {
                toastr.error("Invalid cheque number", "Error");
            }
        }
        else if(type == "Bank transfer") {
            $.ajax({
                type: "post",
                url: "/vendor-pay",
                data: {action: 'pay', vendor: id, payment_type: type, _token: $('meta[name="csrf-token"]').attr('content')},
                dataType: "json",
                success: function (cash) {
                    if (cash.error == 0) {
                        toastr.success(cash.msg, "Success");
                        setInterval(() => {
                            window.open(cash.url, '_blank');
                            location.reload()
                        }, 1000);
                    }
                    else{
                        toastr.error(cash.msg, "Error");
                    }
                }
                
            });
        }
        else if(type == "cancel") {
            $.ajax({
                type: "post",
                url: "/vendor-pay",
                data: {action: 'pay', vendor: id, payment_type: type, _token: $('meta[name="csrf-token"]').attr('content')},
                dataType: "json",
                success: function (cancel) {
                    if (cancel.error == 0) {
                        toastr.success(cancel.msg, "Success");
                        setInterval(() => {
                            location.reload()
                        }, 2000);
                    }
                    else{
                        toastr.error(cancel.msg, "Error");
                    }
                }
                
            });
        }
        else {
            toastr.error("Sorry something went wrong", "Error");
        }
    }
</script>

@endsection