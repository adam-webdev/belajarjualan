@extends('layouts.layoutmaster')

@section('title', 'Shipping Method Details')

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
                <h4 class="card-title">Shipping Method Details</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.shipping.methods.index') }}" class="btn btn-secondary me-1">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    <a href="{{ route('admin.shipping.methods.edit', $method) }}" class="btn btn-warning me-1">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('admin.shipping.costs.by-method', $method) }}" class="btn btn-primary">
                        <i class="bi bi-cash-coin"></i> View Costs
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
                                    <td>{{ $method->id }}</td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $method->name }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 30%">Code</th>
                                    <td>{{ $method->code }}</td>
                                </tr>
                                <tr>
                                    <th>Default Cost</th>
                                    <td>{{ number_format($method->default_cost, 0) }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-{{ $method->is_active ? 'success' : 'danger' }}">
                                            {{ $method->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $method->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $method->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Shipping Costs</h5>
                                </div>
                                <div class="card-body">
                                    @if($method->shippingCosts->count() > 0)
                                        <p>This shipping method has {{ $method->shippingCosts->count() }} shipping costs defined.</p>
                                        <a href="{{ route('admin.shipping.costs.by-method', $method) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> View All Costs
                                        </a>
                                    @else
                                        <p class="text-muted">No shipping costs defined for this method yet.</p>
                                        <a href="{{ route('admin.shipping.costs.create') }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-plus"></i> Add Shipping Cost
                                        </a>
                                    @endif
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