@extends('layouts.layoutmaster')

@section('content')
@include('sweetalert::alert')

<section class="section dashboard">
    <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-8">
            <div class="row">
                <!-- Products Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Produk</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-box"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $totalProducts }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Pesanan</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $totalOrders }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Pendapatan</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Status Cards -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Status Pesanan</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="alert alert-warning">
                                        <h6>Pending</h6>
                                        <h4>{{ $pendingOrders }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-info">
                                        <h6>Processing</h6>
                                        <h4>{{ $processingOrders }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-success">
                                        <h6>Delivered</h6>
                                        <h4>{{ $deliveredOrders }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="col-12">
                    <div class="card recent-sales overflow-auto">
                        <div class="card-body">
                            <h5 class="card-title">Pesanan Terbaru</h5>
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Order Number</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($order->payment)
                                                <span class="badge bg-{{ $order->payment->status == 'paid' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($order->payment->status) }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">No Payment</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side columns -->
        <div class="col-lg-4">
            <!-- Payment Status -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Status Pembayaran</h5>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Paid</span>
                            <span class="badge bg-success">{{ $paidOrders }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($totalOrders > 0 ? ($paidOrders / $totalOrders) * 100 : 0) }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Pending</span>
                            <span class="badge bg-warning">{{ $pendingPayments }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ ($totalOrders > 0 ? ($pendingPayments / $totalOrders) * 100 : 0) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
