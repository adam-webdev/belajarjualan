@extends('layouts.layoutmaster')

@section('content')
@include('sweetalert::alert')

@php
    $dashboardData = app(App\Http\Controllers\Admin\OrderController::class)->getDashboardData();
@endphp

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
                                    <h6>{{ \App\Models\Product::count() }}</h6>
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
                                    <h6>{{ $dashboardData['total_orders'] }}</h6>
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
                                    <h6>Rp {{ number_format(\App\Models\Order::where('status', '!=', 'cancelled')->sum('total'), 0, ',', '.') }}</h6>
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
                                        <h4>{{ $dashboardData['order_status']['pending'] }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-info">
                                        <h6>Processing</h6>
                                        <h4>{{ $dashboardData['order_status']['processing'] }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-success">
                                        <h6>Delivered</h6>
                                        <h4>{{ $dashboardData['order_status']['delivered'] }}</h4>
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
                                    @foreach($dashboardData['recent_orders'] as $order)
                                    <tr>
                                        <td>{{ $order['order_number'] }}</td>
                                        <td>{{ $order['customer_name'] }}</td>
                                        <td>Rp {{ number_format($order['total'], 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order['status'] == 'delivered' ? 'success' : ($order['status'] == 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($order['status']) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $order['payment_status'] == 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($order['payment_status']) }}
                                            </span>
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
                            <span class="badge bg-success">{{ $dashboardData['paid_orders'] }}</span>
                        </div>
                        <div class="progress">
                            @php
                                $paidPercentage = $dashboardData['total_orders'] > 0 ?
                                    ($dashboardData['paid_orders'] / $dashboardData['total_orders']) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $paidPercentage }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Pending</span>
                            <span class="badge bg-warning">{{ $dashboardData['pending_orders'] }}</span>
                        </div>
                        <div class="progress">
                            @php
                                $pendingPercentage = $dashboardData['total_orders'] > 0 ?
                                    ($dashboardData['pending_orders'] / $dashboardData['total_orders']) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pendingPercentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Articles Card -->
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Total Artikel</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ \App\Models\Artikel::count() }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection