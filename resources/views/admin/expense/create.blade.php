@extends('admin.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Expense </h1>
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
            <form id="expenseForm" enctype="multipart/form-data" name="expenseForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cnic">Expense Head </label>
                                    <select name="expense_head_id" id="expense_head_id" class="form-control">
                                        <option selected disabled>Select Expense Head</option>

                                        @foreach ($expenseHeads as $expenseHead)
                                            <option value="{{ $expenseHead->id }}">{{ $expenseHead->name }}</option>
                                        @endforeach
                                    </select>
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
                                    <label for="Date">Date </label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        placeholder="Date">
                                    <p class="text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name"> Description</label>
                                    <textarea name="description" id="description" placeholder="bill,other staff" class="form-control" cols="30"
                                        rows="10"></textarea>
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
        $("#expenseForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('expense.store') }}',
                type: 'Post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    window.location.href = "{{ route('expense.index') }}";
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] == true) {

                        $("#expense_name").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");
                        $("#description").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback')
                            .html("");

                    } else {
                        var errors = response['errors'];
                        if (errors['expense_name']) {
                            $("#expense_name").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['expense_name']);
                        } else {
                            $("#expense_name").removeClass('is-invalid').siblings('p').removeClass(
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



                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something Went Wrong");
                }
            });



        });
    </script>
@endsection
