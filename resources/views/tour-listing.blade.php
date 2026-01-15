@extends('layouts.frontend.master')
@section('title', 'All Tours')

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="mb-0">Explore Our Tours</h1>
        <p class="lead mb-0">Find your perfect adventure</p>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="filter-section">
            <form method="GET" action="{{ route('tours.listing') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Search Tours</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="Search by name, destination..."
                               value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="upcoming" {{ $status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="ongoing" {{ $status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="latest" {{ $sortBy == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="upcoming" {{ $sortBy == 'upcoming' ? 'selected' : '' }}>Upcoming First</option>
                            <option value="price_low" {{ $sortBy == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ $sortBy == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-filter w-100">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if($tours && $tours->count() > 0)
            <div class="mb-4">
                <h5 class="text-muted">Found {{ $tours->total() }} tour(s)</h5>
            </div>
            <div class="row">
                @foreach($tours as $tour)
                <div class="col-md-4 mb-4">
                    <div class="card tour-card">
                        <img src="{{ $tour->image_url }}" class="card-img-top" alt="{{ $tour->name }}">
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-{{ $tour->status == 'ongoing' ? 'success' : ($tour->status == 'upcoming' ? 'info' : 'secondary') }}">
                                    {{ ucfirst($tour->status) }}
                                </span>
                                @if($tour->tour_members_count >= $tour->max_members)
                                    <span class="badge bg-danger">Full</span>
                                @elseif($tour->tour_members_count >= ($tour->max_members * 0.8))
                                    <span class="badge bg-warning">Almost Full</span>
                                @endif
                            </div>

                            <h5 class="card-title">{{ $tour->name }}</h5>

                            <p class="text-muted mb-2">
                                <i class="fas fa-map-marker-alt text-danger"></i> {{ $tour->destination }}
                            </p>

                            <p class="text-muted mb-2">
                                <i class="fas fa-calendar"></i>
                                {{ $tour->start_date->format('d M Y') }} - {{ $tour->end_date->format('d M Y') }}
                            </p>

                            @if($tour->description)
                            <p class="text-muted small">{{ Str::limit($tour->description, 100) }}</p>
                            @endif

                            <div class="mt-auto">
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block">Price per person</small>
                                        <span class="text-success fw-bold fs-5">৳{{ number_format($tour->per_member_cost, 2) }}</span>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block">Availability</small>
                                        <span class="fw-bold">
                                            <i class="fas fa-users text-info"></i>
                                            {{ $tour->tour_members_count }}/{{ $tour->max_members }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-3 d-flex gap-2">
                                    <a href="{{ route('tours.details', $tour->id) }}" class="btn btn-view-details btn-sm">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    @if($tour->tour_members_count < $tour->max_members && in_array($tour->status, ['upcoming', 'ongoing']))
                                        <a href="{{ route('member.show.register') }}" class="btn btn-join-now btn-sm">
                                            <i class="fas fa-sign-in-alt"></i> Join Now
                                        </a>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            @if($tour->tour_members_count >= $tour->max_members)
                                                <i class="fas fa-times-circle"></i> Full
                                            @else
                                                <i class="fas fa-ban"></i> Not Available
                                            @endif
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $tours->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-info-circle fa-3x mb-3"></i>
                <h4>No tours found</h4>
                <p>Try adjusting your search filters or check back later for new tours.</p>
                <a href="{{ route('tours.listing') }}" class="btn btn-view-all mt-3">Reset Filters</a>
            </div>
        @endif
    </div>
</section>
@endsection
