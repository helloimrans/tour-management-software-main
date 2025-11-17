@extends('layouts.admin.master')
@section('title', 'Dashboard')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('general.user.index') }}" class="text-decoration-none text-dark">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fa fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Members</span>
                                <span class="info-box-number">{{ $data['total_members'] }}</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('radio.stations.index') }}" class="text-decoration-none text-dark">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="bx bx-radio"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Radio Station</span>
                                <span class="info-box-number">{{ $data['radio_stations'] }}</span>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    </div>
@endsection
