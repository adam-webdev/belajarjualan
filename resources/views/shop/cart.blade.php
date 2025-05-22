@extends('layouts.frontend.master')

@section('title', 'Shopping Cart - Apriori Shop')

@section('content')
<div class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
        </ol>
    </nav>

    <h1 class="h3 mb-4">Shopping Cart @if($cartItems->count() > 0)({{ $cartItems->sum('quantity') }} items)@endif</h1>

    <form id="checkout-form" action="{{ route('shop.cart.proceed-to-checkout') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-body">
                        @if($cartItems->count() > 0)
                        <!-- Cart Header (Mobile View) -->
                        <div class="d-lg-none mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllMobile">
                                    <label class="form-check-label" for="selectAllMobile">
                                        Select All ({{ $cartItems->count() }} items)
                                    </label>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="deleteSelectedMobile">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        </div>

                        <!-- Cart Header (Desktop View) -->
                        <div class="d-none d-lg-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll">
                                    Select All ({{ $cartItems->count() }} items)
                                </label>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="deleteSelected">
                                <i class="bi bi-trash"></i> Delete Selected
                            </button>
                        </div>

                        <!-- Cart Items List -->
                        @foreach($cartItems as $item)
                        <div class="cart-item mb-3 pb-3 border-bottom">
                            <div class="row align-items-center">
                                <!-- Selection & Product Info -->
                                <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input item-checkbox" type="checkbox"
                                                   name="selected_items[]"
                                                   value="{{ $item->id }}"
                                                   checked
                                                   data-item-id="{{ $item->id }}"
                                                   data-price="{{ $item->productCombination->price }}"
                                                   data-quantity="{{ $item->quantity }}">
                                        </div>

                                        @if($item->productCombination->product->images->count() > 0)
                                            <img src="{{ asset('storage/' . $item->productCombination->product->images->first()->image_path) }}"
                                                 class="rounded me-3" width="80" height="80"
                                                 alt="{{ $item->productCombination->product->name }}">
                                        @endif

                                        <div>
                                            <h6 class="mb-1">{{ $item->productCombination->product->name }}</h6>
                                            @if($item->productCombination->optionValues->count() > 0)
                                                <div class="small text-muted mb-1">
                                                    @foreach($item->productCombination->optionValues as $optionValue)
                                                        <span class="badge bg-light text-dark me-1">
                                                            {{ $optionValue->option->name }}: {{ $optionValue->value }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class="text-muted">
                                                Quantity: {{ $item->quantity }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price & Quantity -->
                                <div class="col-12 col-lg-6">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-lg-8">
                                            <div class="d-flex align-items-center">
                                                <div class="input-group input-group-sm" style="width: 120px;">
                                                    <button type="button" class="btn btn-outline-secondary decrease-quantity"
                                                            data-item-id="{{ $item->id }}">−</button>
                                                    <input type="number" class="form-control text-center item-quantity"
                                                           value="{{ $item->quantity }}" min="1"
                                                           data-item-id="{{ $item->id }}"
                                                           data-max-stock="{{ $item->productCombination->stock }}">
                                                    <button type="button" class="btn btn-outline-secondary increase-quantity"
                                                            data-item-id="{{ $item->id }}">+</button>
                                                </div>
                                                <div class="ms-3">
                                                    <div class="fw-bold">
                                                        Rp {{ number_format($item->productCombination->price * $item->quantity, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4 text-end mt-3 mt-lg-0">
                                            <button type="button" class="btn btn-link text-danger p-0 remove-item"
                                                    data-item-id="{{ $item->id }}">
                                                <small>Remove</small>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Checkout Button -->
                        @if($cartItems->count() > 0)
                            @if(Auth::check())
                                @if(Auth::user()->addresses->count() > 0)
                                    <button type="submit" class="btn btn-primary w-100" id="checkoutBtn">
                                        Proceed to Checkout
                                    </button>
                                @else
                                    <a href="{{ route('shop.profile') }}" class="btn btn-primary w-100">
                                        Add Shipping Address
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                    Login to Checkout
                                </a>
                            @endif
                        @else
                            <button class="btn btn-primary w-100" disabled>
                                Proceed to Checkout
                            </button>
                        @endif

                        <!-- Continue Shopping -->
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Continue Shopping
                        </a>
                        @else
                        <div class="text-center py-3">
                            <p class="text-muted">No items in cart</p>
                        </div>
                        <a href="{{ url('/') }}" class="btn btn-primary w-100">
                            Start Shopping
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5 mb-4">Order Summary</h2>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Selected Items</span>
                                <span id="selected-items-count">{{ $cartItems->count() }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span id="subtotal-amount">Rp {{ number_format($cart->subtotal ?? 0, 0, ',', '.') }}</span>
                            </div>

                            @if($cart->discount_amount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Discount</span>
                                    <span>- Rp {{ number_format($cart->discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <hr>

                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span id="totalAmount">Rp {{ number_format($cart->total ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Recently Viewed Products -->
    @if($recentProducts && $recentProducts->count() > 0)
    <section class="recently-viewed mt-5">
        <h3 class="section-title mb-4">Recently Viewed</h3>
        <div class="row">
            @foreach($recentProducts as $product)
            <div class="col-6 col-md-3 mb-4">
                <div class="product-card">
                    <div class="position-relative">
                        @if($product->images->count() > 0)
                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                        @else
                            <img src="https://via.placeholder.com/300x300?text={{ $product->name }}" class="card-img-top product-img" alt="{{ $product->name }}">
                        @endif
                        <form method="POST" action="{{ route('shop.wishlist.add') }}" class="wishlist-form">
                            @csrf
                            <input type="hidden" name="product_combination_id" value="{{ $product->combinations->first()->id ?? 0 }}">
                            <button type="button" class="btn btn-sm position-absolute top-0 end-0 m-2 bg-white rounded-circle p-2 wishlist-btn">
                                <i class="bi bi-heart"></i>
                            </button>
                        </form>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('shop.product', $product->slug) }}" class="text-decoration-none text-dark">
                            <h5 class="product-title">{{ $product->name }}</h5>
                        </a>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="product-price mb-0">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                            <form method="POST" action="{{ route('shop.cart.add') }}" class="add-to-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="combination_id" value="{{ $product->combinations->first()->id ?? 0 }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="button" class="btn btn-sm btn-primary add-to-cart-btn"><i class="bi bi-cart-plus"></i></button>
                            </form>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($product->reviews->avg('rating') ?? 0))
                                        ⭐
                                    @else
                                        ☆
                                    @endif
                                @endfor
                                ({{ $product->reviews->count() }})
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

@section('js')
<script>
$(document).ready(function() {
    // Initialize variables
    let discount = {{ $cart->discount_amount ?? 0 }};

    // Handle quantity changes
    $('.decrease-quantity').on('click', function() {
        const itemId = $(this).data('item-id');
        const input = $(`.item-quantity[data-item-id="${itemId}"]`);
        const currentVal = parseInt(input.val());
        const price = parseFloat($(`.item-checkbox[data-item-id="${itemId}"]`).data('price'));

        if (currentVal > 1) {
            const newVal = currentVal - 1;
            input.val(newVal);

            // Update item total immediately
            const itemTotal = price * newVal;
            $(this).closest('.cart-item').find('.fw-bold').text('Rp ' + itemTotal.toLocaleString('id-ID'));

            // Update in database
            updateCartItemQuantity(itemId, newVal);
        }
    });

    $('.increase-quantity').on('click', function() {
        const itemId = $(this).data('item-id');
        const input = $(`.item-quantity[data-item-id="${itemId}"]`);
        const maxStock = parseInt(input.data('max-stock'));
        const currentVal = parseInt(input.val());
        const price = parseFloat($(`.item-checkbox[data-item-id="${itemId}"]`).data('price'));

        if (currentVal < maxStock) {
            const newVal = currentVal + 1;
            input.val(newVal);

            // Update item total immediately
            const itemTotal = price * newVal;
            $(this).closest('.cart-item').find('.fw-bold').text('Rp ' + itemTotal.toLocaleString('id-ID'));

            // Update in database
            updateCartItemQuantity(itemId, newVal);
        } else {
            alert('Stok tidak mencukupi');
        }
    });

    // Handle manual quantity input
    $('.item-quantity').on('change', function() {
        const itemId = $(this).data('item-id');
        const maxStock = parseInt($(this).data('max-stock'));
        const newVal = parseInt($(this).val());
        const price = parseFloat($(`.item-checkbox[data-item-id="${itemId}"]`).data('price'));

        if (newVal < 1) {
            $(this).val(1);
            return;
        }

        if (newVal > maxStock) {
            $(this).val(maxStock);
            alert('Stok tidak mencukupi');
            return;
        }

        // Update item total immediately
        const itemTotal = price * newVal;
        $(this).closest('.cart-item').find('.fw-bold').text('Rp ' + itemTotal.toLocaleString('id-ID'));

        // Update in database
        updateCartItemQuantity(itemId, newVal);
    });

    // Update cart item quantity
    function updateCartItemQuantity(itemId, quantity) {
        $.ajax({
            url: '{{ route("shop.cart.update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                cart_item_id: itemId,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    // Update item data attributes
                    $(`.item-checkbox[data-item-id="${itemId}"]`).data('quantity', quantity);
                    // Update order summary
                    updateOrderSummary();
                    // Update cart badge
                    updateCartBadge();
                } else {
                    alert(response.message || 'Gagal mengupdate quantity');
                }
            },
            error: function(xhr) {
                alert('Gagal mengupdate quantity');
            }
        });
    }

    // Function to update order summary
    function updateOrderSummary() {
        let subtotal = 0;
        let itemCount = 0;

        // Loop through all checked items
        $('.item-checkbox:checked').each(function() {
            const price = parseFloat($(this).data('price'));
            const quantity = parseInt($(this).data('quantity'));
            const itemTotal = price * quantity;

            // Add to subtotal
            subtotal += itemTotal;
            itemCount++;
        });

        // Update selected items count
        $('#selected-items-count').text(itemCount);

        // Update subtotal display
        $('#subtotal-amount').text('Rp ' + subtotal.toLocaleString('id-ID'));

        // Calculate and update total
        const total = subtotal - discount;
        $('#totalAmount').text('Rp ' + total.toLocaleString('id-ID'));

        // Enable/disable checkout button
        $('#checkoutBtn').prop('disabled', itemCount === 0);
    }

    // Function to update cart badge
    function updateCartBadge() {
        let totalItems = 0;
        $('.item-quantity').each(function() {
            totalItems += parseInt($(this).val());
        });
        $('.cart-badge').text(totalItems);
    }

    // Handle remove item
    $('.remove-item').on('click', function() {
        const itemId = $(this).data('item-id');
        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            $.ajax({
                url: '{{ route("shop.cart.remove") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    cart_item_id: itemId
                },
                success: function(response) {
                    if (response.success) {
                        // Remove item from DOM
                        $(`.cart-item[data-item-id="${itemId}"]`).remove();
                        // Update order summary
                        updateOrderSummary();
                        // Update cart badge
                        updateCartBadge();
                        // Show success message
                        alert('Item berhasil dihapus');
                    } else {
                        alert(response.message || 'Gagal menghapus item');
                    }
                },
                error: function(xhr) {
                    alert('Gagal menghapus item');
                }
            });
        }
    });

    // Handle select all checkbox
    $('#selectAll, #selectAllMobile').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.item-checkbox').prop('checked', isChecked);
        updateOrderSummary();
    });

    // Handle individual item checkbox
    $('.item-checkbox').on('change', function() {
        updateOrderSummary();
    });

    // Handle delete selected items
    $('#deleteSelected, #deleteSelectedMobile').on('click', function() {
        const selectedItems = $('.item-checkbox:checked').map(function() {
            return $(this).data('item-id');
        }).get();

        if (selectedItems.length === 0) {
            alert('Pilih item yang ingin dihapus');
            return;
        }

        if (confirm('Apakah Anda yakin ingin menghapus item yang dipilih?')) {
            $.ajax({
                url: '{{ route("shop.cart.remove") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    cart_item_ids: selectedItems
                },
                success: function(response) {
                    if (response.success) {
                        // Remove items from DOM
                        selectedItems.forEach(function(itemId) {
                            $(`.cart-item[data-item-id="${itemId}"]`).remove();
                        });
                        // Update order summary
                        updateOrderSummary();
                        // Update cart badge
                        updateCartBadge();
                        // Show success message
                        alert('Item berhasil dihapus');
                    } else {
                        alert(response.message || 'Gagal menghapus item');
                    }
                },
                error: function(xhr) {
                    alert('Gagal menghapus item');
                }
            });
        }
    });

    // Handle form submission
    $('#checkout-form').on('submit', function(e) {
        e.preventDefault();

        const selectedItems = $('.item-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedItems.length === 0) {
            alert('Please select at least one item to checkout');
            return;
        }

        // Submit the form
        this.submit();
    });

    // Initialize order summary
    updateOrderSummary();
});
</script>
@endsection

@endsection