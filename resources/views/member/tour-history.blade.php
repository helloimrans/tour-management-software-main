@extends('layouts.admin.master')
@section('title', 'My Tour History')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">My Tour History</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Tour History</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if($tourMembers && $tourMembers->count() > 0)
                        @foreach($tourMembers as $tourMember)
                        <div class="card dashboard-custom-card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img src="{{ $tourMember->tour->image_url }}" class="img-fluid" alt="{{ $tourMember->tour->name }}" style="border-radius: 10px;">
                                    </div>
                                    <div class="col-md-6">
                                        <h4>{{ $tourMember->tour->name }}</h4>
                                        <p><strong>Destination:</strong> {{ $tourMember->tour->destination }}</p>
                                        <p><strong>Dates:</strong> {{ $tourMember->tour->start_date->format('d M Y') }} - {{ $tourMember->tour->end_date->format('d M Y') }}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge badge-{{ $tourMember->join_status == 'approved' ? 'success' : ($tourMember->join_status == 'completed' ? 'info' : ($tourMember->join_status == 'cancelled' ? 'danger' : 'warning')) }}">
                                                {{ ucfirst($tourMember->join_status) }}
                                            </span>
                                        </p>
                                        <p><strong>Joined On:</strong> {{ $tourMember->joined_at->format('d M Y') }}</p>
                                        @if($tourMember->room_no || $tourMember->seat_no)
                                        <p>
                                            @if($tourMember->room_no)<strong>Room:</strong> {{ $tourMember->room_no }} @endif
                                            @if($tourMember->seat_no)| <strong>Seat:</strong> {{ $tourMember->seat_no }} @endif
                                        </p>
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="mb-2">
                                            <small class="text-muted">Cost Per Member</small>
                                            <h4 class="text-primary">৳{{ number_format($tourMember->tour->per_member_cost, 2) }}</h4>
                                        </div>
                                        <span class="badge badge-{{ $tourMember->tour->status == 'ongoing' ? 'success' : ($tourMember->tour->status == 'upcoming' ? 'info' : ($tourMember->tour->status == 'completed' ? 'secondary' : 'danger')) }}">
                                            {{ ucfirst($tourMember->tour->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle"></i> You haven't joined any tours yet.
                            <a href="{{ route('member.tours') }}" class="alert-link">Browse available tours</a> to join one.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
