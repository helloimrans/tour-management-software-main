@extends('layouts.admin.master')
@section('title', 'Member Dashboard')

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
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Home</a></li>
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
                    <a href="{{ route('member.current-tour') }}" class="text-decoration-none text-dark">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fa-solid fa-map-location-dot"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Current Tour</span>
                                <span class="info-box-number">{{ $currentTour ? $currentTour->name : 'None' }}</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <a href="{{ route('member.tour-history') }}" class="text-decoration-none text-dark">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fa-solid fa-history"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Tours</span>
                                <span class="info-box-number">{{ $totalTours }}</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <a href="{{ route('member.tours') }}" class="text-decoration-none text-dark">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fa-solid fa-search"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Browse Tours</span>
                                <span class="info-box-number">Join Now</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

