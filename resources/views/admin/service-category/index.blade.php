@extends('layouts.admin.master')
@section('title', 'Service Category')
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
                                <h4>Service Category list</h4>
                                <a href="{{route('serviceCategory.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add New</a>
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
                responsive: false,
                serverSide: true,
                ajax: "{{ route('serviceCategory.index') }}",
                columns: [{
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
                        title: 'Name',
                        data: 'name'
                    },
                    {
                        title: 'Radio Station',
                        data: 'radio_station_name'
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
                        title: 'Active',
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

@include('layouts.admin.includes.change-status', ['table'=> 'service_categories'])
@endpush
