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
                @forelse($tours as $tour)
                    <div class="col-md-4 mb-4">
                        <div class="card dashboard-custom-card">
                            <img src="{{ $tour->image_url }}" class="card-img-top" alt="{{ $tour->name }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $tour->name }}</h5>
                                <p class="card-text">{{ Str::limit($tour->description, 100) }}</p>
                                <p class="text-muted">
                                    <small>By: {{ $tour->createdBy->first_name ?? 'N/A' }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle"></i> You haven't joined any tours yet.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

