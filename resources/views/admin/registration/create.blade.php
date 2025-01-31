@extends('admin.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Registrations</h1>
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
            <form id="registrationForm" enctype="multipart/form-data" name="registrationForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Registration name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="registration name">
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="father_name">Father name</label>
                                    <input type="text" name="father_name" id="father_name" class="form-control"
                                        placeholder="father name">
                                    <p class="text-danger"></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cnic">Floor </label>
                                    <select name="floor_id" id="floor" class="form-control">
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
                                    <label for="cnic">Room No </label>
                                    <select name="room_id" id="rooms" class="form-control">

                                    </select>
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cnic">Cnic </label>
                                    <input type="number" name="cnic" id="cnic" class="form-control"
                                        placeholder="Cnic ">
                                    <p class="text-danger"></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address">Address </label>
                                    <input type="text" name="address" id="address" class="form-control"
                                        placeholder="Address">
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image">Student Images</label>
                                    <input type="file" name="images[]" id="images" accept="image/*"
                                        class="form-control" multiple>
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="registration_date">Registration date</label>
                                    <input type="date" name="registration_date" id="registration_date"
                                        class="form-control">
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone_no">Phone No</label>
                                    <input type="number" name="phone_no" id="phone_no" class="form-control">
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="whatsapp_no">whatsapp No</label>
                                    <input type="number" name="whatsapp_no" id="whatsapp_no" class="form-control">
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dob">DOB</label>
                                    <input type="date" name="dob" id="dob" class="form-control">
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control">
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
        $("#registrationForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $("button[type='submit']").prop('disabled', true);
            $.ajax({
                url: '{{ route('registration.store') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] == true) {
                        window.location.href = "{{ route('registration.index') }}";
                        $("#name").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#father_name").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#floor").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#room_id").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");

                        $("#cnic").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#address").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#registration_date").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#phone_no").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#whatsapp_no").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#dob").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#email").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#status").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                    } else {
                        var errors = response['errors'];
                        if (errors['name']) {
                            $("#name").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['name']);
                        } else {
                            $("#name").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }
                        if (errors['father_name']) {
                            $("#father_name").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['father_name']);
                        } else {
                            $("#father_name").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }

                        if (errors['cnic']) {
                            $("#cnic").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['cnic']);
                        } else {
                            $("#cnic").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }
                        if (errors['address']) {
                            $("#address").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['address']);
                        } else {
                            $("#address").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }
                        if (errors['registration_date']) {
                            $("#registration_date").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['registration_date']);
                        } else {
                            $("#registration_date").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }
                        if (errors['phone_no']) {
                            $("#phone_no").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['phone_no']);
                        } else {
                            $("#phone_no").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }
                        if (errors['whatsapp_no']) {
                            $("#whatsapp_no").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['whatsapp_no']);
                        } else {
                            $("#whatsapp_no").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }
                        if (errors['dob']) {
                            $("#dob").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['dob']);
                        } else {
                            $("#dob").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }
                        if (errors['email']) {
                            $("#email").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['email']);
                        } else {
                            $("#email").removeClass('is-invalid').siblings('p').removeClass(
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
                        if (errors['floor']) {
                            $("#floor").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['floor']);
                        } else {
                            $("#floor").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }
                        if (errors['room_id']) {
                            $("#room_id").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['room_id']);
                        } else {
                            $("#room_id").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");

                        }


                    }
                },
                error: function() {
                    console.log("Something Went Wrong");
                }
            });
        });




        $("#floor").on('change', function() {
            var floor = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('filter.rooms') }}",
                data: {
                    'floor': floor
                },
                // asdfasfasdfasd
                success: function(data) {
                    $("#rooms").html('');
                    if (data.rooms.length > 0) {
                        var html = `<option value="">Select room....</option>`;
                        $.each(data.rooms, function(key, value) {
                            html += `<option value="${value.id}">${value.room_name}</option>`;
                        });
                        $("#rooms").html(html);
                    } else {
                        $("#rooms").html("<option value=''>No Rooms Available</option>");
                    }
                },
                error: function(err) {
                    console.log(err.responseText)
                }
            });
        });
    </script>
@endsection
