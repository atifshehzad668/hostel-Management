@extends('admin.layouts.app')
{{--
@push('custom-style')
    <style>
        .dataTables_filter {
            text-align: right;
        }
    </style>
@endpush --}}
@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Staff Registrations</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('staff.create') }}" class="btn btn-primary">Register Staff</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">

        <div class="modal fade" id="pay_to_staff" tabindex="-1" role="dialog" aria-labelledby="pay_to_staff"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pay to Staff</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="">Paid Amount</label>
                        <input type="text" class="form-control" name="paid_amount" id="paid_amount">
                        <input type="hidden" class="form-control" name="name" id="name">
                        <input type="hidden" class="form-control" name="registration_id" id="father_name">
                        <input type="hidden" class="form-control" name="cnic" id="cnic">
                        <input type="hidden" class="form-control" name="amount" id="address">
                        <input type="hidden" class="form-control" name="phone_no" id="phone_no">
                        <input type="hidden" class="form-control" name="staff_id" id="staff_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary staff_payment">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <form action="" method="GET">
                    <div class="card-header">
                        <div class="card-title">
                            <button type="button" onclick="window.location.href='{{ route('staff.index') }}'"
                                class="btn btn-default btn-sm">Reset</button>

                        </div>
                        <div class="card-tools">
                            {{-- <div class="input-group input-group" style="width: 250px;">
                                <input type="text" name="keyword" value="{{ $request->get('keyword') }}"
                                    class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </form>
                <div class="card-body">
                    <table class="table table-bordered data-table " id="mytable">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Name</th>
                                <th>Father Name</th>
                                <th>Cnic</th>

                                {{-- <th>CNIC</th>
                                <th>Address</th>
                                <th>Image</th>
                                <th>Registration Date</th>
                                <th>Phone</th>
                                <th>Whats App</th>
                                <th>DOB</th>
                                <th>Email</th>
                                <th>Status</th> --}}

                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customjs')
    <script type="text/javascript">
        $(function() {

            var table = $('.data-table').DataTable({

                processing: true,
                serverSide: true,
                ajax: "{{ route('staff.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'father_name',
                        name: 'father_name'
                    },
                    {
                        data: 'cnic',
                        name: 'cnic'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]

            });

        });
    </script>
    <script>
        function deleteStaff(id) {
            var url = '{{ route('staff.destroy', ':id') }}';
            url = url.replace(':id', id);
            if (confirm("Are you sure you want to delete this registration?")) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status) {
                            location.reload();
                        } else {
                            alert('Failed to delete the staff registration.');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('An error occurred while trying to delete the staff registration.');
                    }
                });
            }
        }
    </script>







    <script>
        $(document).ready(function() {
            $("#mytable").on("click", ".pay_button", function(event) {
                event.preventDefault();
                var id = $(this).attr('data-id');
                $("#pay_to_staff").modal('show');
                $.ajax({
                    type: "GET",
                    url: '{{ route('staff.get_staff') }}',
                    data: {
                        id: id
                    },
                    success: function(data) {

                        var staff = data.staff;


                        $('#name').val(staff.name);
                        $('#father_name').val(staff.father_name);
                        $('#cnic').val(staff.cnic);
                        $('#address').val(staff.address);
                        $('#phone_no').val(staff.phone_no);
                        $('#staff_id').val(staff.id);
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    }
                });
            });
        });




        $("#pay_to_staff").on("click", ".staff_payment", function() {
            var staffId = $('#staff_id').val();
            var updatedata = {
                'id': staffId,
                'paid_amount': $('#paid_amount').val(),
                'name': $('#name').val(),
                'father_name': $('#father_name').val(),
                'address': $('#address').val(),
                'phone_no': $('#phone_no').val(),
                'cnic': $('#cnic').val(),
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "{{ route('staff.pay_staff') }}",
                data: updatedata,
                success: function(data) {



                    $("#pay_to_staff").modal('hide');
                    window.location.href = "{{ route('staff.index') }}";

                },
                error: function(e) {

                    console.log('Error:', e.responseText);
                }
            });
        });
    </script>
@endsection
