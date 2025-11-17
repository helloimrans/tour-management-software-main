@extends('layouts.admin.master')
@section('title')
    Roles
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header d-flex justify-content-between">
                                <h4>Roles List</h4>
                                @permission('roles-create')
                                <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-circle-plus"></i> Add New
                                </a>
                                @endpermission
                            </div>
                            <div class="table-responsive">
                                <table class="table datatable custom-table dt-responsive nowrap">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('utils.delete-confirm-modal')
@endsection

@push('js')
    <script>
        var table;
        $(function() {
            table = $('.datatable').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('roles.datatable') }}",
                columns: [{
                        title: "SL#",
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        searchable: false,
                        orderable: false
                    },
                    {
                        title: "Name",
                        data: "name"
                    },
                    {
                        title: "Display Name",
                        data: "display_name"
                    },
                    {
                        title: "Description",
                        data: "description"
                    },
                    {
                        title: "Action",
                        data: "action",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $(document).on('click', '.delete', function(e) {
                e.preventDefault();
                $('#delete_form')[0].action = $(this).data('action');
                $('#delete_modal').modal('show');
            });
        });
    </script>
@endpush
