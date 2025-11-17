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
            <div class="row">
                <div class="col">
                    @if($tour)
                        <div class="card dashboard-custom-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="{{ $tour->image_url }}" class="img-fluid" alt="{{ $tour->name }}" style="border-radius: 10px;">
                                    </div>
                                    <div class="col-md-8">
                                        <h3>{{ $tour->name }}</h3>
                                        <p class="text-muted">{{ $tour->description }}</p>
                                        <p><strong>Status:</strong>
                                            <span class="badge {{ $tour->status ? 'badge-success' : 'badge-danger' }}">
                                                {{ $tour->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </p>
                                        <p><strong>Created By:</strong> {{ $tour->createdBy->first_name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle"></i> You are not currently in any tour.
                            <a href="{{ route('member.tours') }}" class="alert-link">Browse available tours</a> to join one.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

