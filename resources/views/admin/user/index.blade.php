@extends('layouts.admin.master')
@section('title', 'General User List')

@push('css')

@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">General Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">General Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header d-flex justify-content-between">
                                <h4>General User List</h4>
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
                ajax: "{{ route('general.user.index') }}",
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
                        title: 'Profile Pic',
                        data: 'profile_pic'
                    },
                    {
                        title: 'Own coupon Code',
                        data: 'own_coupon_code'
                    },
                    {
                        title: 'Used Coupon Code',
                        data: 'used_coupon_code'
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
