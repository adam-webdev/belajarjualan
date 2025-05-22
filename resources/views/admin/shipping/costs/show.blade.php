@extends('layouts.layoutmaster')

@section('title', 'Shipping Cost Details')

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
                <h4 class="card-title">Shipping Cost Details</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.shipping.costs.index') }}" class="btn btn-secondary me-1">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    <a href="{{ route('admin.shipping.costs.edit', $cost) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">ID</th>
                                    <td>{{ $cost->id }}</td>
                                </tr>
                                <tr>
                                    <th>Shipping Method</th>
                                    <td>
                                        <a href="{{ route('admin.shipping.methods.show', $cost->shippingMethod) }}">
                                            {{ $cost->shippingMethod->name }}
                                        </a>
                                        <span class="badge bg-{{ $cost->shippingMethod->is_active ? 'success' : 'danger' }}">
                                            {{ $cost->shippingMethod->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Province</th>
                                    <td>{{ $cost->province }}</td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td>{{ $cost->city }}</td>
                                </tr>
                                <tr>
                                    <th>Cost</th>
                                    <td>Rp {{ number_format($cost->cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $cost->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $cost->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Shipping Method Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Name</th>
                                            <td>{{ $cost->shippingMethod->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Code</th>
                                            <td><code>{{ $cost->shippingMethod->code }}</code></td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge bg-{{ $cost->shippingMethod->is_active ? 'success' : 'danger' }}">
                                                    {{ $cost->shippingMethod->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                    <a href="{{ route('admin.shipping.costs.by-method', $cost->shippingMethod) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> View All Costs for This Method
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection