@extends('layouts.layoutmaster')

@section('title', 'Order Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Order #{{ $order->order_number }}</h4>
                <div>
                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit Order
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Order Information</h5>
                        <table class="table table-sm">
                            <tr>
                                <th style="width: 40%">Order Number</th>
                                <td>{{ $order->order_number }}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'pending' ? 'warning' :
                                                      ($order->status === 'processing' ? 'info' :
                                                      ($order->status === 'shipped' ? 'primary' :
                                                      ($order->status === 'delivered' ? 'success' : 'danger'))) }}">
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
                            </tr>
                            @if($order->tracking_number)
                            <tr>
                                <th>Tracking Number</th>
                                <td>{{ $order->tracking_number }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Status Pembayaran</th>
                                <td>
                                    @if($order->payment)
                                        <span class="badge bg-{{ $order->payment->status === 'paid' ? 'success' : 'warning' }}">
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
                                        <span class="badge bg-light-secondary">Belum Bayar</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Customer Information</h5>
                        <table class="table table-sm">
                            <tr>
                                <th style="width: 40%">Customer</th>
                                <td>{{ $order->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $order->user->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $order->user->phone ?? 'N/A' }}</td>
                            </tr>
                        </table>

                        <h5 class="mt-4">Shipping Address</h5>
                        <div class="p-3 border rounded">
                            <p class="mb-1"><strong>{{ $order->address->recipient_name }}</strong></p>
                            <p class="mb-1">{{ $order->address->full_address }}</p>
                            <p class="mb-1">{{ $order->address->city }}, {{ $order->address->postal_code }}</p>
                            <p class="mb-1">{{ $order->address->state }}, {{ $order->address->country }}</p>
                            <p class="mb-0">Phone: {{ $order->address->phone }}</p>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4">Order Items</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Variant</th>
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
                                        @if($detail->productCombination->product->images->where('is_primary', true)->first())
                                        <img
                                            src="{{ asset('storage/' . $detail->productCombination->product->images->where('is_primary', true)->first()->image_path) }}"
                                            alt="{{ $detail->productCombination->product->name }}"
                                            width="50"
                                            class="me-2">
                                        @endif
                                        <span>{{ $detail->productCombination->product->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($detail->productCombination->combinationValues->count() > 0)
                                        @foreach($detail->productCombination->combinationValues as $value)
                                            <span class="badge bg-light-secondary">
                                                {{ $value->optionValue->option->name }}: {{ $value->optionValue->value }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Default</span>
                                    @endif
                                </td>
                                <td>{{ number_format($detail->price, 2) }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td class="text-end">{{ number_format($detail->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Subtotal</strong></td>
                                <td class="text-end">{{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Shipping Cost</strong></td>
                                <td class="text-end">{{ number_format($order->shipping_cost, 2) }}</td>
                            </tr>
                            @if($order->shippingMethod)
                            <tr>
                                <td colspan="4" class="text-end"><strong>Shipping Method</strong></td>
                                <td class="text-end">{{ $order->shippingMethod->name }}</td>
                            </tr>
                            @endif
                            @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="4" class="text-end">
                                    <strong>Discount</strong>
                                    @if($order->coupon)
                                    <small class="text-muted">(Coupon: {{ $order->coupon_code }})</small>
                                    @endif
                                </td>
                                <td class="text-end">-{{ number_format($order->discount_amount, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total</strong></td>
                                <td class="text-end"><strong>{{ number_format($order->total, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($order->notes)
                <div class="mt-4">
                    <h5>Notes</h5>
                    <div class="p-3 border rounded">
                        {{ $order->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection