@extends('layouts.layoutmaster')

@section('title', 'Create Order')

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendors/flatpickr/flatpickr.min.css')}}">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">Create New Order</h4>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
            @csrf

            <div class="row">
                <!-- Customer Information -->
                <div class="col-md-6 mb-4">
                    <h5>Customer Information</h5>
                    <div class="form-group mb-3">
                        <label for="user_id" class="form-label">Customer</label>
                        <select name="user_id" id="user_id" class="choices form-select @error('user_id') is-invalid @enderror" required>
                            <option value="">Select Customer</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="address_id" class="form-label">Shipping Address</label>
                        <div id="addressContainer">
                        <select name="address_id" id="address_id" class="form-select @error('address_id') is-invalid @enderror" required>
                            <option value="">Select Address</option>
                            <!-- Addresses will be loaded via AJAX -->
                        </select>
                        @error('address_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                            <!-- Address Info (will be shown when address is selected) -->
                            <div id="selectedAddressInfo" class="mt-2 p-3 border rounded" style="display: none;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1" id="selectedRecipientName"></h6>
                                        <p class="mb-1" id="selectedPhone"></p>
                                        <p class="mb-0" id="selectedFullAddress"></p>
                                </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="changeAddressBtn">
                                        <i class="bi bi-pencil"></i> Change
                                    </button>
                                </div>
                            </div>

                            <!-- No Address Message -->
                            <div id="noAddressMessage" class="alert alert-info mt-2" style="display: none;">
                                <i class="bi bi-info-circle"></i> This customer doesn't have any saved addresses.
                                <a href="{{ route('admin.addresses.create') }}" class="alert-link">Add new address</a>
                            </div>

                            <div class="mt-2">
                                <a href="{{ route('admin.addresses.create') }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="bi bi-plus"></i> Manage Addresses
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="status" class="form-label">Order Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ old('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="notes" class="form-label">Order Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Shipping Method -->
                    <div class="form-group mb-3">
                        <label for="shipping_method_id" class="form-label">Shipping Method</label>
                        <select name="shipping_method_id" id="shipping_method_id" class="form-select @error('shipping_method_id') is-invalid @enderror" required>
                            <option value="">Select Shipping Method</option>
                            <option value="1" data-cost="25000">JNE Regular (Rp 25.000)</option>
                            <option value="2" data-cost="35000">JNE Express (Rp 35.000)</option>
                            <option value="3" data-cost="20000">POS Regular (Rp 20.000)</option>
                            <option value="4" data-cost="30000">POS Express (Rp 30.000)</option>
                            <option value="5" data-cost="30000">TIKI Regular (Rp 30.000)</option>
                            <option value="6" data-cost="40000">TIKI Express (Rp 40.000)</option>
                        </select>
                        @error('shipping_method_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                </div>

                <!-- Order Items -->
                <div class="col-md-6 mb-4">
                    <h5>Order Items</h5>
                    <div id="orderItems">
                        <div class="order-item mb-3 p-3 border rounded" data-index="0">
                            <div class="d-flex justify-content-between mb-2">
                                <h6>Item #1</h6>
                                <button type="button" class="btn btn-sm btn-danger remove-item d-none">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Product</label>
                                <select name="items[0][product_id]" class="product-select form-select" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Variant</label>
                                <select name="items[0][product_combination_id]" class="combination-select form-select" required>
                                    <option value="">Select Variant</option>
                                    <!-- Combinations will be loaded via JS -->
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Price</label>
                                        <input type="text" class="form-control item-price" name="items[0][price]" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" class="form-control item-quantity" name="items[0][quantity]" min="1" value="1" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Subtotal</label>
                                        <input type="text" class="form-control item-subtotal" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <button type="button" class="btn btn-info" id="addItemBtn">
                            <i class="bi bi-plus"></i> Add Item
                        </button>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-12 mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Order Summary</h5>
                            </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Subtotal:</span>
                                                <span id="subtotal">Rp 0.00</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                                <span>Shipping Cost:</span>
                                                <span id="shipping_cost_display">Rp 0.00</span>
                                </div>
                            </div>
                                        <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>Total:</strong>
                                                <strong id="total">Rp 0.00</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-primary">Create Order</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/vendors/flatpickr/flatpickr.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle user selection and load addresses
        const userSelect = document.getElementById('user_id');
        const addressSelect = document.getElementById('address_id');
        const selectedAddressInfo = document.getElementById('selectedAddressInfo');
        const noAddressMessage = document.getElementById('noAddressMessage');
        const changeAddressBtn = document.getElementById('changeAddressBtn');

        userSelect.addEventListener('change', function() {
            const userId = this.value;
            if (!userId) {
                addressSelect.innerHTML = '<option value="">Select Address</option>';
                selectedAddressInfo.style.display = 'none';
                noAddressMessage.style.display = 'none';
                return;
            }

            // Show loading state
            addressSelect.disabled = true;
            addressSelect.innerHTML = '<option value="">Loading addresses...</option>';
            selectedAddressInfo.style.display = 'none';
            noAddressMessage.style.display = 'none';

            // Remove any existing error messages
            const existingError = document.querySelector('.alert-danger');
            if (existingError) {
                existingError.remove();
            }

            // Fetch user addresses
            fetch(`/admin/orders/get-user-addresses?user_id=${userId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    addressSelect.disabled = false;

                    if (!data.success) {
                        throw new Error(data.message || 'Failed to load addresses');
                    }

                    if (!data.addresses || data.addresses.length === 0) {
                        addressSelect.innerHTML = '<option value="">No addresses found</option>';
                        noAddressMessage.style.display = 'block';
                        return;
                    }

                    // Populate address select
                    addressSelect.innerHTML = '<option value="">Select Address</option>';
                        data.addresses.forEach(address => {
                        const option = document.createElement('option');
                        option.value = address.id;
                        option.textContent = `${address.recipient_name} - ${address.full_address}`;
                        addressSelect.appendChild(option);
                    });

                    // Show the address select
                    addressSelect.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    addressSelect.disabled = false;
                    addressSelect.innerHTML = '<option value="">Error loading addresses</option>';

                    // Show error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger mt-2';
                    errorDiv.textContent = error.message || 'Failed to load addresses. Please try again.';
                    addressSelect.parentNode.appendChild(errorDiv);

                    // Remove error message after 5 seconds
                    setTimeout(() => {
                        errorDiv.remove();
                    }, 5000);
                });
        });

        // Handle address selection
        addressSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (this.value) {
                selectedAddressInfo.style.display = 'block';
                selectedAddressInfo.querySelector('.recipient-name').textContent = selectedOption.textContent.split(' - ')[0];
                selectedAddressInfo.querySelector('.full-address').textContent = selectedOption.textContent.split(' - ')[1];
            } else {
                selectedAddressInfo.style.display = 'none';
            }
        });

        // Handle change address button
        changeAddressBtn.addEventListener('click', function() {
            selectedAddressInfo.style.display = 'none';
            addressSelect.style.display = 'block';
            addressSelect.focus();
        });

        // Handle shipping method selection
        const shippingMethodSelect = document.getElementById('shipping_method_id');
        const shippingCostInput = document.getElementById('shipping_cost');
        const shippingCostDisplay = document.getElementById('shipping_cost_display');

        shippingMethodSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const cost = selectedOption.dataset.cost || 0;

            // Update shipping cost input and display
            shippingCostInput.value = cost;
            shippingCostDisplay.textContent = 'Rp ' + parseInt(cost).toLocaleString('id-ID');

            // Recalculate totals
            calculateTotals();
        });

        // Function to calculate totals
        function calculateTotals() {
            let subtotal = 0;
            const rows = document.querySelectorAll('.order-item');

            rows.forEach(row => {
                const price = parseFloat(row.querySelector('.item-price').value.replace(/[^\d.-]/g, '')) || 0;
                const quantity = parseInt(row.querySelector('.item-quantity').value) || 0;
                const itemSubtotal = price * quantity;
                row.querySelector('.item-subtotal').value = 'Rp ' + itemSubtotal.toLocaleString('id-ID');
                subtotal += itemSubtotal;
            });

            // Get shipping cost
            const shippingCost = parseFloat(document.getElementById('shipping_cost').value) || 0;

            // Update displays
            document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('shipping_cost_display').textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
            document.getElementById('total').textContent = 'Rp ' + (subtotal + shippingCost).toLocaleString('id-ID');
        }

        // Format currency function
        function formatCurrency(number) {
            return 'Rp ' + number.toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Format number without currency symbol
        function formatNumber(number) {
            return number.toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Add event listeners for calculations
        document.querySelectorAll('.item-price, .item-quantity').forEach(input => {
            input.addEventListener('change', calculateTotals);
        });

        // Add event listeners for shipping method and address changes
        document.getElementById('shipping_method_id').addEventListener('change', calculateTotals);
        document.getElementById('address_id').addEventListener('change', calculateTotals);

        // Product and combination selection handling
        document.querySelectorAll('.product-select').forEach(select => {
            select.addEventListener('change', function() {
                const productId = this.value;
                const combinationSelect = this.closest('.order-item').querySelector('.combination-select');

                if (!productId) {
                combinationSelect.innerHTML = '<option value="">Select Variant</option>';
                    return;
                }

                combinationSelect.innerHTML = '<option value="">Loading variants...</option>';
                combinationSelect.disabled = true;

                fetch(`/admin/orders/get-product-combinations?product_id=${productId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        combinationSelect.disabled = false;
                                    combinationSelect.innerHTML = '<option value="">Select Variant</option>';

                        if (!data.success) {
                            throw new Error(data.message || 'Failed to load variants');
                        }

                        if (!data.combinations || data.combinations.length === 0) {
                            // If no combinations, create a default option
                            const option = document.createElement('option');
                            option.value = 'default';
                            option.textContent = 'Default Product';
                            option.dataset.price = data.base_price || 0;
                            combinationSelect.appendChild(option);
                            return;
                        }

                        data.combinations.forEach(combination => {
                            const option = document.createElement('option');
                            option.value = combination.id;
                            option.textContent = `${combination.name || combination.sku} - Rp ${formatNumber(combination.price)}`;
                            option.dataset.price = combination.price;
                            combinationSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        combinationSelect.innerHTML = '<option value="">Error loading variants</option>';
                        combinationSelect.disabled = false;

                        // Show error message
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'alert alert-danger mt-2';
                        errorDiv.textContent = error.message || 'Failed to load variants. Please try again.';
                        combinationSelect.parentNode.appendChild(errorDiv);

                        // Remove error message after 5 seconds
                        setTimeout(() => {
                            errorDiv.remove();
                        }, 5000);
                    });
            });
        });

        document.querySelectorAll('.combination-select').forEach(select => {
            select.addEventListener('change', function() {
                const price = this.selectedOptions[0]?.dataset.price || 0;
                const priceInput = this.closest('.order-item').querySelector('.item-price');
                priceInput.value = formatCurrency(price);
                calculateTotals();
            });
        });

        // Add new item
        document.getElementById('addItemBtn').addEventListener('click', function() {
            const itemsContainer = document.getElementById('orderItems');
            const itemCount = itemsContainer.children.length;
            const newItem = itemsContainer.children[0].cloneNode(true);

            // Update indices
            newItem.dataset.index = itemCount;
            newItem.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace('[0]', `[${itemCount}]`);
            });

            // Clear values
            newItem.querySelectorAll('select').forEach(select => select.value = '');
            newItem.querySelectorAll('input').forEach(input => input.value = '');

            // Show remove button
            newItem.querySelector('.remove-item').classList.remove('d-none');

            itemsContainer.appendChild(newItem);

            // Add event listeners to new item
            addItemEventListeners(newItem);
        });

        function addItemEventListeners(item) {
            const productSelect = item.querySelector('.product-select');
            const combinationSelect = item.querySelector('.combination-select');

            productSelect.addEventListener('change', function() {
                // ... (same as above product select handler)
            });

            combinationSelect.addEventListener('change', function() {
                // ... (same as above combination select handler)
            });

            item.querySelector('.item-quantity').addEventListener('change', calculateTotals);

            item.querySelector('.remove-item').addEventListener('click', function() {
                item.remove();
                calculateTotals();
            });
        }

        // Initialize first item
        addItemEventListeners(document.querySelector('.order-item'));

        // Initialize totals if there are items
        calculateTotals();
    });
</script>
@endsection