@extends('layouts.admin.master')
@section('title', 'Point Withdraw')

@push('css')
@endpush

@section('content')
    <div class="content">
        <div class="container-fluid">
{{--            @permission('music-filter')--}}
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header">
                                <h5 class="m-0">Filter</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="status" class="custom-select" id="status">
                                        <option value="" selected>Select Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="reject">Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
{{--            @endpermission--}}
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header d-flex justify-content-between">
                                <h4>Point Withdraw Request List</h4>
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
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('pointWithdrawRequest') }}",
                    data: function(d) {
                        d.status = $('#status').val();
                    }
                },
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
                        title: 'User Name',
                        data: 'user_name',
                    },

                    {
                        title: 'Point',
                        data: 'points'
                    },

                    {
                        title: 'Status',
                        data: 'status'
                    },
                    {
                        title: 'Approved By',
                        data: 'approved_by_name'
                    },
                    {
                        title: 'Approval Date',
                        data: 'approval_date'
                    },
                    {
                        title: "Action",
                        data: "action",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#status').change(function() {
                table.draw();
            });
        });
    </script>
@endpush
