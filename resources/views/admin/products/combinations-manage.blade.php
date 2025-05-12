@extends('layouts.layoutmaster')

@section('title', 'Manage Product Combinations')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Manage Combinations for: {{ $product->name }}</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.products.show', $product) }}#combinations" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Product
                    </a>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Generate Multiple Combinations</h5>
                                </div>
                                <div class="card-body">
                                    @if($product->options->count() > 0)
                                        <form action="{{ route('admin.products.combinations.generate', $product) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label>Select Options to Combine</label>
                                                @foreach($product->options as $option)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" name="options[]"
                                                            value="{{ $option->id }}" id="option{{ $option->id }}">
                                                        <label class="form-check-label" for="option{{ $option->id }}">
                                                            {{ $option->name }} ({{ $option->values->count() }} values)
                                                        </label>
                                                    </div>
                                                @endforeach
                                                <small class="text-muted">Select which options to include in the generated combinations.</small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="base_sku">Base SKU</label>
                                                <input type="text" class="form-control" id="base_sku" name="base_sku"
                                                    placeholder="e.g. PROD-1" required>
                                                <small class="text-muted">This will be used as a prefix for all generated SKUs.</small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="base_price">Base Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control" id="base_price" name="base_price"
                                                        min="0" value="{{ $product->base_price }}" required>
                                                </div>
                                                <small class="text-muted">All generated combinations will have this price initially.</small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="base_stock">Base Stock</label>
                                                <input type="number" class="form-control" id="base_stock" name="base_stock"
                                                    min="0" value="10" required>
                                                <small class="text-muted">All generated combinations will have this stock initially.</small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="base_weight">Base Weight (grams)</label>
                                                <input type="number" class="form-control" id="base_weight" name="base_weight"
                                                    min="0" value="100" required>
                                                <small class="text-muted">All generated combinations will have this weight initially.</small>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Generate Combinations</button>
                                        </form>
                                    @else
                                        <div class="alert alert-warning">
                                            You need to add options before generating combinations.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Product Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Category:</strong> {{ $product->category->name }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Options:</strong> {{ $product->options->count() }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Combinations:</strong> {{ $product->combinations->count() }}
                                    </div>
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i>
                                        Generate combinations automatically based on your options.
                                        You can edit individual combinations afterward.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <h5>Product Combinations</h5>

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
        // Initialize the modal
        const editCombinationModal = document.getElementById('editCombinationModal');
        if (editCombinationModal) {
            const modal = new bootstrap.Modal(editCombinationModal);

            // Handle edit combination buttons
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

                    const form = document.getElementById('editCombinationForm');
                    form.action = `{{ route('admin.products.combinations.update', '') }}/${id}`;

                    modal.show();
                });
            });
        }
    });
</script>
@endsection