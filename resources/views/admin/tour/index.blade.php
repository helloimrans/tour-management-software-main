@extends('layouts.admin.master')
@section('title', 'Tours List')

@push('css')
@endpush

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header d-flex justify-content-between">
                                <h4>Tours List</h4>
                                @permission('tour-create')
                                <a href="{{ route('tour.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus-circle"></i> Add New
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
@endsection

@push('js')
    <script>
        $(function() {
            $('.datatable').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('tour.index') }}",
                columns: [
                    {
                        title: "SL#",
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        searchable: false,
                        orderable: false,
                    },
                    {
                        title: 'Image',
                        data: 'image',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        title: 'Name',
                        data: 'name'
                    },
                    {
                        title: 'Description',
                        data: 'description'
                    },
                    {
                        title: 'Status',
                        data: 'status',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        title: 'Created By',
                        data: 'created_by_name',
                        orderable: false,
                    },
                    {
                        title: 'Updated By',
                        data: 'updated_by_name',
                        orderable: false,
                    },
                    {
                        title: "Action",
                        data: "action",
                        orderable: false,
                        searchable: false,
                    }
                ]
            });
        });
    </script>

    @include('layouts.admin.includes.change-status', ['table' => 'tours', 'column' => 'status'])
@endpush

