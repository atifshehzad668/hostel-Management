@extends('admin.layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Floors</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('expense_head.create') }}" class="btn btn-primary">Add expense_head</a>
                </div>
                <div class="col-sm-12 mt-4 text-right">
                    <a href="{{ route('expense_head.trashindex') }}" class="btn btn-danger">Trash</a>
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
                            <button type="button" onclick="window.location.href='{{ route('floor.index') }}'"
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
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Expense Name</th>
                                <th>Description</th>


                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{-- {{ $floors->links() }} --}}
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
        function delete_expense_head(id, relatedExpensesCount) {
            if (relatedExpensesCount > 0) {
                alert("Expense Head is used in other records and cannot be deleted.");
                return;
            }
            var url = '{{ route('expense_head.destroy', ':id') }}';
            url = url.replace(':id', id);
            if (confirm("Are you sure you want to delete this Expense_head?")) {
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
                            alert('Failed to delete the Expense_head.');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('An error occurred while trying to delete the Expense_head.');
                    }
                });
            }
        }
    </script>
    <script type="text/javascript">
        $(function() {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('expense_head.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
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
