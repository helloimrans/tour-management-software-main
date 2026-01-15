@extends('layouts.admin.master')
@section('title', 'Member Dashboard')

@push('css')
<style>
    .dashboard-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .dashboard-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    }

    .dashboard-card .info-box {
        margin-bottom: 0;
        padding: 20px;
    }

    .dashboard-card .info-box-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        border-radius: 10px;
    }

    .dashboard-card .info-box-content {
        padding-left: 15px;
    }

    .dashboard-card .info-box-text {
        font-size: 13px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .dashboard-card .info-box-number {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1.2;
        word-break: break-word;
    }

    .dashboard-card .info-box-number.long-text {
        font-size: 14px;
    }

    .current-tour-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-top: 20px;
    }

    .current-tour-card .card-header {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 15px 20px;
        border: none;
    }

    .current-tour-card .card-title {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }

    .current-tour-card .card-body {
        padding: 20px;
    }

    .current-tour-card h4 {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .current-tour-card .info-row {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .current-tour-card .info-row:last-child {
        border-bottom: none;
    }

    .current-tour-card .info-row i {
        width: 30px;
        color: #3498db;
        font-size: 16px;
    }

    .current-tour-card .info-row strong {
        color: #2c3e50;
        margin-right: 10px;
        min-width: 100px;
    }

    .content-header {
        margin-bottom: 20px;
    }

    .content-header h1 {
        font-size: 24px;
        font-weight: 700;
    }
</style>
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
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('member.current-tour') }}" class="text-decoration-none">
                        <div class="card dashboard-card">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fa-solid fa-map-location-dot"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Current Tour</span>
                                    <span class="info-box-number {{ $currentTour && strlen($currentTour->name) > 30 ? 'long-text' : '' }}">{{ $currentTour ? Str::limit($currentTour->name, 40) : 'None' }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('member.tour-history') }}" class="text-decoration-none">
                        <div class="card dashboard-card">
                            <div class="info-box mb-0">
                                <span class="info-box-icon bg-success elevation-1"><i class="fa-solid fa-history"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Tours</span>
                                    <span class="info-box-number">{{ $totalTours }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('member.payment-history') }}" class="text-decoration-none">
                        <div class="card dashboard-card">
                            <div class="info-box mb-0">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fa-solid fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Paid</span>
                                    <span class="info-box-number">৳{{ number_format($totalPaid, 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('member.tours') }}" class="text-decoration-none">
                        <div class="card dashboard-card">
                            <div class="info-box mb-0">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fa-solid fa-search"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Browse Tours</span>
                                    <span class="info-box-number">
                                        <span class="badge bg-warning text-dark">Browse</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            @if($currentTourMember && $currentTour)
            <div class="row">
                <div class="col-12">
                    <div class="card current-tour-card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle me-2"></i>Current Tour Details</h3>
                        </div>
                        <div class="card-body">
                            <h4>{{ $currentTour->name }}</h4>
                            <div class="info-row">
                                <i class="fas fa-map-marker-alt"></i>
                                <strong>Destination:</strong>
                                <span>{{ $currentTour->destination }}</span>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-calendar"></i>
                                <strong>Dates:</strong>
                                <span>{{ $currentTour->start_date->format('d M Y') }} - {{ $currentTour->end_date->format('d M Y') }}</span>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-check-circle"></i>
                                <strong>Status:</strong>
                                <span><span class="badge badge-{{ $currentTourMember->join_status == 'approved' ? 'success' : 'warning' }}">{{ ucfirst($currentTourMember->join_status) }}</span></span>
                            </div>
                            @if($currentTourMember->room_no || $currentTourMember->seat_no)
                            <div class="info-row">
                                <i class="fas fa-door-open"></i>
                                <strong>Accommodation:</strong>
                                <span>
                                    @if($currentTourMember->room_no)Room: {{ $currentTourMember->room_no }} @endif
                                    @if($currentTourMember->seat_no)Seat: {{ $currentTourMember->seat_no }} @endif
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

