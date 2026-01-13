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
                                <h4>Members List</h4>
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

<script>
$(document).on('click', '.edit-role-btn', function() {
    let userId = $(this).data('id');
    let userName = $(this).data('name');
    let userRoles = $(this).data('roles');
    
    Swal.fire({
        title: 'Assign Role to ' + userName,
        html: `
            <form id="roleAssignForm">
                <div class="form-group text-left">
                    <label>Select Roles:</label>
                    <select name="roles[]" class="form-control select2" multiple required>
                        @foreach(\App\Models\Role::all() as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Assign',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const selectedRoles = $('#roleAssignForm select[name="roles[]"]').val();
            if (!selectedRoles || selectedRoles.length === 0) {
                Swal.showValidationMessage('Please select at least one role');
                return false;
            }
            return selectedRoles;
        },
        didOpen: () => {
            $('.select2').select2({
                dropdownParent: $('.swal2-popup')
            });
            $('.select2').val(userRoles).trigger('change');
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/dashboard/general-users/' + userId + '/assign-role',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    roles: result.value
                },
                success: function(response) {
                    toastr.success(response.message || 'Role assigned successfully');
                    $('.datatable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    toastr.error('Failed to assign role');
                }
            });
        }
    });
});
</script>
@endpush
