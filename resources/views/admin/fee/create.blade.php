@extends('admin.layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Students Fee</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">


        <!-- Modal -->
        <div class="modal fade" id="payfee" tabindex="-1" role="dialog" aria-labelledby="payfee" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pay Fee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="">Paid Amount</label>
                        <input type="text" class="form-control" name="paid_amount" id="paid_amount">
                        <input type="hidden" class="form-control" name="registration_id" id="registration_id">
                        <input type="date" class="form-control d-none" name="fee_date" id="fee_date">
                        <input type="hidden" class="form-control" name="amount" id="amount">
                        <input type="hidden" class="form-control" name="fee_id" id="fee_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary payFee">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <button type="button" onclick="window.location.href='{{ route('fee.create') }}'"
                        class="btn btn-default btn-sm">Reset</button>

                </div>
            </div>
            @include('admin.message')
            <div class="card">
                <form id="feeGenarate">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="date" id="date" name="date" class="form-control"
                                    placeholder="Search by Date">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary payFee" type="submit" id="submit">Generate fee</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="filterbyname"
                                        placeholder="Filter By Name">
                                </div>
                                <div class="col-md-8">
                                    <form action="">
                                        <div class="row">





                                            <div class="col-md-3">
                                                <input type="date" class="form-control" id="start_date">
                                            </div>




                                            <div class="col-md-3">
                                                <input type="date" class="form-control" id="end_date">
                                            </div>


                                            <div class="col-md-3">
                                                <button class="btn btn-primary" type="submit" id="datebetween">Date
                                                    Between</button>
                                            </div>



                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
            <div class="card-body">
                <table id="mytable" class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th>Registered Student</th>
                            <th>Fee Date</th>
                            <th>Amount</th>
                            <th>Paid Amount</th>
                            <th>Status</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('customjs')
    <script>
        $(document).ready(function() {
            $("#feeGenarate").submit(function(event) {
                event.preventDefault();
                $("#submit").prop("disabled", true);

                var dateValue = $("#date").val();
                var formData = new FormData();
                formData.append("date", dateValue);

                $.ajax({
                    type: "POST",
                    url: "{{ route('generate_fee') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        window.location.href = "{{ route('fee.create') }}";
                    },
                    error: function(e) {
                        console.log(e.responseText);
                        $("#submit").prop("disabled", false);
                        if (e.responseJSON && !e.responseJSON.status) {
                            window.location.href = "{{ route('fee.create') }}";
                        }
                    }
                });
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('fee.index') }}",
                    data: function(d) {
                        d.name = $('#filterbyname').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'registration.name',
                        name: 'registration.name'
                    },
                    {
                        data: 'fee_date',
                        name: 'fee_date'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#filterbyname').on('keyup', function() {
                table.ajax.reload();
            });
            $('#datebetween').on('click', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });
        });

        $('#reset-button').on('click', function() {
            $('#start_date').val('');
            $('#end_date').val('');
            table.draw();
        });
    </script>


    <script>
        $(document).ready(function() {
            $("#mytable").on("click", ".pay_button", function(event) {
                event.preventDefault();
                var id = $(this).attr('data-id');
                $("#payfee").modal('show');
                $.ajax({
                    type: "GET",
                    url: '{{ route('get.fee') }}',
                    data: {
                        id: id
                    },
                    success: function(data) {

                        var fee = data.fee;


                        $('#paid_amount').val('');
                        $('#registration_id').val(fee.registration_id);
                        $('#fee_date').val(fee.fee_date);
                        $('#amount').val(fee.amount);
                        $('#fee_id').val(fee.id);
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    }
                });
            });
        });





        $("#payfee").on("click", ".payFee", function() {
            var feeId = $('#fee_id').val();
            var updatedata = {
                'id': feeId,
                'paid_amount': $('#paid_amount').val(),
                'registration_id': $('#registration_id').val(),
                'fee_date': $('#fee_date').val(),
                'amount': $('#amount').val(),
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "{{ route('pay.fee') }}",
                data: updatedata,
                success: function(data) {



                    $("#payfee").modal('hide');
                    window.location.href = "{{ route('fee.create') }}";

                },
                error: function(e) {

                    console.log('Error:', e.responseText);
                }
            });
        });
    </script>
@endsection
