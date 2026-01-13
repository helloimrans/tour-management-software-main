@extends('layouts.admin.master')
@section('title', 'My Current Tour')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">My Current Tour</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Current Tour</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            @if($tour && $tourMember)
                <div class="row">
                    <div class="col-md-8">
                        <!-- Tour Details Card -->
                        <div class="card dashboard-custom-card">
                            <div class="card-header">
                                <h3 class="card-title">Tour Details</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="{{ $tour->image_url }}" class="img-fluid" alt="{{ $tour->name }}" style="border-radius: 10px;">
                                    </div>
                                    <div class="col-md-8">
                                        <h3>{{ $tour->name }}</h3>
                                        <p><strong>Destination:</strong> {{ $tour->destination }}</p>
                                        <p><strong>Dates:</strong> {{ $tour->start_date->format('d M Y') }} - {{ $tour->end_date->format('d M Y') }}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge badge-{{ $tour->status == 'ongoing' ? 'success' : ($tour->status == 'upcoming' ? 'info' : 'secondary') }}">
                                                {{ ucfirst($tour->status) }}
                                            </span>
                                        </p>
                                        <p><strong>Cost Per Member:</strong> ৳{{ number_format($tour->per_member_cost, 2) }}</p>
                                        @if($tour->description)
                                        <p class="text-muted">{{ $tour->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- My Assignment Card -->
                        <div class="card dashboard-custom-card">
                            <div class="card-header">
                                <h3 class="card-title">My Assignment</h3>
                            </div>
                            <div class="card-body">
                                <p><strong>Membership Status:</strong> 
                                    <span class="badge badge-{{ $tourMember->join_status == 'approved' ? 'success' : 'warning' }}">
                                        {{ ucfirst($tourMember->join_status) }}
                                    </span>
                                </p>
                                <p><strong>Joined On:</strong> {{ $tourMember->joined_at->format('d M Y') }}</p>
                                @if($tourMember->room_no)
                                <p><strong>Room No:</strong> {{ $tourMember->room_no }}</p>
                                @endif
                                @if($tourMember->seat_no)
                                <p><strong>Seat No:</strong> {{ $tourMember->seat_no }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Tour Schedule Card -->
                        @if($tour->schedules && $tour->schedules->count() > 0)
                        <div class="card dashboard-custom-card">
                            <div class="card-header">
                                <h3 class="card-title">Tour Schedule</h3>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    @foreach($tour->schedules as $schedule)
                                    <div class="mb-3">
                                        <strong>{{ $schedule->schedule_date->format('d M Y') }} - {{ $schedule->title }}</strong>
                                        <p class="text-muted mb-0">{{ $schedule->details }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Other Members Card -->
                        @if($tour->tourMembers && $tour->tourMembers->count() > 0)
                        <div class="card dashboard-custom-card">
                            <div class="card-header">
                                <h3 class="card-title">Tour Members ({{ $tour->tourMembers->count() }})</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($tour->tourMembers as $member)
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <i class="fa-solid fa-user-circle fa-2x text-primary"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $member->user->first_name }} {{ $member->user->last_name }}</strong>
                                                @if($member->room_no || $member->seat_no)
                                                <br>
                                                <small class="text-muted">
                                                    @if($member->room_no)Room: {{ $member->room_no }} @endif
                                                    @if($member->seat_no)| Seat: {{ $member->seat_no }} @endif
                                                </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Payment Summary Sidebar -->
                    <div class="col-md-4">
                        <div class="card dashboard-custom-card">
                            <div class="card-header bg-primary">
                                <h3 class="card-title text-white">Payment Summary</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Total Cost:</strong>
                                    <h4 class="text-primary">৳{{ number_format($paymentSummary['total_cost'], 2) }}</h4>
                                </div>
                                <div class="mb-3">
                                    <strong>Total Paid:</strong>
                                    <h4 class="text-success">৳{{ number_format($paymentSummary['total_paid'], 2) }}</h4>
                                </div>
                                <div class="mb-3">
                                    <strong>Remaining:</strong>
                                    <h4 class="text-{{ $paymentSummary['remaining'] > 0 ? 'danger' : 'success' }}">
                                        ৳{{ number_format($paymentSummary['remaining'], 2) }}
                                    </h4>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <a href="{{ route('member.add-payment') }}" class="btn btn-primary btn-block">
                                        <i class="fa-solid fa-plus"></i> Add Payment
                                    </a>
                                    <a href="{{ route('member.payment-history') }}" class="btn btn-outline-secondary btn-block mt-2">
                                        <i class="fa-solid fa-history"></i> Payment History
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle"></i> You are not currently in any tour.
                            <a href="{{ route('member.tours') }}" class="alert-link">Browse available tours</a> to join one.
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
