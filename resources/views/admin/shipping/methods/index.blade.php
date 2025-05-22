@extends('layouts.layoutmaster')

@section('title', 'Shipping Methods')

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
                <h4 class="card-title">Shipping Methods List</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.shipping.methods.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Add New Method
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
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shippingMethods as $method)
                                <tr>
                                    <td>{{ $method->id }}</td>
                                    <td>{{ $method->name }}</td>
                                    <td><code>{{ $method->code }}</code></td>
                                    <td>
                                        <span class="badge bg-{{ $method->is_active ? 'success' : 'danger' }}">
                                            {{ $method->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.shipping.methods.show', $method) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.shipping.costs.by-method', $method) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-cursor"></i>
                                            </a>
                                            <a href="{{ route('admin.shipping.methods.edit', $method) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.shipping.methods.toggle-status', $method) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-{{ $method->is_active ? 'secondary' : 'success' }}" title="{{ $method->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi bi-toggle-{{ $method->is_active ? 'on' : 'off' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.shipping.methods.destroy', $method) }}" method="POST" class="d-inline">
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