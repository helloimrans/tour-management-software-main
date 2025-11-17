@extends('layouts.admin.master')
@section('title', 'User List')

@push('css')
    <!-- You can add custom CSS here -->
@endpush

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header d-flex justify-content-between">
                                <h4>Admin Users  List</h4>
                                @permission('admin-user-create')
                                <a href="{{ route('admin.user.create') }}" class="btn btn-primary">
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
        var table;
        $(function() {
            table = $('.datatable').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                scrollX:true,
                ajax: "{{ route('admin.user.index') }}",
                columns: [
                    {
                        title: "SL#",
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        searchable: false,
                        orderable: false,
                        visible: true
                    },
                    {
                        title: 'Radio Station',
                        data: 'radio_station'
                    },
                    {
                        title: 'First Name',
                        data: 'first_name'
                    },
                    {
                        title: 'Last Name',
                        data: 'last_name'
                    },
                    {
                        title: 'Email',
                        data: 'email'
                    },

                    {
                        title: 'Phone',
                        data: 'phone'
                    },
                    {
                        title: 'Roles',
                        data: 'roles'
                    },
                    {
                        title: 'Profile Pic',
                        data: 'profile_pic'
                    },
                    {
                        title: 'Status',
                        data: 'status'
                    },
                    {
                        title: 'Created By',
                        data: 'created_by_name'
                    },
                    {
                        title: 'Updated By',
                        data: 'updated_by_name'
                    },
                    {
                        title: "Action",
                        data: "action",
                        orderable: false,
                        searchable: false,
                        visible: true
                    }
                ]
            });
        });
    </script>

    @include('layouts.admin.includes.change-status', ['table' => 'users', 'column' => 'status'])
@endpush
