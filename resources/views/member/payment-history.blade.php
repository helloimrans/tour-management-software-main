@extends('layouts.admin.master')
@section('title', 'Payment History')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">My Payment History</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Payment History</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header d-flex justify-content-between mb-3">
                                <h4>Payment History</h4>
                                <a href="{{ route('member.add-payment') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus"></i> Add New Payment
                                </a>
                            </div>

                            @if($payments && $payments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tour</th>
                                                <th>Amount</th>
                                                <th>Payment Method</th>
                                                <th>Transaction No</th>
                                                <th>Date</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($payments as $index => $payment)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $payment->tour->name }}</strong><br>
                                                    <small class="text-muted">{{ $payment->tour->destination }}</small>
                                                </td>
                                                <td><strong class="text-success">৳{{ number_format($payment->amount, 2) }}</strong></td>
                                                <td>
                                                    @php
                                                        $badges = [
                                                            'cash' => 'badge-success',
                                                            'bank' => 'badge-info',
                                                            'bkash' => 'badge-warning',
                                                            'nagad' => 'badge-primary',
                                                        ];
                                                        $class = $badges[$payment->payment_method] ?? 'badge-secondary';
                                                    @endphp
                                                    <span class="badge {{ $class }}">{{ ucfirst($payment->payment_method) }}</span>
                                                </td>
                                                <td>{{ $payment->transaction_number ?? '-' }}</td>
                                                <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                                <td>{{ $payment->notes ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-light">
                                                <th colspan="2" class="text-right">Total Paid:</th>
                                                <th colspan="5">৳{{ number_format($payments->sum('amount'), 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fa-solid fa-info-circle"></i> No payment records found.
                                    <a href="{{ route('member.add-payment') }}" class="alert-link">Add your first payment</a>.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

