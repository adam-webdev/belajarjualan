@extends('layouts.layoutmaster')

@section('title', 'Shipping Costs')

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
                <h4 class="card-title">Shipping Costs List</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.shipping.costs.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Add New Cost
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
                                    <th>Shipping Method</th>
                                    <th>Province</th>
                                    <th>City</th>
                                    <th>Cost</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shippingCosts as $cost)
                                <tr>
                                    <td>{{ $cost->id }}</td>
                                    <td>
                                        <span class="badge bg-{{ $cost->shippingMethod->is_active ? 'success' : 'danger' }}">
                                            {{ $cost->shippingMethod->name }}
                                        </span>
                                    </td>
                                    <td>{{ $cost->province }}</td>
                                    <td>{{ $cost->city }}</td>
                                    <td>Rp {{ number_format($cost->cost, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.shipping.costs.show', $cost) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.shipping.costs.edit', $cost) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.shipping.costs.destroy', $cost) }}" method="POST" class="d-inline">
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