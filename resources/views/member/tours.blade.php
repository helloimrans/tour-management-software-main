@extends('layouts.admin.master')
@section('title', 'Available Tours')

@push('css')
<style>
    .tour-card {
        height: 100%;
        transition: transform 0.3s;
    }
    .tour-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .tour-card img {
        height: 220px;
        object-fit: cover;
    }
</style>
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Available Tours</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Tours</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                @forelse($tours as $tour)
                    <div class="col-md-4 mb-4">
                        <div class="card tour-card dashboard-custom-card">
                            <img src="{{ $tour->image_url }}" class="card-img-top" alt="{{ $tour->name }}">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <span class="badge badge-{{ $tour->status == 'ongoing' ? 'success' : 'info' }}">
                                        {{ ucfirst($tour->status) }}
                                    </span>
                                    @if($tour->tour_members_count >= $tour->max_members)
                                        <span class="badge badge-danger">Full</span>
                                    @endif
                                </div>
                                
                                <h5 class="card-title">{{ $tour->name }}</h5>
                                
                                <p class="text-muted mb-2">
                                    <i class="fas fa-map-marker-alt text-danger"></i> <strong>{{ $tour->destination }}</strong>
                                </p>
                                
                                <p class="text-muted mb-2">
                                    <i class="fas fa-calendar"></i> {{ $tour->start_date->format('d M Y') }} - {{ $tour->end_date->format('d M Y') }}
                                </p>
                                
                                @if($tour->description)
                                <p class="card-text text-muted small">{{ Str::limit($tour->description, 100) }}</p>
                                @endif
                                
                                <div class="mt-auto">
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <small class="text-muted d-block">Price per person</small>
                                            <h5 class="text-success mb-0">৳{{ number_format($tour->per_member_cost, 2) }}</h5>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted d-block">Availability</small>
                                            <strong>
                                                <i class="fas fa-users text-info"></i> 
                                                {{ $tour->tour_members_count }}/{{ $tour->max_members }}
                                            </strong>
                                        </div>
                                    </div>
                                    
                                    @if($tour->tour_members_count < $tour->max_members)
                                        <form action="{{ route('member.join-tour', $tour->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fa-solid fa-plus"></i> Join This Tour
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-block" disabled>
                                            <i class="fa-solid fa-times-circle"></i> Tour is Full
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fa-solid fa-info-circle fa-2x mb-3"></i>
                            <h4>No tours available at the moment</h4>
                            <p>Please check back later for upcoming tours.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
