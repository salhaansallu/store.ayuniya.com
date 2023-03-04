@extends('layouts.app')

@section('content')

@if(Session::get('appmessage'))
    <script>
        $(document).ready(function () {
            if ("{{ Session::get('appmessage') }}" == "success") {
                toastr.success("Appintment booked succesfully", "Success");
            }
            else if ("{{ Session::get('appmessage') }}" == "booked") {
                toastr.warning("You have already booked for this date", "Warning");
            }
            else {
                toastr.error("Please try again later", "Error");
            }
        });
    </script>
@endif

    <div class="appointment">
        <div class="head">{{ date("M/Y") }}</div>
        <div class="data_row">
            @for ($i = 1; $i <= (date("t", strtotime(date("d/m/Y")))-date("d")); $i++)
            <div class="col col-lg-1">
                <div class="data">Date : <span>{{ date("d/m/Y", strtotime($i." days")) }}</span></div>
                <div class="data text-center"><span>Available</span></div>
                <div class="book_btn"><button onclick="book('{{ date('d-m-Y', strtotime($i.' days')) }}')">Book now</button></div>
            </div>
            @endfor
        </div>
    </div>

    <div class="appointment">
        <div class="head">{{ date("M/Y", strtotime("1 month")) }}</div>
        <div class="data_row">
            @for ($i = 1; $i <= date("t", strtotime("1 month")); $i++)
            <div class="col col-lg-1">
                <div class="data">Date : <span>{{ date($i."/m/Y", strtotime("1 month")) }}</span></div>
                <div class="data text-center"><span>Available</span></div>
                <div class="book_btn"><button onclick="book('{{ date($i.'-m-Y', strtotime('1 month')) }}')">Book now</button></div>
            </div>
            @endfor
        </div>
    </div>

<div class="modal fade" id="diseaseModal" tabindex="-1" aria-labelledby="diseaseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
            <label for="">Select disease</label><br>
            <select class="form-select mt-2" aria-label="select disease" id="disease">
                <option value="Arthritis">Arthritis</option>
                <option value="Autism">Autism</option>
                <option value="OPD or other">OPD or other</option>
            </select><br>
            <button data-bs-dismiss="modal" class="primary_btn m-0" id="book_now">Book now</button>
        </div>
      </div>
    </div>
  </div>

    <script>
        date = 0;
        function book(app_date) {
            $("#diseaseModal").modal("show");
            date = app_date;
        }

        $("#book_now").click(function (e) { 
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "/book/hospital/"+date,
                data: {des : $("#disease").val(), _token : $("meta[name='csrf-token']").attr('content')},
                dataType: "json",
                success: function (response) {
                    if (response.error == 0) {
                        toastr.success(response.msg, "Success");
                    }
                    else if (response.error == 2) {
                        toastr.warning(response.msg, "Warning");
                    }
                    else if(response.error == 3 && response.msg == "login") {
                        location.href="/login";
                    }
                    else {
                        toastr.error(response.msg, "Error");
                    }
                }
            });
        });
    </script>
@endsection