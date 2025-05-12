@extends('layouts.layoutmaster')

@section('title', 'Product Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Product Details</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning me-1">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
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
                            <button class="nav-link" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button" role="tab">Images</button>
                        </li>
                        @if($product->has_variant)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="options-tab" data-bs-toggle="tab" data-bs-target="#options" type="button" role="tab">Options</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="combinations-tab" data-bs-toggle="tab" data-bs-target="#combinations" type="button" role="tab">Combinations</button>
                        </li>
                        @endif
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <!-- Details Tab -->
                        <div class="tab-pane fade show active" id="details" role="tabpanel">
                            <div class="row mt-3">
                                <div class="col-md-4 text-center mb-3">
                                    @if($product->primaryImage)
                                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                                    @else
                                        <div class="border rounded p-5">
                                            <i class="bi bi-image fs-1 text-muted"></i>
                                            <p class="text-muted">No image available</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="fw-bold">Name:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <p>{{ $product->name }}</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">Category:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <p>{{ $product->category->name }}</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">Slug:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <p>{{ $product->slug }}</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">Base Price:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <p>Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">Has Variants:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <span class="badge bg-{{ $product->has_variant ? 'info' : 'secondary' }}">
                                                {{ $product->has_variant ? 'Yes' : 'No' }}
                                            </span>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">Status:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">Created At:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <p>{{ $product->created_at->format('d M Y H:i') }}</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">Last Updated:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <p>{{ $product->updated_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <label class="fw-bold">Description:</label>
                                    <div class="border rounded p-3 mt-2">
                                        {!! $product->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Images Tab -->
                        <div class="tab-pane fade" id="images" role="tabpanel">
                            <div class="row mt-3 mb-3">
                                <div class="col-12">
                                    <form action="{{ route('admin.products.images.store', $product) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group">
                                            <input type="file" name="images[]" multiple class="form-control" accept="image/*" required>
                                            <button type="submit" class="btn btn-primary">Upload Images</button>
                                        </div>
                                        <small class="text-muted">You can upload multiple images at once.</small>
                                    </form>
                                </div>
                            </div>

                            <div class="row">
                                @forelse($product->images as $image)
                                <div class="col-md-3 mb-4">
                                    <div class="card h-100">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top" alt="{{ $product->name }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                @if($image->is_primary)
                                                    <span class="badge bg-success">Primary</span>
                                                @else
                                                    <form action="{{ route('admin.products.images.primary', $image) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">Set as Primary</button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.products.images.destroy', $image) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        No images available for this product. Upload some images above.
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        @if($product->has_variant)
                        <!-- Options Tab -->
                        <div class="tab-pane fade" id="options" role="tabpanel">
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="card shadow-sm">
                                        <div class="card-header">
                                            <h5 class="card-title">Add New Option</h5>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('admin.products.options.store', $product) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="option_name">Option Name</label>
                                                    <input type="text" class="form-control" id="option_name" name="option_name" placeholder="e.g. Size, Color" required>
                                                    <small class="text-muted">This is the name of the option (e.g. Size, Color)</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Option Values</label>
                                                    <div id="option_values_container">
                                                        <div class="input-group mb-2">
                                                            <input type="text" class="form-control" name="option_values[]" placeholder="e.g. Red, XL" required>
                                                            <button type="button" class="btn btn-outline-secondary add-option-value">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">These are the available values for this option (e.g. Red, Blue, XL, L)</small>
                                                </div>

                                                <button type="submit" class="btn btn-primary">Save Option</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5>Product Options</h5>
                                    @forelse($product->options as $option)
                                    <div class="card mb-3">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $option->name }}</h6>
                                            <div>
                                                <a href="{{ route('admin.products.options.edit', $option) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.products.options.destroy', $option) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex flex-wrap">
                                                @foreach($option->values as $value)
                                                <span class="badge bg-light text-dark m-1 p-2">{{ $value->value }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="alert alert-info">
                                        No options defined yet. Add your first option using the form on the left.
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Combinations Tab -->
                        <div class="tab-pane fade" id="combinations" role="tabpanel">
                            <div class="row mt-3">
                                <div class="col-md-5">
                                    <div class="card shadow-sm">
                                        <div class="card-header">
                                            <h5 class="card-title">Add New Combination</h5>
                                        </div>
                                        <div class="card-body">
                                            @if($product->options->count() > 0)
                                            <form action="{{ route('admin.products.combinations.store', $product) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label>Options</label>
                                                    @foreach($product->options as $option)
                                                    <div class="mb-2">
                                                        <label>{{ $option->name }}</label>
                                                        <select name="option_values[]" class="choices form-select" required>
                                                            <option value="">Select {{ $option->name }}</option>
                                                            @foreach($option->values as $value)
                                                            <option value="{{ $value->id }}">{{ $value->value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @endforeach
                                                </div>

                                                <div class="mb-3">
                                                    <label for="sku">SKU</label>
                                                    <input type="text" class="form-control" id="sku" name="sku" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="price">Price</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control" id="price" name="price" min="0" required>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="stock">Stock</label>
                                                    <input type="number" class="form-control" id="stock" name="stock" min="0" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="weight">Weight (grams)</label>
                                                    <input type="number" class="form-control" id="weight" name="weight" min="0" required>
                                                </div>

                                                <button type="submit" class="btn btn-primary">Save Combination</button>
                                            </form>
                                            @else
                                            <div class="alert alert-warning">
                                                You need to add options before creating combinations.
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <h5>Product Combinations</h5>
                                    <div class="d-flex justify-content-end mb-3">
                                        <a href="{{ route('admin.products.combinations.manage', $product) }}" class="btn btn-primary">
                                            <i class="bi bi-gear"></i> Manage All Combinations
                                        </a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Combination</th>
                                                    <th>SKU</th>
                                                    <th>Price</th>
                                                    <th>Stock</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($product->combinations as $combination)
                                                <tr>
                                                    <td>
                                                        @foreach($combination->values as $combinationValue)
                                                        <span class="badge bg-light text-dark">
                                                            {{ $combinationValue->optionValue->option->name }}: {{ $combinationValue->optionValue->value }}
                                                        </span>
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $combination->sku }}</td>
                                                    <td>Rp {{ number_format($combination->price, 0, ',', '.') }}</td>
                                                    <td>{{ $combination->stock }}</td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <button type="button" class="btn btn-sm btn-warning edit-combination"
                                                                data-id="{{ $combination->id }}"
                                                                data-sku="{{ $combination->sku }}"
                                                                data-price="{{ $combination->price }}"
                                                                data-stock="{{ $combination->stock }}"
                                                                data-weight="{{ $combination->weight }}">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <form action="{{ route('admin.products.combinations.destroy', $combination) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No combinations available</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
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
</div>

<!-- Edit Combination Modal -->
<div class="modal fade" id="editCombinationModal" tabindex="-1" aria-labelledby="editCombinationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCombinationModalLabel">Edit Combination</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCombinationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_sku">SKU</label>
                        <input type="text" class="form-control" id="edit_sku" name="sku" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_price">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="edit_price" name="price" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_stock">Stock</label>
                        <input type="number" class="form-control" id="edit_stock" name="stock" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_weight">Weight (grams)</label>
                        <input type="number" class="form-control" id="edit_weight" name="weight" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle adding option values
        document.querySelectorAll('.add-option-value').forEach(button => {
            button.addEventListener('click', function() {
                const container = document.getElementById('option_values_container');
                const newRow = document.createElement('div');
                newRow.className = 'input-group mb-2';
                newRow.innerHTML = `
                    <input type="text" class="form-control" name="option_values[]" placeholder="e.g. Red, XL" required>
                    <button type="button" class="btn btn-outline-danger remove-option-value">
                        <i class="bi bi-dash"></i>
                    </button>
                `;
                container.appendChild(newRow);

                // Add event listener to the newly created remove button
                newRow.querySelector('.remove-option-value').addEventListener('click', function() {
                    container.removeChild(newRow);
                });
            });
        });

        // Handle edit combination modal
        const editCombinationModal = document.getElementById('editCombinationModal');
        if (editCombinationModal) {
            const modal = new bootstrap.Modal(editCombinationModal);

            document.querySelectorAll('.edit-combination').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const sku = this.getAttribute('data-sku');
                    const price = this.getAttribute('data-price');
                    const stock = this.getAttribute('data-stock');
                    const weight = this.getAttribute('data-weight');

                    document.getElementById('edit_sku').value = sku;
                    document.getElementById('edit_price').value = price;
                    document.getElementById('edit_stock').value = stock;
                    document.getElementById('edit_weight').value = weight;

                    document.getElementById('editCombinationForm').action = "{{ route('admin.products.combinations.update', '') }}/" + id;

                    modal.show();
                });
            });
        }
    });
</script>
@endsection