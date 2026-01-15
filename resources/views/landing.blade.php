@extends('layouts.frontend.master')
@section('title', 'Home')

@push('css')
<style>
    .hero-section {
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                    url('{{ asset("frontend/video/tour-bg.jpg") }}') center/cover;
    }
</style>
@endpush

@section('content')
<section class="hero-section">
    <div class="hero-content">
        <h1>Explore the World with Us</h1>
        <p>Join amazing tours and create unforgettable memories</p>
        <div class="mt-4">
            <a href="{{ route('tours.listing') }}" class="btn btn-custom btn-primary-custom me-3">Browse Tours</a>
            <a href="{{ route('member.show.register') }}" class="btn btn-custom btn-secondary-custom">Join Now</a>
        </div>
    </div>
</section>

<section class="py-5" style="background: #f8f9fa;">
    <div class="container">
        <div class="section-title">
            <h2>Why Choose Us?</h2>
            <p class="text-muted">Best tour management experience</p>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="feature-box">
                    <i class="fas fa-map-marked-alt"></i>
                    <h4>Amazing Destinations</h4>
                    <p class="text-muted">Explore beautiful and exotic locations</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-box">
                    <i class="fas fa-users"></i>
                    <h4>Expert Guides</h4>
                    <p class="text-muted">Professional and friendly tour guides</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-box">
                    <i class="fas fa-shield-alt"></i>
                    <h4>Safe & Secure</h4>
                    <p class="text-muted">Your safety is our top priority</p>
                </div>
            </div>
        </div>
    </div>
</section>

@if($featuredTour)
<section class="py-4 featured-tour-section">
    <div class="container">
        <div class="section-title">
            <h2>Featured Tour</h2>
            <p class="text-muted">Don't miss this amazing opportunity</p>
        </div>
        <div class="card featured-tour-card shadow-sm">
            <div class="row g-0">
                <div class="col-md-5">
                    <div class="position-relative">
                        <img src="{{ $featuredTour->image_url }}" alt="{{ $featuredTour->name }}" class="img-fluid w-100 h-100" style="object-fit: cover; min-height: 300px;">
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-{{ $featuredTour->status == 'ongoing' ? 'success' : 'info' }}">{{ ucfirst($featuredTour->status) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-2">{{ $featuredTour->name }}</h4>
                        <p class="text-muted mb-3">
                            <i class="fas fa-map-marker-alt text-danger"></i> {{ $featuredTour->destination }}
                        </p>
                        <p class="card-text small text-muted mb-3" style="line-height: 1.6;">
                            {{ Str::limit($featuredTour->description, 200) }}
                        </p>
                        <div class="row g-3 mb-3">
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Date</small>
                                        <strong class="small">{{ $featuredTour->start_date->format('d M') }} - {{ $featuredTour->end_date->format('d M Y') }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-money-bill-wave text-success me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Price</small>
                                        <strong class="text-success">৳{{ number_format($featuredTour->per_member_cost, 0) }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users text-info me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Members</small>
                                        <strong>{{ $featuredTour->tour_members_count }}/{{ $featuredTour->max_members }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('tours.details', $featuredTour->id) }}" class="btn btn-view-details btn-sm">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            @if($featuredTour->tour_members_count < $featuredTour->max_members && in_array($featuredTour->status, ['upcoming', 'ongoing']))
                            <a href="{{ route('member.show.register') }}" class="btn btn-join-now btn-sm">
                                <i class="fas fa-sign-in-alt"></i> Join Now
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<section class="py-5" style="background: #f8f9fa;">
    <div class="container">
        <div class="section-title">
            <h2>All Available Tours</h2>
            <p class="text-muted">Explore our exciting tour packages</p>
        </div>

        <div class="filter-section">
            <form method="GET" action="{{ route('tours.listing') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Search Tours</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="Search by name, destination..."
                               value="{{ request('search') ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="upcoming" {{ request('sort') == 'upcoming' ? 'selected' : '' }}>Upcoming First</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
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

        @if(isset($allTours) && $allTours && $allTours->count() > 0)
            <div class="mb-4">
                <h5 class="text-muted">Found {{ $allTours->count() }} tour(s)</h5>
            </div>

            <div class="row">
                @foreach($allTours as $tour)
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
                            <p class="text-muted small mb-3">{{ Str::limit($tour->description, 100) }}</p>
                            @endif

                            <div class="mt-auto">
                                <hr>
                                <div class="d-flex justify-content-between align-items-center mb-3">
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

                                <div class="d-flex gap-2">
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
            <div class="text-center mt-4">
                <a href="{{ route('tours.listing') }}" class="btn btn-view-all">View All Tours</a>
            </div>
        @else
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-info-circle fa-3x mb-3"></i>
                <h4>No tours found</h4>
                <p>Try adjusting your search filters or check back later for new tours.</p>
                <a href="{{ route('tours.listing') }}" class="btn btn-view-all mt-3">View All Tours</a>
            </div>
        @endif
    </div>
</section>

@if($upcomingTours && $upcomingTours->count() > 0)
<section class="py-5" style="background: #f8f9fa;">
    <div class="container">
        <div class="section-title">
            <h2>Upcoming Tours</h2>
            <p class="text-muted">Explore our exciting tour packages</p>
        </div>
        <div class="row">
            @foreach($upcomingTours as $tour)
            <div class="col-md-4">
                <div class="card tour-card">
                    <img src="{{ $tour->image_url }}" class="card-img-top" alt="{{ $tour->name }}">
                    <div class="card-body">
                        <span class="badge bg-{{ $tour->status == 'ongoing' ? 'success' : 'info' }} mb-2">{{ ucfirst($tour->status) }}</span>
                        <h5 class="card-title">{{ $tour->name }}</h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt text-danger"></i> {{ $tour->destination }}
                        </p>
                        <p class="text-muted mb-3">
                            <i class="fas fa-calendar"></i> {{ $tour->start_date->format('d M Y') }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-success fw-bold fs-5">৳{{ number_format($tour->per_member_cost, 2) }}</span>
                            <span class="text-muted"><i class="fas fa-users"></i> {{ $tour->tour_members_count }}/{{ $tour->max_members }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
                <a href="{{ route('tours.listing') }}" class="btn btn-view-all">View All Tours</a>
        </div>
    </div>
</section>
@endif
@endsection
