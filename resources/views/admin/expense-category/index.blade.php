@extends('layouts.admin.master')

@section('title', 'Expense Categories')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Expense Categories</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Expense Categories</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manage Expense Categories</h3>
                    <div class="card-tools">
                        @permission('expense-category-create')
                        <button type="button" class="btn btn-primary btn-sm" id="addCategoryBtn">
                            <i class="fas fa-plus"></i> Add Category
                        </button>
                        @endpermission
                    </div>
                </div>
                <div class="card-body">
                    <table id="categoryTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="10%">#</th>
                                <th width="40%">Category Name</th>
                                <th width="30%">Created By</th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Expense Category</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="addCategoryForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Expense Category</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="editCategoryForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editCategoryId">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editCategoryName" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // DataTable
            var table = $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('expense-category.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'created_by', name: 'created_by'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // Add Category
            $('#addCategoryBtn').click(function() {
                $('#addCategoryForm')[0].reset();
                $('#addCategoryModal').modal('show');
            });

            $('#addCategoryForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('expense-category.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#addCategoryModal').modal('hide');
                            table.ajax.reload();
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Failed to add category.');
                        }
                    }
                });
            });

            // Edit Category
            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                $('#editCategoryId').val(id);
                $('#editCategoryName').val(name);
                $('#editCategoryModal').modal('show');
            });

            $('#editCategoryForm').submit(function(e) {
                e.preventDefault();
                var id = $('#editCategoryId').val();
                $.ajax({
                    url: "/dashboard/expense-categories/" + id,
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#editCategoryModal').modal('hide');
                            table.ajax.reload();
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Failed to update category.');
                        }
                    }
                });
            });

            // Delete Category
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this category?')) {
                    $.ajax({
                        url: "/dashboard/expense-categories/" + id,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload();
                                toastr.success(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON.message || 'Failed to delete category.');
                        }
                    });
                }
            });
        });
    </script>
@endpush

