@extends('admin.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Rooms</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form id="roomForm" enctype="multipart/form-data" name="roomForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Room name</label>
                                    <input type="text" name="room_name" id="room_name" class="form-control"
                                        placeholder="room name">
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Select Floor</label>
                                    <select name="floor_id" id="floor_id" class="form-control">
                                        <option selected disabled>Select Floor</option>

                                        @foreach ($floors as $floor)
                                            <option value="{{ $floor->id }}">{{ $floor->floor_name }}</option>
                                        @endforeach

                                    </select>
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Room Type</label>
                                    <select name="room_type" id="room_type" class="form-control">
                                        <option value="" selected disabled>Select RoomType</option>
                                        <option value="Single">Single</option>
                                        <option value="Multiple">Multiple</option>
                                    </select>
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">NO Of Seats</label>
                                    <input type="text" name="no_of_seats" id="no_of_seats" class="form-control"
                                        placeholder="NO OF SEATS">
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                    <p class="text-danger"></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>

        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection


@section('customjs')
    <script>
        $("#roomForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('rooms.store') }}',
                type: 'Post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] == true) {
                        window.location.href = "{{ route('rooms.index') }}";
                        $("#room_name").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#floor_id").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");

                        $("#room_type").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#no_of_seats").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#status").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                    } else {
                        var errors = response['errors'];
                        if (errors['room_name']) {
                            $("#room_name").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['room_name']);
                        } else {
                            $("#room_name").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }
                        if (errors['floor_id']) {
                            $("#floor_id").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['floor_id']);
                        } else {
                            $("#floor_id").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }

                        if (errors['room_type']) {
                            $("#room_type").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['room_type']);
                        } else {
                            $("#room_type").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }
                        if (errors['no_of_seats']) {
                            $("#no_of_seats").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['no_of_seats']);
                        } else {
                            $("#no_of_seats").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }
                        if (errors['status']) {
                            $("#status").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['status']);
                        } else {
                            $("#status").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }


                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something Went Wrong");
                }
            });



        });
    </script>
@endsection
