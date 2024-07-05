@extends('admin.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Generate Fee</h1>
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
            @include('admin.message')
            <form id="FeeForm" enctype="multipart/form-data" name="FeeForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">


                            <input type="hidden" name="user_id" id="user_id" value="{{ $registration->id }}">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fee_date">Fee Date </label>
                                    <input type="date" name="fee_date" id="fee_date" class="form-control"
                                        placeholder="fee_date ">
                                    <p class="text-danger"></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount">Amount </label>
                                    <input type="number" name="amount" id="amount" class="form-control"
                                        placeholder="Amount">
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="paid_amount">Paid Amount </label>
                                    <input type="number" name="paid_amount" id="paid_amount" class="form-control"
                                        placeholder="Paid Amount">
                                    <p class="text-danger"></p>
                                </div>
                            </div>




                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="Unpaid">Unpaid</option>
                                        <option value="Paid">Paid</option>
                                        <option value="Partially-paid">Partially-paid</option>
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
        $("#FeeForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('fee.insert') }}',
                type: 'POST',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response.status === true) {
                        window.location.href = "{{ route('user_fee.list') }}";
                    } else {
                        if (response.message && response.message.includes('Fees already issued')) {
                            // Reload the same page
                            location.reload();
                        } else {
                            var errors = response.errors;
                            if (errors.fee_date) {
                                $("#fee_date").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.fee_date);
                            } else {
                                $("#fee_date").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback').html("");
                            }

                            if (errors.amount) {
                                $("#amount").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.amount);
                            } else {
                                $("#amount").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback').html("");
                            }

                            if (errors.paid_amount) {
                                $("#paid_amount").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.paid_amount);
                            } else {
                                $("#paid_amount").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback').html("");
                            }

                            if (errors.status) {
                                $("#status").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.status);
                            } else {
                                $("#status").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback').html("");
                            }
                        }
                    }
                },
                error: function() {
                    window.location.href = "{{ route('fee.index') }}";
                }
            });
        });
    </script>
@endsection
