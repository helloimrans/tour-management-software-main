@extends('layouts.admin.master')
@section('title', 'Show Request')

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
                                <h4>Create Radio Station</h4>
                                <a href="{{ route('pointWithdrawRequest') }}" class="btn btn-primary"><i
                                        class="fa fa-arrow-circle-left"></i> Back</a>
                            </div>

                                <div class="row">
                                    <div class="col-md-6 d-flex justify-content-between px-4 py-2">
                                        <div>Profile Photo</div>
                                        <div><img src="{{ $data->thumbnail_image ? Storage::url($data->thumbnail_image) : asset('defaults/noimage/no_img.jpg') }}" height="80" width="80" alt=""></div>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-between px-4 py-2">
                                        <div>Full Name</div>
                                        <div>{{$data->user->first_name. ' '. $data->user->last_name ?? ''}}</div>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-between px-4 py-2">
                                        <div>User Email</div>
                                        <div>{{$data->user->email ?? 'N/A'}}</div>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-between px-4 py-2">
                                        <div>User Phone</div>
                                        <div>{{$data->user->phone ?? 'N/A'}}</div>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-between px-4 py-2">
                                        <div>Request Withdraw Point</div>
                                        <div>{{$data->points ?? '0'}}</div>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-between px-4 py-2">
                                        <div>Request Status</div>
                                        <div>{{$data->status}}</div>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-between px-4 py-2">
                                        <div>Request Time</div>
                                        <div>{{$data->created_at ?? 'N/A'}}</div>
                                    </div>
                                    @if($data->status != 'pending')
                                        <div class="col-md-6 d-flex justify-content-between px-4 py-2">
                                            <div>Full Name</div>
                                            <div>{{$data->approvedBy->first_name. ' '. $data->approvedBy->last_name ?? ''}}</div>
                                        </div>
                                    @endif
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@push('js')
@endpush
