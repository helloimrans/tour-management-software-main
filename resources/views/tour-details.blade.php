@extends('layouts.frontend.master')
@section('title', $tour->name)

@push('css')
<style>
    .tour-details-header {
        background: linear-gradient(135deg, rgba(52, 152, 219, 0.9), rgba(46, 204, 113, 0.9)),
                    url('{{ $tour->image_url }}') center/cover;
        color: white;
        padding: 100px 0 60px;
        margin-top: 0;
    }

    .tour-details-header .badge {
        font-size: 14px;
        padding: 8px 15px;
        margin-bottom: 15px;
    }

    .tour-details-content {
        padding: 40px 0;
    }

    .tour-info-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
    }

    .tour-info-card h5 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 20px;
        font-size: 20px;
    }

    .info-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item i {
        width: 30px;
        color: #3498db;
        font-size: 18px;
    }

    .info-item strong {
        color: #2c3e50;
        margin-left: 10px;
    }

    .schedule-item {
        background: #f8f9fa;
        border-left: 4px solid #3498db;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 8px;
    }

    .schedule-item h6 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .schedule-item p {
        color: #7f8c8d;
        margin: 0;
    }

    .price-box {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        color: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
    }

    .price-box .price {
        font-size: 36px;
        font-weight: 700;
        margin: 10px 0;
    }

    .price-box .price-label {
        font-size: 14px;
        opacity: 0.9;
    }
</style>
@endpush

@section('content')
<div class="tour-details-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge bg-{{ $tour->status == 'ongoing' ? 'success' : ($tour->status == 'upcoming' ? 'info' : 'secondary') }}">
                    {{ ucfirst($tour->status) }}
                </span>
                @if($tour->tour_members_count >= $tour->max_members)
                    <span class="badge bg-danger">Full</span>
                @elseif($tour->tour_members_count >= ($tour->max_members * 0.8))
                    <span class="badge bg-warning">Almost Full</span>
                @endif
                <h1 class="mb-3">{{ $tour->name }}</h1>
                <p class="lead mb-4">
                    <i class="fas fa-map-marker-alt"></i> {{ $tour->destination }}
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="price-box">
                    <div class="price-label">Price per person</div>
                    <div class="price">৳{{ number_format($tour->per_member_cost, 2) }}</div>
                    <div class="price-label">
                        <i class="fas fa-users"></i> {{ $tour->tour_members_count }}/{{ $tour->max_members }} members
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="tour-details-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="tour-info-card">
                    <h5><i class="fas fa-info-circle"></i> Tour Description</h5>
                    <p class="text-muted">{{ $tour->description ?? 'No description available.' }}</p>
                </div>

                @if($tour->schedules && $tour->schedules->count() > 0)
                <div class="tour-info-card">
                    <h5><i class="fas fa-calendar-alt"></i> Tour Schedule</h5>
                    @foreach($tour->schedules as $schedule)
                    <div class="schedule-item">
                        <h6>
                            <i class="fas fa-calendar-check"></i> 
                            {{ $schedule->schedule_date->format('d M Y') }}
                        </h6>
                        <p><strong>Title:</strong> {{ $schedule->title }}</p>
                        @if($schedule->details)
                        <p><strong>Details:</strong> {{ $schedule->details }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="tour-info-card">
                    <h5><i class="fas fa-image"></i> Tour Image</h5>
                    <img src="{{ $tour->image_url }}" alt="{{ $tour->name }}" class="img-fluid rounded shadow">
                </div>
            </div>

            <div class="col-md-4">
                <div class="tour-info-card">
                    <h5><i class="fas fa-calendar"></i> Tour Dates</h5>
                    <div class="info-item">
                        <i class="fas fa-calendar-check"></i>
                        <strong>Start Date:</strong>
                    </div>
                    <div class="mb-3 ms-4">{{ $tour->start_date->format('d M Y') }}</div>
                    <div class="info-item">
                        <i class="fas fa-calendar-times"></i>
                        <strong>End Date:</strong>
                    </div>
                    <div class="mb-3 ms-4">{{ $tour->end_date->format('d M Y') }}</div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <strong>Duration:</strong>
                    </div>
                    <div class="mb-3 ms-4">{{ $tour->start_date->diffInDays($tour->end_date) + 1 }} days</div>
                </div>

                <div class="tour-info-card">
                    <h5><i class="fas fa-users"></i> Availability</h5>
                    <div class="info-item">
                        <i class="fas fa-user-check"></i>
                        <strong>Joined:</strong> {{ $tour->tour_members_count }}
                    </div>
                    <div class="info-item">
                        <i class="fas fa-user-plus"></i>
                        <strong>Available:</strong> {{ $tour->max_members - $tour->tour_members_count }}
                    </div>
                    <div class="info-item">
                        <i class="fas fa-users"></i>
                        <strong>Maximum:</strong> {{ $tour->max_members }}
                    </div>
                </div>

                <div class="tour-info-card">
                    <h5><i class="fas fa-money-bill-wave"></i> Pricing</h5>
                    <div class="info-item">
                        <i class="fas fa-tag"></i>
                        <strong>Per Person:</strong> ৳{{ number_format($tour->per_member_cost, 2) }}
                    </div>
                    <div class="info-item">
                        <i class="fas fa-calculator"></i>
                        <strong>Total Cost:</strong> ৳{{ number_format($tour->total_cost, 2) }}
                    </div>
                </div>

                @if($tour->tour_members_count < $tour->max_members && in_array($tour->status, ['upcoming', 'ongoing']))
                <div class="d-grid gap-2 mt-3">
                    <a href="{{ route('member.show.register') }}" class="btn btn-join-now btn-sm">
                        <i class="fas fa-sign-in-alt"></i> Join This Tour
                    </a>
                </div>
                @else
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    @if($tour->tour_members_count >= $tour->max_members)
                        This tour is full.
                    @else
                        This tour is not available for joining.
                    @endif
                </div>
                @endif

                <div class="d-grid gap-2 mt-3">
                    <a href="{{ route('tours.listing') }}" class="btn btn-view-all btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Tours
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
