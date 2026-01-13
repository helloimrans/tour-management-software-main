@extends('layouts.admin.master')
@section('title', 'Add Payment')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Payment</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Add Payment</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header d-flex justify-content-between">
                                <h4>Add Payment / Contribution</h4>
                                <a href="{{ route('member.payment-history') }}" class="btn btn-secondary">
                                    <i class="fa-solid fa-history"></i> Payment History
                                </a>
                            </div>

                            <form id="paymentForm" action="{{ route('member.payment.store') }}" method="post">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="tour_id" class="form-label">Select Tour <span class="text-danger">*</span></label>
                                            <select name="tour_id" class="form-control" id="tour_id" required>
                                                <option value="">Select Tour</option>
                                                @foreach($tours as $tourMember)
                                                    <option value="{{ $tourMember->tour->id }}" {{ old('tour_id') == $tourMember->tour->id ? 'selected' : '' }}>
                                                        {{ $tourMember->tour->name }} - {{ $tourMember->tour->destination }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('tour_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount" class="form-label">Amount (৳) <span class="text-danger">*</span></label>
                                            <input type="number" name="amount" class="form-control" id="amount" step="0.01" min="0"
                                                   placeholder="Enter Amount" value="{{ old('amount') }}" required>
                                            @error('amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                            <input type="date" name="payment_date" class="form-control" id="payment_date"
                                                   value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                            @error('payment_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                            <select name="payment_method" class="form-control" id="payment_method" required>
                                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                                <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                                                <option value="bkash" {{ old('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                                                <option value="nagad" {{ old('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                            </select>
                                            @error('payment_method')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="transaction_number" class="form-label">Transaction Number</label>
                                            <input type="text" name="transaction_number" class="form-control" id="transaction_number"
                                                   placeholder="Enter Transaction Number" value="{{ old('transaction_number') }}">
                                            @error('transaction_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notes" class="form-label">Notes</label>
                                            <textarea name="notes" class="form-control" id="notes" rows="3"
                                                      placeholder="Enter any notes about this payment">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-floppy-disk"></i> Submit Payment
                                        </button>
                                        <a href="{{ route('member.dashboard') }}" class="btn btn-secondary">
                                            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

