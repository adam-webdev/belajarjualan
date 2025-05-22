@extends('layouts.layoutmaster')

@section('title', 'Flash Sale Details')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
<style>
    .btn i {
        font-size: 1rem;
        display: inline-block;
    }
    .progress {
        height: 0.5rem;
    }
    .product-image {
        max-width: 100px;
        max-height: 100px;
        object-fit: contain;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Flash Sale Details</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-secondary me-1">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    <a href="{{ route('admin.flash-sales.edit', $flashSale) }}" class="btn btn-warning me-1">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('admin.flash-sales.toggle-status', $flashSale) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-{{ $flashSale->is_active ? 'secondary' : 'success' }} me-1" title="{{ $flashSale->is_active ? 'Deactivate' : 'Activate' }}">
                            <i class="bi bi-toggle-{{ $flashSale->is_active ? 'on' : 'off' }}"></i>
                            {{ $flashSale->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.flash-sales.destroy', $flashSale) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure? This will also delete all flash sale items.')">
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
                                            <td>{{ $flashSale->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $flashSale->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $flashSale->description ?? 'No description' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($flashSale->isActiveNow)
                                                    <span class="badge bg-success">Active Now</span>
                                                @elseif($flashSale->isUpcoming)
                                                    <span class="badge bg-info">Upcoming</span>
                                                @elseif($flashSale->isExpired)
                                                    <span class="badge bg-secondary">Expired</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $flashSale->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated At</th>
                                            <td>{{ $flashSale->updated_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Time Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Start Time</th>
                                            <td>{{ $flashSale->start_time->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>End Time</th>
                                            <td>{{ $flashSale->end_time->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Duration</th>
                                            <td>{{ $flashSale->start_time->diffForHumans($flashSale->end_time, true) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Progress</th>
                                            <td>
                                                <div class="progress mt-1">
                                                    <div class="progress-bar
                                                        @if($flashSale->isActiveNow) bg-success
                                                        @elseif($flashSale->isUpcoming) bg-info
                                                        @else bg-secondary @endif"
                                                        role="progressbar"
                                                        style="width: {{ $flashSale->progressPercent }}%"
                                                        aria-valuenow="{{ $flashSale->progressPercent }}"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        {{ round($flashSale->progressPercent) }}%
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $flashSale->timeRemaining }}</small>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Flash Sale Items ({{ $flashSale->items->count() }})</h5>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemsModal">
                                        <i class="bi bi-plus"></i> Add Items
                                    </button>
                                </div>
                                <div class="card-body">
                                    @if($flashSale->items->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Variant</th>
                                                    <th>Regular Price</th>
                                                    <th>Flash Price</th>
                                                    <th>Discount</th>
                                                    <th>Stock</th>
                                                    <th>Limit</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($flashSale->items as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if($item->productCombination && $item->productCombination->product && $item->productCombination->product->images->where('is_primary', true)->first())
                                                            <img src="{{ asset('storage/' . $item->productCombination->product->images->where('is_primary', true)->first()->image_path) }}"
                                                                class="product-image me-2">
                                                            @endif
                                                            {{ $item->productCombination->product->name ?? 'N/A' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($item->productCombination && $item->productCombination->combinationValues && $item->productCombination->combinationValues->count() > 0)
                                                            @foreach($item->productCombination->combinationValues as $value)
                                                                @if($value->optionValue && $value->optionValue->option)
                                                                <span class="badge bg-light-secondary text-dark">
                                                                    {{ $value->optionValue->option->name }}: {{ $value->optionValue->value }}
                                                                </span>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted">No variant</span>
                                                        @endif
                                                    </td>
                                                    <td>Rp {{ number_format($item->regularPrice ?? 0, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($item->discount_price ?? 0, 0, ',', '.') }}</td>
                                                    <td>
                                                        <span class="badge bg-danger">{{ $item->discountPercent ?? 0 }}% OFF</span>
                                                    </td>
                                                    <td>
                                                        {{ $item->stockRemaining ?? 0 }} / {{ $item->stock_available ?? 0 }}
                                                        <div class="progress mt-1" style="height: 4px;">
                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                style="width: {{ $item->soldPercent ?? 0 }}%"
                                                                aria-valuenow="{{ $item->soldPercent ?? 0 }}"
                                                                aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ $item->purchase_limit ?? 'No limit' }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $item->is_active ? 'success' : 'danger' }}">
                                                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <button class="btn btn-sm btn-warning edit-item-btn"
                                                                data-id="{{ $item->id }}"
                                                                data-discount-price="{{ $item->discount_price }}"
                                                                data-stock-available="{{ $item->stock_available }}"
                                                                data-purchase-limit="{{ $item->purchase_limit }}"
                                                                data-is-active="{{ $item->is_active }}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editItemModal">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <form action="{{ route('admin.flash-sales.items.destroy', $item) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger"
                                                                        onclick="return confirm('Are you sure you want to remove this item?')"
                                                                        {{ $item->stock_sold > 0 ? 'disabled' : '' }}
                                                                        title="{{ $item->stock_sold > 0 ? 'Cannot remove items with sales' : 'Remove item' }}">
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
                                    @else
                                    <div class="alert alert-info">
                                        <h4 class="alert-heading">No Items Yet</h4>
                                        <p>This flash sale doesn't have any items yet. Click the "Add Items" button to add products to this flash sale.</p>
                                    </div>
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

<!-- Add Items Modal -->
<div class="modal fade" id="addItemsModal" tabindex="-1" aria-labelledby="addItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemsModalLabel">Add Flash Sale Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.flash-sales.items.store', $flashSale) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <p class="mb-0">Select products to add to this flash sale. You can add multiple items at once.</p>
                            </div>
                        </div>
                    </div>

                    <div id="itemsContainer">
                        <div class="row mb-4 item-row">
                            <div class="col-md-6">
                                <label>Product & Variant</label>
                                <select class="form-select product-select" name="items[0][product_combination_id]" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        @if($product->has_variant)
                                            @foreach($product->combinations as $combination)
                                                @if(!$flashSale->items->contains('product_combination_id', $combination->id))
                                                <option value="{{ $combination->id }}" data-price="{{ $combination->price }}">
                                                    {{ $product->name }} -
                                                    @foreach($combination->combinationValues as $value)
                                                        {{ $value->optionValue->option->name }}: {{ $value->optionValue->value }}
                                                        @if(!$loop->last) / @endif
                                                    @endforeach
                                                    (Rp {{ number_format($combination->price, 0, ',', '.') }})
                                                </option>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach($product->combinations as $combination)
                                                @if(!$flashSale->items->contains('product_combination_id', $combination->id))
                                                <option value="{{ $combination->id }}" data-price="{{ $combination->price }}">
                                                    {{ $product->name }} (Rp {{ number_format($combination->price, 0, ',', '.') }})
                                                </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Flash Price</label>
                                <input type="number" class="form-control discount-price" name="items[0][discount_price]" min="0" step="0.01" required>
                            </div>
                            <div class="col-md-2">
                                <label>Stock Available</label>
                                <input type="number" class="form-control" name="items[0][stock_available]" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <label>Purchase Limit</label>
                                <input type="number" class="form-control" name="items[0][purchase_limit]" min="0" placeholder="Optional">
                                <small class="text-muted">Leave empty for no limit</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="button" id="addMoreItemBtn" class="btn btn-outline-secondary">
                                <i class="bi bi-plus"></i> Add Another Item
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Items</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Flash Sale Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editItemForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_discount_price">Flash Price</label>
                        <input type="number" class="form-control" id="edit_discount_price" name="discount_price" min="0" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_stock_available">Stock Available</label>
                        <input type="number" class="form-control" id="edit_stock_available" name="stock_available" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_purchase_limit">Purchase Limit (per customer)</label>
                        <input type="number" class="form-control" id="edit_purchase_limit" name="purchase_limit" min="0" placeholder="Optional">
                        <small class="text-muted">Leave empty for no limit</small>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1">
                            <label class="form-check-label" for="edit_is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle adding more items
        let itemCount = 1;
        document.getElementById('addMoreItemBtn').addEventListener('click', function() {
            const itemsContainer = document.getElementById('itemsContainer');
            const newItem = document.querySelector('.item-row').cloneNode(true);

            // Update names with new index
            newItem.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace('[0]', `[${itemCount}]`);
                input.value = '';
            });

            itemsContainer.appendChild(newItem);
            itemCount++;

            // Reinitialize event listeners for new elements
            initProductSelectListeners();
        });

        // Handle product selection change to set default price
        function initProductSelectListeners() {
            document.querySelectorAll('.product-select').forEach(select => {
                select.addEventListener('change', function() {
                    const option = this.options[this.selectedIndex];
                    if (option.value) {
                        const price = parseFloat(option.getAttribute('data-price'));
                        const row = this.closest('.item-row');
                        const discountPriceInput = row.querySelector('.discount-price');

                        // Set a default discount price (e.g., 10% off)
                        if (price) {
                            discountPriceInput.value = (price * 0.9).toFixed(2);
                        }
                    }
                });
            });
        }

        // Initialize event listeners
        initProductSelectListeners();

        // Handle edit item modal
        document.querySelectorAll('.edit-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const discountPrice = this.getAttribute('data-discount-price');
                const stockAvailable = this.getAttribute('data-stock-available');
                const purchaseLimit = this.getAttribute('data-purchase-limit');
                const isActive = this.getAttribute('data-is-active') === '1';

                // Set form action
                document.getElementById('editItemForm').action = `/admin/flash-sales/items/${id}`;

                // Fill form fields
                document.getElementById('edit_discount_price').value = discountPrice;
                document.getElementById('edit_stock_available').value = stockAvailable;
                document.getElementById('edit_purchase_limit').value = purchaseLimit || '';
                document.getElementById('edit_is_active').checked = isActive;
            });
        });
    });
</script>
@endsection