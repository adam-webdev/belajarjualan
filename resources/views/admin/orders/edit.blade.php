@extends('layouts.layoutmaster')

@section('title', 'Edit Order')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">Edit Order #{{ $order->order_number }}</h4>
        <div>
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Order
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="status" class="form-label">Order Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="status-help">
                            <strong>Note:</strong> Changing status to "cancelled" will return products to inventory.
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="tracking_number" class="form-label">Tracking Number</label>
                        <input type="text" class="form-control @error('tracking_number') is-invalid @enderror"
                               id="tracking_number" name="tracking_number"
                               value="{{ old('tracking_number', $order->tracking_number) }}">
                        @error('tracking_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="notes" class="form-label">Order Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Order Summary</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 40%">Order Number</th>
                                    <td>{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <th>Customer</th>
                                    <td>{{ $order->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Items</th>
                                    <td>{{ $order->details->sum('quantity') }}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>{{ number_format($order->total, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Status</th>
                                    <td>
                                        @if($order->payment)
                                            <span class="badge bg-{{ $order->payment->status === 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($order->payment->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-light-secondary">No Payment</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Order</button>
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection