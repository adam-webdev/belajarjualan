@extends('layouts.frontend.master')

@section('title', 'Order Details - Apriori Shop')

@section('content')
<div class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.profile') }}">My Profile</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.profile.orders') }}">My Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order Details</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Order Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Order #{{ $order->order_number }}</h5>
                        <span class="badge bg-{{ $order->status === 'pending' ? 'warning' :
                            ($order->status === 'processing' ? 'info' :
                            ($order->status === 'shipped' ? 'primary' :
                            ($order->status === 'delivered' ? 'success' :
                            ($order->status === 'cancelled' ? 'danger' : 'secondary')))) }}">
                            @switch($order->status)
                                @case('pending')
                                    Menunggu
                                    @break
                                @case('processing')
                                    Diproses
                                    @break
                                @case('shipped')
                                    Dikirim
                                    @break
                                @case('delivered')
                                    Diterima
                                    @break
                                @case('cancelled')
                                    Dibatalkan
                                    @break
                            @endswitch
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-3">Informasi Pesanan</h6>
                            <p class="mb-1"><strong>Tanggal Pesanan:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                            <p class="mb-1"><strong>Metode Pembayaran:</strong>
                                @if($order->payment)
                                    @switch($order->payment->payment_method)
                                        @case('bank_transfer')
                                            Transfer Bank
                                            @break
                                        @case('e_wallet')
                                            E-Wallet
                                            @break
                                    @endswitch
                                @else
                                    -
                                @endif
                            </p>
                            <p class="mb-1"><strong>Status Pembayaran:</strong>
                                @if($order->payment)
                                    <span class="badge bg-{{ $order->payment->status === 'paid' ? 'success' :
                                        ($order->payment->status === 'pending' ? 'warning' : 'danger') }}">
                                        @switch($order->payment->status)
                                            @case('paid')
                                                Lunas
                                                @break
                                            @case('pending')
                                                Menunggu
                                                @break
                                            @case('failed')
                                                Gagal
                                                @break
                                            @case('refunded')
                                                Dikembalikan
                                                @break
                                        @endswitch
                                    </span>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Informasi Pengiriman</h6>
                            <p class="mb-1"><strong>Penerima:</strong> {{ $order->address->recipient_name }}</p>
                            <p class="mb-1"><strong>Telepon:</strong> {{ $order->address->phone }}</p>
                            <p class="mb-1"><strong>Alamat:</strong> {{ $order->address->address_detail }}</p>
                            <p class="mb-1">{{ $order->address->city }}, {{ $order->address->province }} {{ $order->address->postal_code }}</p>
                        </div>
                    </div>

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
                                        <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
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
                        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>

                    @if($order->status === 'pending' && $order->payment && $order->payment->status === 'pending')
                        <div class="mt-4">
                            <h6 class="mb-3">Petunjuk Pembayaran</h6>
                            @if($order->payment->payment_method === 'bank_transfer')
                                <div class="alert alert-info">
                                    <p class="mb-1">Silakan transfer ke:</p>
                                    <p class="mb-1"><strong>Bank:</strong> {{ $order->payment->bank_name }}</p>
                                    <p class="mb-1"><strong>Nomor Rekening:</strong> {{ $order->payment->account_number }}</p>
                                    <p class="mb-1"><strong>Atas Nama:</strong> Apriori Shop</p>
                                </div>
                            @elseif($order->payment->payment_method === 'e_wallet')
                                <div class="alert alert-info">
                                    <p class="mb-1">Silakan bayar ke:</p>
                                    <p class="mb-1"><strong>Provider:</strong> {{ $order->payment->e_wallet_provider }}</p>
                                    <p class="mb-1"><strong>Nomor Akun:</strong> {{ $order->payment->account_number }}</p>
                                    <p class="mb-1"><strong>Atas Nama:</strong> Apriori Shop</p>
                                </div>
                            @endif

                            <form action="{{ route('shop.orders.upload-payment', $order->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                @csrf
                                <div class="mb-3">
                                    <label for="payment_proof" class="form-label">Unggah Bukti Pembayaran</label>
                                    <input type="file" class="form-control @error('payment_proof') is-invalid @enderror"
                                           id="payment_proof" name="payment_proof" accept="image/*">
                                    @error('payment_proof')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Unggah Bukti</button>
                            </form>
                        </div>
                    @endif

                    @if($order->status === 'pending' || $order->status === 'processing')
                        <form action="{{ route('shop.orders.cancel', $order->id) }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100"
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                Batalkan Pesanan
                            </button>
                        </form>
                    @endif

                    @if($order->status === 'shipped')
                        <form action="{{ route('shop.orders.confirm-delivery', $order->id) }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-success w-100"
                                    onclick="return confirm('Confirm that you have received this order?')">
                                Confirm Delivery
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection