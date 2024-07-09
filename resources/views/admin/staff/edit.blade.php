@extends('admin.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Staff</h1>
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
            <form id="staffForm" enctype="multipart/form-data" name="staffForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name"> Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Staff name" value="{{ $staff->name }}">
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="father_name">Father name</label>
                                    <input type="text" name="father_name" id="father_name" class="form-control"
                                        placeholder="father name" value="{{ $staff->father_name }}">
                                    <p class="text-danger"></p>
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cnic">Cnic </label>
                                    <input type="number" name="cnic" id="cnic" class="form-control"
                                        placeholder="Cnic "value="{{ $staff->cnic }}">
                                    <p class="text-danger"></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address">Address </label>
                                    <input type="text" name="address" id="address" class="form-control"
                                        placeholder="Address" value="{{ $staff->address }}">
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image">Staff Images</label>
                                    <input type="file" name="images[]" id="images" accept="image/*"
                                        class="form-control" multiple>
                                    <p class="text-danger"></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone_no">Phone No</label>
                                    <input type="number" name="phone_no" id="phone_no" class="form-control"
                                        value="{{ $staff->phone_no }}">
                                    <p class="text-danger"></p>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ $staff->email }}">
                                    <p class="text-danger"></p>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="Active" {{ $staff->status == 'Active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="Inactive" {{ $staff->status == 'Inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>

                                    <p class="text-danger"></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
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
        $("#staffForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $("button[type='submit']").prop('disabled', true);
            $.ajax({
                url: '{{ route('staff.update', $staff->id) }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] == true) {
                        window.location.href = "{{ route('staff.index') }}";
                        $("#name").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#father_name").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");


                        $("#cnic").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#address").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");

                        $("#phone_no").removeClass('is-invalid').siblings('p').removeClass(
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

                        if (errors['phone_no']) {
                            $("#phone_no").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['phone_no']);
                        } else {
                            $("#phone_no").removeClass('is-invalid').siblings('p').removeClass(
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






                    }
                },
                error: function() {
                    console.log("Something Went Wrong");
                }
            });
        });
    </script>
@endsection
