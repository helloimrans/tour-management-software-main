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
                    <a href="{{ route('tour.index') }}" class="text-decoration-none text-dark">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary elevation-1"><i class="fa-solid fa-map-location-dot"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Tours</span>
                                <span class="info-box-number">{{ $data['total_tours'] }}</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('tour.index') }}" class="text-decoration-none text-dark">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fa-solid fa-plane-departure"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Active Tours</span>
                                <span class="info-box-number">{{ $data['active_tours'] }}</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('member-management.index') }}" class="text-decoration-none text-dark">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info elevation-1"><i class="fa-solid fa-user-group"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Participants</span>
                                <span class="info-box-number">{{ $data['total_participants'] }}</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('expense.index') }}" class="text-decoration-none text-dark">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fa-solid fa-money-bill-wave"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Expenses</span>
                                <span class="info-box-number">৳{{ number_format($data['total_expenses'], 2) }}</span>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    </div>
@endsection
