@extends('layouts.admin.master')
@section('title', 'Travel Agency Dashboard')

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
                        <li class="breadcrumb-item"><a href="{{ route('travel.agency.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fa-solid fa-map-location-dot"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Tours</span>
                            <span class="info-box-number">{{ $stats['total_tours'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fa-solid fa-map-location-dot"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Active Tours</span>
                            <span class="info-box-number">{{ $stats['active_tours'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fa-solid fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Participants</span>
                            <span class="info-box-number">{{ $stats['total_participants'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

