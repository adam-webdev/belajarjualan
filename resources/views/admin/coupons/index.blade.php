@extends('layouts.layoutmaster')

@section('title', 'Coupons')

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
                <h4 class="card-title">Coupons List</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Add New Coupon
                    </a>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="table1">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Validity</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->id }}</td>
                                    <td><code>{{ $coupon->code }}</code></td>
                                    <td>{{ $coupon->name }}</td>
                                    <td>{{ ucfirst($coupon->type) }}</td>
                                    <td>
                                        @if($coupon->type == 'percentage')
                                            {{ $coupon->value }}%
                                        @else
                                            Rp {{ number_format($coupon->value, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($coupon->starts_at && $coupon->expires_at)
                                            {{ $coupon->starts_at->format('d M Y') }} - {{ $coupon->expires_at->format('d M Y') }}
                                        @elseif($coupon->starts_at)
                                            From {{ $coupon->starts_at->format('d M Y') }}
                                        @elseif($coupon->expires_at)
                                            Until {{ $coupon->expires_at->format('d M Y') }}
                                        @else
                                            No time limit
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $coupon->isValid() ? 'success' : 'danger' }}">
                                            {{ $coupon->isValid() ? 'Valid' : 'Invalid' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-{{ $coupon->is_active ? 'secondary' : 'success' }}" title="{{ $coupon->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi bi-toggle-{{ $coupon->is_active ? 'on' : 'off' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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
</div>
@endsection