@extends('layouts.admin.master')
@section('title', 'Radio Stations')

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
                                <h4>Radio Station List</h4>
                                @permission('radio-stations-create')
                                <a href="{{route('radio.stations.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add New</a>
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
                responsive: false,
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('radio.stations.index') }}",
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
                        title: 'Descripiton',
                        data: 'description'
                    },
                    {
                        title: 'Logo',
                        data: 'logo'
                    },
                    {
                        title: 'Is Under Maintenance',
                        data: 'is_under_maintenence'
                    },
                    {
                        title: 'Under Maintenance Image',
                        data: 'under_maintenence_image'
                    },
                    {
                        title: 'Live Radio URL',
                        data: 'live_radio_url'
                    },
                    {
                        title: 'Live YouTube URL',
                        data: 'live_youtube_url'
                    },
                    {
                        title: 'Thumbnail Image',
                        data: 'thumbnail_image'
                    },
                    {
                        title: 'Phone Number',
                        data: 'phone_number'
                    },
                    {
                        title: 'Whatsapp Number',
                        data: 'whatsapp_number'
                    },
                    {
                        title: 'Is Active',
                        data: 'is_active'
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

@include('layouts.admin.includes.change-status', ['table'=> 'radio_stations'])
@endpush
