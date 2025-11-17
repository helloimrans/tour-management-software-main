@extends('layouts.admin.master')
@section('title', 'Ad Slider')

@push('css')
@endpush

@section('content')
    <div class="content">
        <div class="container-fluid">
            @permission('ad-sliders-filter')
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header">
                                <h5 class="m-0">Filter</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="radio_station_id" class="custom-select" id="radio_station_id">
                                        <option value="">Select Radio Station</option>
                                        @foreach ($radioStations as $radioStation)
                                            <option value="{{ $radioStation->id }}">{{ $radioStation->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endpermission

            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header d-flex justify-content-between">
                                <h4>Ad Slider List</h4>
                                <a href="{{ route('adSlider.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus-circle"></i> Add New
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table datatable custom-table dt-responsive nowrap"></table>
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
                ajax: {
                    url: "{{ route('adSlider.index') }}",
                    data: function(d) {
                        d.radio_station_id = $('#radio_station_id').val();
                    }
                },
                columns: [
                    {
                        title: "SL#",
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        searchable: false,
                        orderable: false
                    },
                    {
                        title: 'Slider Thumbnail',
                        data: 'thumbnail_image',
                       
                    },
                    {
                        title: 'Radio Station',
                        data: 'radio_station_name'
                    },
                    {
                        title: 'Title',
                        data: 'title'
                    },
                    {
                        title: 'Description',
                        data: 'description'
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
                        
                    }
                ]
            });

            $('#radio_station_id').change(function() {
                table.draw();
            });

        });
    </script>

    @include('layouts.admin.includes.change-status', ['table' => 'ad_sliders'])
@endpush
