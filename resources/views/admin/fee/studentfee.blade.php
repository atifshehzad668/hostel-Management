@extends('admin.layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Students Fee</h1>
                </div>
                {{-- <div class="col-sm-6 text-right">
                    <a href="{{ route('student.create') }}" class="btn btn-primary">Add New Student</a>
                </div> --}}
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="modal fade" id="PayModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" id="paymentForm" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="paid_amount" class="form-label">Paid Amount</label>
                                <input type="number" class="form-control" id="paid_amount" placeholder="Enter paid amount">
                            </div>
                            <div class="mb-3 d-none">
                                <input type="hidden" class="form-control" id="registration_id">
                                <input type="hidden" class="form-control" id="fee_id">
                            </div>
                            <div class="mb-3 d-none">
                                <input type="hidden" class="form-control" name="total_amount" id="total_amount">
                            </div>
                            <div class="mb-3 d-none">
                                <input type="hidden" class="form-control" id="name">
                                <input type="hidden" class="form-control" id="father_name">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary payment_store">Paid</button>
                        </div>
                    </form>
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
                            <button type="button" onclick="window.location.href='{{ route('user_fee.list') }}'"
                                class="btn btn-default btn-sm">Reset</button>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <select name="floor_id" id="floor" class="form-control">
                                    <option value="">Select Floor</option>
                                    @foreach ($floors as $floor)
                                        <option value="{{ $floor->id }}"
                                            {{ request('floor_id') == $floor->id ? 'selected' : '' }}>
                                            {{ $floor->floor_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="search_by_room" name="search_by_room" class="form-control"
                                    placeholder="Search by Room" value="{{ request('search_by_room') }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="user" name="user" class="form-control"
                                    placeholder="Search by User" value="{{ request('user') }}">
                            </div>
                        </div>
                        <div class="card-tools">

                        </div>
                    </div>
                </form>
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
                <div class="card-footer clearfix">
                    {{-- {{ $students->links() }} --}}
                    {{-- <ul class="pagination pagination m-0 float-right">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul> --}}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customjs')
    <script>
        function deleteFee(id) {
            var url = '{{ route('user_fee.destroy', ':id') }}';
            url = url.replace(':id', id);
            if (confirm("Are you sure you want to delete this Fee?")) {
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
                            alert('Failed to delete the Fee.');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('An error occurred while trying to delete the Fee.');
                    }
                });
            }
        }

        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user_fee.list') }}",
                    data: function(d) {
                        d.floor_id = $('#floor').val();
                        d.search_by_room = $('#search_by_room').val();
                        d.user = $('#user').val();
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

            $('#floor').on('change', function() {
                table.draw();
            });

            $('#user').on('keyup', function() {
                table.draw();
            });

            $('#search_by_room').on('keyup', function() {
                table.draw();
            });

            $('#reset-button').on('click', function() {
                $('#floor').val('');
                $('#user').val('');
                $('#search_by_room').val('');
                table.draw();
            });
        });
    </script>
@endsection
