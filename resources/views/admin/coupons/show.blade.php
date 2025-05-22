@extends('layouts.layoutmaster')

@section('title', 'Coupon Details')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
<style>
    .btn i {
        font-size: 1rem;
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Coupon Details</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary me-1">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning me-1">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-{{ $coupon->is_active ? 'secondary' : 'success' }} me-1" title="{{ $coupon->is_active ? 'Deactivate' : 'Activate' }}">
                            <i class="bi bi-toggle-{{ $coupon->is_active ? 'on' : 'off' }}"></i>
                            {{ $coupon->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">ID</th>
                                            <td>{{ $coupon->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Code</th>
                                            <td><code>{{ $coupon->code }}</code></td>
                                        </tr>
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $coupon->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $coupon->description ?? 'No description' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Type</th>
                                            <td>{{ ucfirst($coupon->type) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Value</th>
                                            <td>
                                                @if($coupon->type == 'percentage')
                                                    {{ $coupon->value }}%
                                                @else
                                                    Rp {{ number_format($coupon->value, 0, ',', '.') }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge bg-{{ $coupon->is_active ? 'success' : 'danger' }}">
                                                    {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $coupon->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated At</th>
                                            <td>{{ $coupon->updated_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Usage Restrictions</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Minimum Purchase</th>
                                            <td>
                                                @if($coupon->min_purchase)
                                                    Rp {{ number_format($coupon->min_purchase, 0, ',', '.') }}
                                                @else
                                                    <span class="text-muted">No minimum</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($coupon->type == 'percentage')
                                        <tr>
                                            <th>Maximum Discount</th>
                                            <td>
                                                @if($coupon->max_discount)
                                                    Rp {{ number_format($coupon->max_discount, 0, ',', '.') }}
                                                @else
                                                    <span class="text-muted">No maximum</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>Usage Count</th>
                                            <td>
                                                {{ $coupon->used_count }}
                                                @if($coupon->max_uses)
                                                    of {{ $coupon->max_uses }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Max Uses Per User</th>
                                            <td>
                                                @if($coupon->max_uses_per_user)
                                                    {{ $coupon->max_uses_per_user }}
                                                @else
                                                    <span class="text-muted">Unlimited</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Valid From</th>
                                            <td>
                                                @if($coupon->starts_at)
                                                    {{ $coupon->starts_at->format('d M Y') }}
                                                @else
                                                    <span class="text-muted">Started immediately</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Valid Until</th>
                                            <td>
                                                @if($coupon->expires_at)
                                                    {{ $coupon->expires_at->format('d M Y') }}
                                                @else
                                                    <span class="text-muted">No expiry</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Validity Status</th>
                                            <td>
                                                <span class="badge bg-{{ $coupon->isValid() ? 'success' : 'danger' }}">
                                                    {{ $coupon->isValid() ? 'Valid' : 'Invalid' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Applicable Categories</h5>
                                </div>
                                <div class="card-body">
                                    @if($coupon->categories->count() > 0)
                                        <div class="list-group">
                                            @foreach($coupon->categories as $category)
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    {{ $category->name }}
                                                    <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }} rounded-pill">
                                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">This coupon is applicable to all categories.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Applicable Products</h5>
                                </div>
                                <div class="card-body">
                                    @if($coupon->products->count() > 0)
                                        <div class="list-group">
                                            @foreach($coupon->products as $product)
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    {{ $product->name }}
                                                    <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }} rounded-pill">
                                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">This coupon is applicable to all products.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($coupon->usages->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Usage History</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>User</th>
                                                    <th>Order ID</th>
                                                    <th>Used At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($coupon->usages as $usage)
                                                <tr>
                                                    <td>{{ $usage->id }}</td>
                                                    <td>
                                                        @if($usage->user)
                                                            {{ $usage->user->name }} ({{ $usage->user->email }})
                                                        @else
                                                            <span class="text-muted">Unknown user</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($usage->order_id)
                                                            #{{ $usage->order_id }}
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $usage->created_at->format('d M Y H:i') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection