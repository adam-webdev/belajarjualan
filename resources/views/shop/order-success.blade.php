@extends('layouts.frontend.master')

@section('title', 'Order Success - THRIFT SHOP')

@section('content')
<div class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.orders') }}">My Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order Success</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-brown" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="card-title mb-3">Order Placed Successfully!</h2>
                    <p class="text-muted mb-4">Thank you for your purchase. Your order has been received.</p>

                    <div class="order-info mb-4">
                        <h5>Order Details</h5>
                        <p class="mb-1">Order Number: <strong>{{ $order->order_number }}</strong></p>
                        <p class="mb-1">Date: <strong>{{ $order->created_at->format('d M Y H:i') }}</strong></p>
                        <p class="mb-1">Total Amount: <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></p>
                        <p class="mb-1">Payment Method: <strong>{{ $order->payment->payment_method }}</strong></p>
                    </div>

                    @if($order->payment->payment_method === 'bank_transfer')
                        <div class="payment-info mb-4">
                            <h5>Payment Information</h5>
                            @php
                                $paymentDetails = json_decode($order->payment->payment_proof, true) ?? [];
                            @endphp
                            <p class="mb-1">Bank: <strong>{{ $paymentDetails['bank_name'] ?? 'N/A' }}</strong></p>
                            <p class="mb-1">Account Number: <strong>{{ $paymentDetails['account_number'] ?? 'N/A' }}</strong></p>
                        </div>
                    @elseif(strpos($order->payment->payment_method, 'e_wallet') !== false)
                        <div class="payment-info mb-4">
                            <h5>Payment Information</h5>
                            @php
                                $paymentDetails = json_decode($order->payment->payment_proof, true) ?? [];
                            @endphp
                            <p class="mb-1">Provider: <strong>{{ $paymentDetails['provider'] ?? 'N/A' }}</strong></p>
                            <p class="mb-1">Account Number: <strong>{{ $paymentDetails['account_number'] ?? 'N/A' }}</strong></p>
                        </div>
                    @endif

                    <div class="order-items mb-4">
                        <h5>Order Items</h5>
                        @foreach($order->details as $detail)
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <p class="mb-0">{{ $detail->productCombination->product->name }}</p>
                                    <small class="text-muted">Quantity: {{ $detail->quantity }} x Rp {{ number_format($detail->price, 0, ',', '.') }}</small>
                                </div>
                                <div class="text-end">
                                    <strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="shipping-info mb-4">
                        <h5>Shipping Information</h5>
                        <p class="mb-1">Name: <strong>{{ $order->address->name }}</strong></p>
                        <p class="mb-1">Phone: <strong>{{ $order->address->phone }}</strong></p>
                        <p class="mb-1">Address: <strong>{{ $order->address->address }}, {{ $order->address->city }}, {{ $order->address->province }} {{ $order->address->postal_code }}</strong></p>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('shop.orders') }}" class="btn btn-brown">View My Orders</a>
                        <a href="{{ url('/') }}" class="btn btn-outline-brown">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-brown {
    color: #8B4513 !important;
}
.btn-brown {
    background-color: #8B4513;
    border-color: #8B4513;
    color: white;
}
.btn-brown:hover {
    background-color: #6B3410;
    border-color: #6B3410;
    color: white;
}
.btn-outline-brown {
    color: #8B4513;
    border-color: #8B4513;
}
.btn-outline-brown:hover {
    background-color: #8B4513;
    border-color: #8B4513;
    color: white;
}
</style>
@endsection