@extends('layouts.layoutmaster')

@section('title', 'Category Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Category Details</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning me-1">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
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
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">Products</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <!-- Details Tab -->
                        <div class="tab-pane fade show active" id="details" role="tabpanel">
                            <div class="row mt-3">
                                @if($category->image)
                                <div class="col-md-4 text-center mb-3">
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                                <div class="col-md-8">
                                @else
                                <div class="col-md-12">
                                @endif
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="fw-bold">Name:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <p>{{ $category->name }}</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">Slug:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <p>{{ $category->slug }}</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">Description:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <p>{{ $category->description ?: 'No description' }}</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">Status:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">Created At:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <p>{{ $category->created_at->format('d M Y H:i') }}</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">Last Updated:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <p>{{ $category->updated_at->format('d M Y H:i') }}</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">Product Count:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <p>{{ $category->products->count() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products Tab -->
                        <div class="tab-pane fade" id="products" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table table-hover mb-0" id="products-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($category->products as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>
                                                @if($product->primaryImage)
                                                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 50px;">
                                                @else
                                                    <span class="text-muted">No image</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>Rp {{ number_format($product->base_price, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="#" class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No products found in this category</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let productsTable = document.querySelector('#products-table');
        if (productsTable) {
            new simpleDatatables.DataTable(productsTable);
        }
    });
</script>
@endsection
