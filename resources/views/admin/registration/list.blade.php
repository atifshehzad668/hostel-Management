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
                    <h1>Registrations</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('registration.create') }}" class="btn btn-primary">Register Student</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <form action="" method="GET">
                    <div class="card-header">
                        <div class="card-title">
                            <button type="button" onclick="window.location.href='{{ route('registration.index') }}'"
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
                    <table class="table table-bordered data-table ">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Name</th>
                                <th>Floor</th>
                                <th>Room NO</th>
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
                ajax: "{{ route('registration.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'floor.floor_name',
                        name: 'floor.floor_name'
                    },
                    {
                        data: 'room.room_name',
                        name: 'room.room_name'
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
        function deleteRegistration(id) {
            var url = '{{ route('registration.destroy', ':id') }}';
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
                            alert('Failed to delete the registration.');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('An error occurred while trying to delete the registration.');
                    }
                });
            }
        }
    </script>
@endsection
