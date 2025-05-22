@extends('layouts.frontend.master')

@section('title', 'Order Details - Apriori Shop')

@section('content')
<div class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.profile') }}">Profile</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.profile.orders') }}">My Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order Details</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Order Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Order Number:</strong></p>
                            <p class="mb-1"><strong>Order Date:</strong></p>
                            <p class="mb-1"><strong>Status:</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1">#{{ $order->order_number }}</p>
                            <p class="mb-1">{{ $order->created_at->format('d M Y H:i') }}</p>
                            <p class="mb-1">
                                <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'completed' ? 'success' : 'secondary')) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <h6 class="mb-3">Order Items</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->details as $detail)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($detail->productCombination->product->images->count() > 0)
                                                <img src="{{ asset('storage/' . $detail->productCombination->product->images->first()->image_path) }}"
                                                     class="rounded me-3" width="50" height="50"
                                                     alt="{{ $detail->productCombination->product->name }}">
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $detail->productCombination->product->name }}</h6>
                                                @if($detail->productCombination->optionValues->count() > 0)
                                                    <small class="text-muted">
                                                        {{ $detail->productCombination->getOptionsTextAttribute() }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Shipping Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Recipient Name:</strong></p>
                            <p class="mb-1"><strong>Phone:</strong></p>
                            <p class="mb-1"><strong>Address:</strong></p>
                            <p class="mb-1"><strong>Shipping Method:</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1">{{ $order->address->recipient_name }}</p>
                            <p class="mb-1">{{ $order->address->phone }}</p>
                            <p class="mb-1">
                                {{ $order->address->address_detail }}<br>
                                {{ $order->address->district }}, {{ $order->address->city }}<br>
                                {{ $order->address->province }} {{ $order->address->postal_code }}
                            </p>
                            <p class="mb-1">{{ $order->shipping_method }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>

                        @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Discount</span>
                                <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping Cost</span>
                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="mt-4">
                        <h6 class="mb-3">Payment Information</h6>
                        <div class="mb-2">
                            <strong>Payment Method:</strong><br>
                            {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                        </div>

                        @if($order->payment_method === 'bank_transfer')
                            <div class="alert alert-info mt-3">
                                <h6 class="mb-2">Payment Instructions:</h6>
                                <p class="mb-1">Please transfer to:</p>
                                <p class="mb-1"><strong>Bank:</strong> {{ $order->bank_name }}</p>
                                <p class="mb-1"><strong>Account Number:</strong> {{ $order->bank_account_number }}</p>
                                <p class="mb-1"><strong>Account Name:</strong> Apriori Shop</p>
                                <p class="mb-0"><strong>Amount:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                        @elseif($order->payment_method === 'e_wallet')
                            <div class="alert alert-info mt-3">
                                <h6 class="mb-2">Payment Instructions:</h6>
                                <p class="mb-1">Please pay using {{ $order->e_wallet_provider }}:</p>
                                <p class="mb-1"><strong>Account Number:</strong> {{ $order->e_wallet_account_number }}</p>
                                <p class="mb-1"><strong>Account Name:</strong> Apriori Shop</p>
                                <p class="mb-0"><strong>Amount:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                        @endif

                        <!-- Payment Proof Upload -->
                        @if($order->status === 'pending')
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h6 class="mb-3">Upload Payment Proof</h6>
                                    @if($order->payment && $order->payment->payment_proof)
                                        <div class="mb-3">
                                            <p class="mb-2">Current Payment Proof:</p>
                                            <img src="{{ asset('storage/' . $order->payment->payment_proof) }}"
                                                 class="img-fluid rounded mb-2"
                                                 style="max-height: 200px;"
                                                 alt="Payment Proof">
                                            <p class="text-muted small">Uploaded on: {{ $order->payment->updated_at->format('d M Y H:i') }}</p>
                                        </div>
                                    @endif
                                    <form action="{{ route('shop.orders.upload-payment', $order->id) }}"
                                          method="POST"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="payment_proof" class="form-label">Upload New Payment Proof</label>
                                            <input type="file"
                                                   class="form-control @error('payment_proof') is-invalid @enderror"
                                                   id="payment_proof"
                                                   name="payment_proof"
                                                   accept="image/*"
                                                   required>
                                            @error('payment_proof')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Upload a clear image of your payment proof (max 2MB)</div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-upload me-2"></i>Upload Proof
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Debug information -->
                            <div class="alert alert-info mt-3">
                                <p>Order Status: {{ $order->status }}</p>
                                <p>Payment Status: {{ $order->payment ? $order->payment->status : 'No payment' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection