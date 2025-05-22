@extends('layouts.frontend.master')

@section('title', $product->name . ' - Apriori Shop')

@section('content')
<div class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Detail -->
    <div class="row">
        <!-- Product Images -->
        <div class="col-md-5 mb-4">
            <div class="product-images">
                <!-- Main Image -->
                <div class="main-image mb-3" style="width: 100%; height: 400px; overflow: hidden; border-radius: 8px;">
                    @if($product->images->count() > 0)
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                             class="img-fluid w-100 h-100 object-fit-contain"
                             id="mainImage"
                             alt="{{ $product->name }}"
                             style="object-fit: contain;">
                    @else
                        <img src="https://via.placeholder.com/600x600?text={{ $product->name }}"
                             class="img-fluid w-100 h-100 object-fit-contain"
                             id="mainImage"
                             alt="{{ $product->name }}"
                             style="object-fit: contain;">
                    @endif
                </div>

                <!-- Thumbnail Images Carousel -->
                @if($product->images->count() > 1)
                <div class="thumbnail-carousel position-relative">
                    <div class="thumbnail-images d-flex overflow-hidden" style="max-width: 100%;">
                        @foreach($product->images as $index => $image)
                        <div class="thumbnail-image me-2 {{ $index === 0 ? 'active' : '' }}"
                             onclick="changeImage('{{ asset('storage/' . $image->image_path) }}')"
                             style="min-width: 80px; height: 80px; cursor: pointer; border: 2px solid #ddd; border-radius: 8px; overflow: hidden; transition: all 0.3s ease;">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                 class="img-fluid h-100 w-100 object-fit-contain"
                                 alt="{{ $product->name }} {{ $index + 1 }}"
                                 style="object-fit: contain;">
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" onclick="scrollThumbnails('prev')" style="left: -20px;">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" onclick="scrollThumbnails('next')" style="right: -20px;">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-7">
            <div class="product-info bg-white p-4 rounded">
                <h1 class="h3 mb-2">{{ $product->name }}</h1>

                <!-- Price -->
                <div class="product-price mb-3" id="product-price-display">
                    @if($flashSaleItem)
                        <span class="fw-bold fs-4">Rp {{ number_format($flashSaleItem->sale_price ?? 0, 0, ',', '.') }}</span>
                        <span class="text-decoration-line-through text-muted ms-2">Rp {{ number_format($defaultCombination->price ?? 0, 0, ',', '.') }}</span>
                        <span class="badge bg-danger ms-2">{{ round(((($defaultCombination->price ?? 0) - ($flashSaleItem->sale_price ?? 0)) / ($defaultCombination->price ?? 1)) * 100) }}% OFF</span>
                    @else
                        <span class="fw-bold fs-4">Rp {{ number_format($defaultCombination->price ?? $product->base_price ?? 0, 0, ',', '.') }}</span>
                        @if($product->sale_price && $product->sale_price < $product->base_price)
                            <span class="text-decoration-line-through text-muted ms-2">Rp {{ number_format($product->base_price ?? 0, 0, ',', '.') }}</span>
                            <span class="badge bg-danger ms-2">{{ round(((($product->base_price ?? 0) - ($product->sale_price ?? 0)) / ($product->base_price ?? 1)) * 100) }}% OFF</span>
                        @endif
                    @endif
                </div>

                <!-- Short Description -->
                <div class="product-description mb-4">
                    <p>{{ $product->short_description }}</p>
                </div>

                <form action="{{ route('shop.cart.add') }}" method="POST" id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <!-- Product Variants -->
                    @if($product->has_variant && count($productOptions) > 0)
                    <input type="hidden" name="combination_id" id="selected_combination_id" value="{{ $defaultCombination->id ?? 0 }}">
                    <div class="product-variants mb-4">
                        @foreach($productOptions as $optionId => $option)
                        <div class="variant-group mb-3" data-option-id="{{ $optionId }}">
                            <label class="fw-bold mb-2">{{ $option['name'] }}:</label>
                            <div class="d-flex flex-wrap">
                                @foreach($option['values'] as $valueId => $value)
                                <div class="variant-item {{ $option['name'] == 'Color' ? 'color-variant' : 'size-variant' }} me-2 mb-2"
                                     data-option-id="{{ $optionId }}"
                                     data-value-id="{{ $valueId }}"
                                     data-value="{{ $value }}"
                                     style="
                                        width: 40px;
                                        height: 40px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        border: 2px solid #ddd;
                                        border-radius: 8px;
                                        cursor: pointer;
                                        transition: all 0.3s ease;
                                        {{ $option['name'] == 'Color' ? 'background-color: ' . strtolower($value) . ';' : '' }}
                                     "
                                     @if($option['name'] == 'Color')
                                     title="{{ $value }}"
                                     @endif>
                                    @if($option['name'] != 'Color')
                                        {{ $value }}
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <!-- Product without variants -->
                    <input type="hidden" name="combination_id" id="selected_combination_id" value="{{ $defaultCombination->id ?? 0 }}">
                    @endif

                    <!-- Stock Info -->
                    <div class="stock-info mb-3">
                        <div class="alert alert-info" id="stock-alert" style="display: none;">
                            <i class="bi bi-info-circle me-2"></i>
                            <span id="stock-alert-text"></span>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="quantity-container mb-4">
                        <label class="fw-bold mb-2">Quantity:</label>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-outline-secondary" id="decreaseQuantity">−</button>
                            <input type="number" class="form-control mx-2 text-center" name="quantity" value="1" min="1" id="quantity" style="width: 70px;">
                            <button type="button" class="btn btn-outline-secondary" id="increaseQuantity">+</button>
                            <span class="ms-3 text-muted" id="stock-info">{{ $defaultCombination->stock ?? 0 }} items available</span>
                        </div>
                    </div>

                    <!-- Add to Cart -->
                    <div class="d-flex mb-4">
                        <button type="button" id="add-to-cart-button" class="btn btn-primary me-2 flex-grow-1">
                            <i class="bi bi-cart-plus me-2"></i> Add to Cart
                        </button>
                    </div>
                </form>

                <!-- Purchase Protection -->
                <div class="purchase-protection p-3 bg-light rounded mb-4">
                    <div class="d-flex">
                        <i class="bi bi-shield-check text-primary me-2 fs-4"></i>
                        <div>
                            <h6 class="mb-1">Purchase Protection</h6>
                            <p class="mb-0 small">Shop with confidence. Get refund if item is not as described or if delivery is delayed.</p>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="product-details">
                    <h5>Product Details</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> SKU: <span id="product-sku">{{ $defaultCombination->sku ?? 'N/A' }}</span></li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Category: {{ $product->category->name ?? 'Uncategorized' }}</li>
                        @if(isset($product->brand) && $product->brand)
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Brand: {{ $product->brand }}</li>
                        @endif
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Free shipping on orders over Rp 200,000</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Tabs -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Description</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specification-tab" data-bs-toggle="tab" data-bs-target="#specification" type="button" role="tab">Specifications</button>
                </li>
            </ul>
            <div class="tab-content bg-white p-4 border border-top-0 rounded-bottom" id="productTabsContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <h5>Product Description</h5>
                    {!! $product->description !!}
                </div>
                <div class="tab-pane fade" id="specification" role="tabpanel">
                    <h5>Product Specifications</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                @if($product->specifications)
                                    @foreach(json_decode($product->specifications, true) ?? [] as $key => $value)
                                    <tr>
                                        <th width="30%">{{ $key }}</th>
                                        <td>{{ $value }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="text-center">No specifications available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="related-products mt-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-6 col-md-3 mb-4">
                <div class="product-card">
                    <div class="position-relative">
                        <a href="{{ route('shop.product', $relatedProduct->slug) }}">
                            @if($relatedProduct->images->count() > 0)
                                <img src="{{ asset('storage/' . $relatedProduct->images->first()->path) }}" class="card-img-top product-img" alt="{{ $relatedProduct->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x300?text={{ $relatedProduct->name }}" class="card-img-top product-img" alt="{{ $relatedProduct->name }}">
                            @endif
                        </a>
                        @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->base_price)
                            <span class="discount-badge">-{{ round((($relatedProduct->base_price - $relatedProduct->sale_price) / $relatedProduct->base_price) * 100) }}%</span>
                        @endif
                        <form method="POST" action="{{ route('shop.wishlist.add') }}" class="d-inline wishlist-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                            <button type="button" class="btn btn-sm position-absolute top-0 end-0 m-2 bg-white rounded-circle p-2 wishlist-btn">
                                <i class="bi bi-heart"></i>
                            </button>
                        </form>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('shop.product', $relatedProduct->slug) }}" class="text-decoration-none text-dark">
                            <h5 class="product-title">{{ $relatedProduct->name }}</h5>
                        </a>
                        <div class="d-flex justify-content-between align-items-center">
                            @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->base_price)
                            <div>
                                <p class="product-price mb-0">Rp {{ number_format($relatedProduct->sale_price, 0, ',', '.') }}</p>
                                <small class="text-decoration-line-through text-muted">Rp {{ number_format($relatedProduct->base_price, 0, ',', '.') }}</small>
                            </div>
                            @else
                            <p class="product-price mb-0">Rp {{ number_format($relatedProduct->base_price, 0, ',', '.') }}</p>
                            @endif
                            <form method="POST" action="{{ route('shop.cart.add') }}" class="d-inline cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="button" class="btn btn-sm btn-primary add-to-cart-btn"><i class="bi bi-cart-plus"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection

@section('js')
<script>
    // Store selected option values
    let selectedOptionValues = {};
    const combinationsMap = {!! $combinationsMapJson !!};

    // Handle variant selection
    document.querySelectorAll('.variant-item').forEach(item => {
        item.addEventListener('click', function() {
            const optionId = this.getAttribute('data-option-id');
            const valueId = this.getAttribute('data-value-id');
            const value = this.getAttribute('data-value');

            // Remove active class from same option items
            document.querySelectorAll(`.variant-item[data-option-id="${optionId}"]`).forEach(el => {
                el.classList.remove('active');
                el.style.borderColor = '#ddd';
            });

            // Add active class to selected item
            this.classList.add('active');
            this.style.borderColor = '#007bff';

            // Update selected options
            selectedOptionValues[optionId] = {
                id: valueId,
                value: value
            };

            // Check if all options are selected
            updateProductVariant();
        });
    });

    // Update product information based on selected variant
    function updateProductVariant() {
        // Get all option groups to check if all are selected
        const optionGroups = document.querySelectorAll('.variant-group');
        let allSelected = true;

        // Check if all options are selected
        optionGroups.forEach(group => {
            const optionId = group.getAttribute('data-option-id');
            if (!selectedOptionValues[optionId]) {
                allSelected = false;
            }
        });

        if (allSelected) {
            // Create the key for the combinations map
            const selectedValues = Object.values(selectedOptionValues).map(v => v.id).sort();
            const key = selectedValues.join('-');

            if (combinationsMap[key]) {
                const combination = combinationsMap[key];

                // Update price display
                const priceDisplay = document.getElementById('product-price-display');
                priceDisplay.innerHTML = `<span class="fw-bold fs-4">Rp ${new Intl.NumberFormat('id-ID').format(combination.price)}</span>`;

                // Update stock info
                const stockInfo = document.getElementById('stock-info');
                stockInfo.textContent = `${combination.stock} items available`;

                // Show stock alert if stock is low
                const stockAlert = document.getElementById('stock-alert');
                const stockAlertText = document.getElementById('stock-alert-text');

                if (combination.stock <= 5) {
                    stockAlert.style.display = 'block';
                    stockAlertText.textContent = `Hanya tersisa ${combination.stock} item untuk kombinasi ini!`;
                } else {
                    stockAlert.style.display = 'none';
                }

                // Update SKU
                document.getElementById('product-sku').textContent = combination.sku;

                // Update form combination ID
                document.getElementById('selected_combination_id').value = combination.id;

                // Update quantity max if needed
                const quantityInput = document.getElementById('quantity');
                if (parseInt(quantityInput.value) > combination.stock) {
                    quantityInput.value = combination.stock;
                }
                quantityInput.max = combination.stock;
            }
        }
    }

    // Handle add to cart button click
    document.getElementById('add-to-cart-button').addEventListener('click', function(e) {
        e.preventDefault();

        // Check if all options are selected
        const optionGroups = document.querySelectorAll('.variant-group');
        let allSelected = true;

        optionGroups.forEach(group => {
            const optionId = group.getAttribute('data-option-id');
            if (!selectedOptionValues[optionId]) {
                allSelected = false;
            }
        });

        if (!allSelected) {
            alert('Please select all options before adding to cart');
            return;
        }

        // Get the selected combination ID
        const combinationId = document.getElementById('selected_combination_id').value;
        if (!combinationId) {
            alert('Please select a valid combination');
            return;
        }

        // Get the form data
        const form = document.getElementById('add-to-cart-form');
        const formData = new FormData(form);

        // Send AJAX request
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Update stock info after successful add to cart
                updateProductVariant();
                // Optionally update cart count in header
                if (data.cart && data.cart.items_quantity) {
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart.items_quantity;
                    }
                }
            } else {
                alert(data.message || 'Failed to add item to cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to add item to cart');
        });
    });

    // Handle quantity buttons
    document.getElementById('decreaseQuantity').addEventListener('click', function() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    });

    document.getElementById('increaseQuantity').addEventListener('click', function() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        const maxStock = parseInt(quantityInput.max);

        if (currentValue < maxStock) {
            quantityInput.value = currentValue + 1;

            // Show alert if only a few items left
            if (maxStock - (currentValue + 1) <= 5) {
                const stockAlert = document.getElementById('stock-alert');
                const stockAlertText = document.getElementById('stock-alert-text');
                stockAlert.style.display = 'block';
                stockAlertText.textContent = `Hanya tersisa ${maxStock - (currentValue + 1)} item lagi!`;
            }
        } else {
            alert('Stok tidak mencukupi');
        }
    });

    // Handle quantity input change
    document.getElementById('quantity').addEventListener('change', function() {
        const currentValue = parseInt(this.value);
        const maxStock = parseInt(this.max);

        if (currentValue > maxStock) {
            this.value = maxStock;
            alert('Stok tidak mencukupi');
        }

        // Show alert if only a few items left
        if (maxStock - currentValue <= 5) {
            const stockAlert = document.getElementById('stock-alert');
            const stockAlertText = document.getElementById('stock-alert-text');
            stockAlert.style.display = 'block';
            stockAlertText.textContent = `Hanya tersisa ${maxStock - currentValue} item lagi!`;
        }
    });

    // Initialize variant selection on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Trigger click on first option value in each group
        document.querySelectorAll('.variant-group').forEach(group => {
            const firstOption = group.querySelector('.variant-item');
            if (firstOption) {
                firstOption.click();
            }
        });
    });

    // Thumbnail carousel functionality
    function scrollThumbnails(direction) {
        const container = document.querySelector('.thumbnail-images');
        const scrollAmount = 320; // Adjust based on your needs

        if (direction === 'next') {
            container.scrollLeft += scrollAmount;
        } else {
            container.scrollLeft -= scrollAmount;
        }
    }

    // Image gallery functionality
    function changeImage(src) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumbnail-image').forEach(thumb => {
            thumb.classList.remove('active');
            thumb.style.borderColor = '#ddd';
        });
        event.currentTarget.classList.add('active');
        event.currentTarget.style.borderColor = '#007bff';
    }
</script>

<style>
    .thumbnail-carousel {
        position: relative;
        padding: 0 30px;
    }

    .thumbnail-carousel .carousel-control-prev,
    .thumbnail-carousel .carousel-control-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 2;
    }

    .thumbnail-carousel .carousel-control-prev-icon,
    .thumbnail-carousel .carousel-control-next-icon {
        width: 15px;
        height: 15px;
    }

    .thumbnail-images {
        scroll-behavior: smooth;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .thumbnail-images::-webkit-scrollbar {
        display: none;
    }

    .variant-item.active {
        border-color: #007bff !important;
    }

    .color-variant {
        position: relative;
    }

    .color-variant::after {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        border: 2px solid transparent;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .color-variant.active::after {
        border-color: #007bff;
    }

    .main-image img {
        transition: transform 0.3s ease;
    }

    .main-image img:hover {
        transform: scale(1.05);
    }

    .stock-info .alert {
        margin-bottom: 0;
    }
</style>
@endsection