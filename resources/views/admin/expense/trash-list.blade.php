@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Trash Expenses</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('expense.index') }}" class="btn btn-primary">Expenses</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Expense Name</th>
                                <th>Amount</th>
                                <th>Paying Date</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customjs')
    <script>
        function delete_expense(id) {
            var url = '{{ route('expense.destroy', ':id') }}';
            url = url.replace(':id', id);
            if (confirm("Are you sure you want to delete this Expense?")) {
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
                            alert('Failed to delete the Expense.');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('An error occurred while trying to delete the Expense.');
                    }
                });
            }
        }

        function restore_expense(id) {
            var url = '{{ route('expense.restore', ':id') }}';
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {
                        window.location.href = "{{ route('expense.index') }}";
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert('An error occurred while trying to restore the Expense.');
                }
            });

        }

        function expense_permanentDelete(id) {
            var url = '{{ route('expense.permanentdelete', ':id') }}';
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {

                    } else {
                        alert('Failed to restore the Expense.');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert('An error occurred while trying to restore the Expense.');
                }
            });

        }

        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('expense.trashindex') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'expense_head.name',
                        name: 'expense_head.name'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'paying_date',
                        name: 'paying_date'
                    },
                    {
                        data: 'description',
                        name: 'description'
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
@endsection
