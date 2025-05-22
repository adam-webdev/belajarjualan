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

                    <!-- Payment Method Section -->
                    <div class="form-group mb-3">
                        <label for="payment_type" class="form-label">Payment Method</label>
                        <select name="payment_type" id="payment_type" class="form-select @error('payment_type') is-invalid @enderror" required>
                            <option value="">Select Payment Method</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="e_wallet">E-Wallet</option>
                        </select>
                        @error('payment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bank Transfer Options -->
                    <div id="bankTransferOptions" class="form-group mb-3" style="display: none;">
                        <label for="bank_name" class="form-label">Select Bank</label>
                        <select name="bank_name" id="bank_name" class="form-select">
                            <option value="">Select Bank</option>
                            <option value="mandiri">Mandiri</option>
                            <option value="bca">BCA</option>
                            <option value="bri">BRI</option>
                            <option value="bni">BNI</option>
                            <option value="permata">Permata</option>
                        </select>
                        <small class="text-muted mt-2 d-block">
                            Transfer to: 1111 2222 3333<br>
                            Account Name: THRIFT SHOP
                        </small>
                    </div>

                    <!-- E-Wallet Options -->
                    <div id="eWalletOptions" class="form-group mb-3" style="display: none;">
                        <label for="e_wallet_name" class="form-label">Select E-Wallet</label>
                        <select name="e_wallet_name" id="e_wallet_name" class="form-select">
                            <option value="">Select E-Wallet</option>
                            <option value="dana">DANA</option>
                            <option value="ovo">OVO</option>
                            <option value="gopay">GoPay</option>
                            <option value="shopeepay">ShopeePay</option>
                            <option value="linkaja">LinkAja</option>
                        </select>
                        <small class="text-muted mt-2 d-block">
                            E-Wallet Number: 0888 0999 0000<br>
                            Account Name: THRIFT SHOP
                        </small>
                    </div>

                    <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                </div>

                <!-- Order Items -->
                <div class="col-md-6 mb-4">
                    <h5>Order Items</h5>
                    <div id="orderItems">
                        <div class="order-item mb-3 p-3 border rounded">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
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
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Variant</label>
                                        <select name="items[0][product_combination_id]" class="combination-select form-select" required>
                                            <option value="">Select Variant</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" class="form-control item-quantity" name="items[0][quantity]" min="1" value="1" required>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">Price</label>
                                        <input type="text" class="form-control item-price" name="items[0][price]" readonly>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger remove-item d-none">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
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

        // Handle product selection and variant loading
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select')) {
                const productId = e.target.value;
                const orderItem = e.target.closest('.order-item');
                const combinationSelect = orderItem.querySelector('.combination-select');
                const priceInput = orderItem.querySelector('.item-price');
                const quantityInput = orderItem.querySelector('.item-quantity');
                const subtotalInput = orderItem.querySelector('.item-subtotal');

                // Reset values
                combinationSelect.innerHTML = '<option value="">Select Variant</option>';
                priceInput.value = '';
                quantityInput.value = '1';
                quantityInput.max = '';
                subtotalInput.value = '';

                if (!productId) {
                    calculateTotals();
                    return;
                }

                // Show loading state
                combinationSelect.innerHTML = '<option value="">Loading variants...</option>';
                combinationSelect.disabled = true;

                // Fetch variants
                fetch(`/admin/orders/get-product-combinations?product_id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        combinationSelect.disabled = false;
                        combinationSelect.innerHTML = '<option value="">Select Variant</option>';

                        if (data.success && data.combinations) {
                            // Sort combinations by name
                            data.combinations.sort((a, b) => a.name.localeCompare(b.name));

                            data.combinations.forEach(combination => {
                                const option = document.createElement('option');
                                option.value = combination.id;
                                option.textContent = `${combination.name} - Rp ${formatNumber(combination.price)}`;
                                option.dataset.price = combination.price;
                                option.dataset.stock = combination.stock;
                                combinationSelect.appendChild(option);
                            });

                            // Auto-select if only one combination
                            if (data.combinations.length === 1) {
                                combinationSelect.value = data.combinations[0].id;
                                const event = new Event('change');
                                combinationSelect.dispatchEvent(event);
                            }
                        }
                        calculateTotals();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        combinationSelect.innerHTML = '<option value="">Error loading variants</option>';
                        combinationSelect.disabled = false;
                        calculateTotals();
                    });
            }
        });

        // Handle variant selection
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('combination-select')) {
                const orderItem = e.target.closest('.order-item');
                const selectedOption = e.target.selectedOptions[0];
                const priceInput = orderItem.querySelector('.item-price');
                const quantityInput = orderItem.querySelector('.item-quantity');
                const subtotalInput = orderItem.querySelector('.item-subtotal');

                if (!selectedOption) {
                    priceInput.value = '';
                    quantityInput.value = '1';
                    quantityInput.max = '';
                    subtotalInput.value = '';
                    calculateTotals();
                    return;
                }

                const price = selectedOption.dataset.price || 0;
                const stock = selectedOption.dataset.stock || 0;

                // Update price input
                priceInput.value = formatCurrency(price);

                // Update quantity max and value
                quantityInput.max = stock;
                if (parseInt(quantityInput.value) > stock) {
                    quantityInput.value = stock;
                }

                // Calculate and update subtotal
                const quantity = parseInt(quantityInput.value) || 0;
                const subtotal = price * quantity;
                subtotalInput.value = formatCurrency(subtotal);

                // Trigger totals calculation
                calculateTotals();
            }
        });

        // Handle quantity changes
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('item-quantity')) {
                const orderItem = e.target.closest('.order-item');
                const priceInput = orderItem.querySelector('.item-price');
                const subtotalInput = orderItem.querySelector('.item-subtotal');
                const price = parseFloat(priceInput.value.replace(/[^\d.-]/g, '')) || 0;
                const quantity = parseInt(e.target.value) || 0;
                const subtotal = price * quantity;

                // Update subtotal
                subtotalInput.value = formatCurrency(subtotal);

                // Recalculate totals
                calculateTotals();
            }
        });

        // Add new item row
        document.getElementById('addItemBtn').addEventListener('click', function() {
            const itemContainer = document.getElementById('orderItems');
            const itemCount = itemContainer.children.length;
            const newItem = document.createElement('div');
            newItem.className = 'order-item mb-3 p-3 border rounded';
            newItem.innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Product</label>
                            <select class="form-select product-select" name="items[${itemCount}][product_id]" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Variant</label>
                            <select class="form-select combination-select" name="items[${itemCount}][product_combination_id]" required>
                                <option value="">Select Variant</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control item-quantity" name="items[${itemCount}][quantity]" value="1" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label">Price</label>
                            <input type="text" class="form-control item-price" name="items[${itemCount}][price]" readonly>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-danger remove-item">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Subtotal</label>
                            <input type="text" class="form-control item-subtotal" readonly>
                        </div>
                    </div>
                </div>
            `;

            itemContainer.appendChild(newItem);

            // Add event listeners to new item
            const newProductSelect = newItem.querySelector('.product-select');
            const newCombinationSelect = newItem.querySelector('.combination-select');
            const newQuantityInput = newItem.querySelector('.item-quantity');
            const newPriceInput = newItem.querySelector('.item-price');

            // Product select event
            newProductSelect.addEventListener('change', function() {
                const productId = this.value;
                newCombinationSelect.innerHTML = '<option value="">Loading variants...</option>';
                newCombinationSelect.disabled = true;

                if (!productId) {
                    newCombinationSelect.innerHTML = '<option value="">Select Variant</option>';
                    newCombinationSelect.disabled = false;
                    return;
                }

                fetch(`/admin/orders/get-product-combinations?product_id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        newCombinationSelect.disabled = false;
                        newCombinationSelect.innerHTML = '<option value="">Select Variant</option>';

                        if (data.success && data.combinations) {
                            data.combinations.sort((a, b) => a.name.localeCompare(b.name));
                            data.combinations.forEach(combination => {
                                const option = document.createElement('option');
                                option.value = combination.id;
                                option.textContent = `${combination.name} - Rp ${formatNumber(combination.price)}`;
                                option.dataset.price = combination.price;
                                option.dataset.stock = combination.stock;
                                newCombinationSelect.appendChild(option);
                            });

                            // Auto-select if only one combination
                            if (data.combinations.length === 1) {
                                newCombinationSelect.value = data.combinations[0].id;
                                const event = new Event('change');
                                newCombinationSelect.dispatchEvent(event);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        newCombinationSelect.innerHTML = '<option value="">Error loading variants</option>';
                        newCombinationSelect.disabled = false;
                    });
            });

            // Combination select event
            newCombinationSelect.addEventListener('change', function() {
                const selectedOption = this.selectedOptions[0];
                const price = selectedOption?.dataset.price || 0;
                const stock = selectedOption?.dataset.stock || 0;

                newPriceInput.value = formatCurrency(price);
                newQuantityInput.max = stock;
                if (parseInt(newQuantityInput.value) > stock) {
                    newQuantityInput.value = stock;
                }

                calculateTotals();
            });

            // Remove item button
            newItem.querySelector('.remove-item').addEventListener('click', function() {
                newItem.remove();
                calculateTotals();
            });
        });

        // Function to calculate totals
        function calculateTotals() {
            let subtotal = 0;
            const rows = document.querySelectorAll('.order-item');

            rows.forEach(row => {
                const priceInput = row.querySelector('.item-price');
                const quantityInput = row.querySelector('.item-quantity');
                const subtotalInput = row.querySelector('.item-subtotal');

                const price = parseFloat(priceInput.value.replace(/[^\d.-]/g, '')) || 0;
                const quantity = parseInt(quantityInput.value) || 0;
                const itemSubtotal = price * quantity;

                // Update item subtotal
                subtotalInput.value = formatCurrency(itemSubtotal);
                subtotal += itemSubtotal;
            });

            // Get shipping cost
            const shippingCost = parseFloat(document.getElementById('shipping_cost').value) || 0;

            // Update displays
            document.getElementById('subtotal').textContent = formatCurrency(subtotal);
            document.getElementById('shipping_cost_display').textContent = formatCurrency(shippingCost);
            document.getElementById('total').textContent = formatCurrency(subtotal + shippingCost);
        }

        // Format currency function
        function formatCurrency(number) {
            return 'Rp ' + number.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        // Format number without currency symbol
        function formatNumber(number) {
            return number.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        // Initialize calculations for existing items
        calculateTotals();

        // Handle payment method selection
        const paymentTypeSelect = document.getElementById('payment_type');
        const bankTransferOptions = document.getElementById('bankTransferOptions');
        const eWalletOptions = document.getElementById('eWalletOptions');
        const bankNameSelect = document.getElementById('bank_name');
        const eWalletNameSelect = document.getElementById('e_wallet_name');

        paymentTypeSelect.addEventListener('change', function() {
            const selectedValue = this.value;

            // Hide all options first
            bankTransferOptions.style.display = 'none';
            eWalletOptions.style.display = 'none';

            // Show selected option
            if (selectedValue === 'bank_transfer') {
                bankTransferOptions.style.display = 'block';
                eWalletNameSelect.value = ''; // Clear e-wallet selection
            } else if (selectedValue === 'e_wallet') {
                eWalletOptions.style.display = 'block';
                bankNameSelect.value = ''; // Clear bank selection
            }
        });
    });
</script>
@endsection