@extends('layouts.frontend.master')

@section('title', 'My Orders - Apriori Shop')

@section('content')
<div class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.profile') }}">My Profile</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Orders</li>
        </ol>
    </nav>

    <h1 class="h3 mb-4">My Orders</h1>

    <!-- Order Filter and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('shop.orders') }}" method="GET" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label for="status" class="form-label">Filter by Status</label>
                        <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                            <option value="">All Orders</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label for="date" class="form-label">Filter by Date</label>
                        <select class="form-select" id="date" name="date" onchange="this.form.submit()">
                            <option value="">All Time</option>
                            <option value="30days" {{ request('date') == '30days' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="3months" {{ request('date') == '3months' ? 'selected' : '' }}>Last 3 Months</option>
                            <option value="6months" {{ request('date') == '6months' ? 'selected' : '' }}>Last 6 Months</option>
                            <option value="1year" {{ request('date') == '1year' ? 'selected' : '' }}>Last Year</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="search" class="form-label">Search Orders</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search"
                                   placeholder="Search by order number..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nomor Pesanan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Metode Pembayaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>#{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                    <td>
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
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <a href="{{ route('shop.profile.order.detail', $order->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-bag fs-1 text-muted mb-3"></i>
                <h5 class="mb-3">No Orders Found</h5>
                <p class="text-muted mb-4">
                    @if(request('status') || request('date') || request('search'))
                        No orders match your current filters.
                    @else
                        You haven't placed any orders yet.
                    @endif
                </p>
                <a href="{{ url('/') }}" class="btn btn-primary">
                    Start Shopping
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change
    const filterInputs = document.querySelectorAll('#filterForm select');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endpush