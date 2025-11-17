@extends('layouts.admin.master')
@section('title', 'Live Comments')

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
                                <h4>Live Comment</h4>
                                <a href="{{ route('user.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus-circle"></i> reply to your comment
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table datatable custom-table dt-responsive nowrap">
                                    <!-- DataTables will automatically populate this table -->
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
                ajax: "{{ route('live-comments.index') }}",
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
                        title: 'Radio Station Name',
                        data: 'radio_station_id'
                    },
                    {
                        title: 'User ID',
                        data: 'user_id'
                    },
                    {
                        title: 'Live Radio Url',
                        data: 'live_radio_url'
                    },
                    {
                        title: 'Comment',
                        data: 'comment'
                    },
                    {
                        title: 'Date',
                        data: 'date'
                    },
                    {
                        title: 'Status',
                        data: 'is_active'
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

    @include('layouts.admin.includes.change-status', ['table' => 'live_radio_comments'])
@endpush
