@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Registrations</h1>
                </div>

            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('admin.message')

            <div class="card">
                <form action="{{ route('fee.index') }}" method="GET">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <button type="button" id="reset-button" class="btn btn-default btn-sm">Reset</button>
                                </div>
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
                        </div>
                    </div>
                </form>

                <div class="card-body">
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Floor</th>
                                <th>Room NO</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customjs')
    <script>
        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('fee.index') }}",
                    data: function(d) {
                        d.floor_id = $('#floor').val();
                        d.user = $('#user').val();
                        d.search_by_room = $('#search_by_room').val();
                    }
                },
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
                    }
                ]
            });

            // Handle floor selection change
            $('#floor').on('change', function() {
                table.ajax.reload();
            });

            // Handle user search input change
            $('#user').on('keyup', function() {
                table.ajax.reload();
            });

            // Handle room search input change
            $('#search_by_room').on('keyup', function() {
                table.ajax.reload();
            });

            // Reset button functionality
            $('#reset-button').on('click', function() {
                $('#floor').val('');
                $('#user').val('');
                $('#search_by_room').val('');
                table.ajax.reload();
            });
        });
    </script>
@endsection
