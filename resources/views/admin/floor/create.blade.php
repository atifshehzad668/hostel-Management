@extends('admin.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Floor</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('floor.index')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form id="FloorForm" enctype="multipart/form-data" name="FloorForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Floor name</label>
                                    <input type="text" name="floor_name" id="floor_name" class="form-control"
                                        placeholder="Floor name">
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Description</label>
                                    <textarea name="description" id="description" class="form-control" cols="30" rows="10"></textarea>
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Total rooms</label>
                                    <input type="text" name="total_rooms" id="total_rooms" class="form-control"
                                        placeholder="Total Rooms">
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
        $("#FloorForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('floor.store') }}',
                type: 'Post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] == true) {
                        window.location.href = "{{ route('floor.index') }}";
                        $("#floor_number").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");

                        $("#description").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#total_rooms").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                    } else {
                        var errors = response['errors'];
                        if (errors['floor_number']) {
                            $("#floor_number").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['floor_number']);
                        } else {
                            $("#floor_number").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }

                        if (errors['description']) {
                            $("#description").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['description']);
                        } else {
                            $("#description").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }
                        if (errors['total_rooms']) {
                            $("#total_rooms").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['total_rooms']);
                        } else {
                            $("#total_rooms").removeClass('is-invalid').siblings('p').removeClass(
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
