@extends('layouts.admin.master')
@section('title', 'Live Comments')

@push('css')
@endpush

@section('content')
    <div class="content">
        <div class="container-fluid">
            @permission('live-comments-filter')
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header">
                                <h5 class="m-0">Filter</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="radio_station_id" class="custom-select" id="radio_station_id">
                                        <option value="">Select Radio Station</option>
                                        @foreach ($radioStations as $radioStation)
                                            <option value="{{ $radioStation->id }}">{{ $radioStation->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" id="date" class="form-control" placeholder="Start Date">
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
                                <h4>Live Comments</h4>
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
                ajax: {
                    url: "{{ route('live.comments') }}",
                    data: function(d) {
                        d.radio_station_id = $('#radio_station_id')
                            .val();
                        d.date = $('#date').val();
                    }
                },
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
                        title: 'Date',
                        data: 'date'
                    },
                    {
                        title: 'Radio Station',
                        data: 'radio_station'
                    },
                    {
                        title: 'User Name',
                        data: 'user_name'
                    },
                    {
                        title: 'Comment',
                        data: 'comment'
                    },
                    {
                        title: 'Is Active',
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

            $('#radio_station_id, #date').change(function() {
                table.draw();
            });
        });
    </script>

    @include('layouts.admin.includes.change-status', ['table' => 'live_radio_comments'])
@endpush
