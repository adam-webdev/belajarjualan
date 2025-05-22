@extends('layouts.frontend.master')

@section('title', $category->name . ' - Apriori Shop')

@section('content')
<div class="container my-4">
    <!-- Category Header -->
    <div class="category-header mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>
        <h1 class="h2 mb-2">{{ $category->name }}</h1>
        <p class="text-muted">{{ $category->description }}</p>
    </div>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form id="filterForm" action="{{ route('shop.category', $category->slug) }}" method="GET">
                        <!-- Price Range Filter -->
                        <div class="mb-4">
                            <h6 class="mb-3">Price Range</h6>
                            <div class="d-flex">
                                <div class="input-group me-2">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ $minPrice ?? '' }}">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ $maxPrice ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Rating Filter -->
                        <div class="mb-4">
                            <h6 class="mb-3">Rating</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rating" value="5" id="rating5" {{ $rating == 5 ? 'checked' : '' }}>
                                <label class="form-check-label" for="rating5">
                                    ⭐⭐⭐⭐⭐ 5 stars
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rating" value="4" id="rating4" {{ $rating == 4 ? 'checked' : '' }}>
                                <label class="form-check-label" for="rating4">
                                    ⭐⭐⭐⭐ 4 stars & up
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rating" value="3" id="rating3" {{ $rating == 3 ? 'checked' : '' }}>
                                <label class="form-check-label" for="rating3">
                                    ⭐⭐⭐ 3 stars & up
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rating" value="2" id="rating2" {{ $rating == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="rating2">
                                    ⭐⭐ 2 stars & up
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rating" value="1" id="rating1" {{ $rating == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="rating1">
                                    ⭐ 1 star & up
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="sort" value="{{ $sortBy }}">
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>

            <!-- Related Categories -->
            @if($relatedCategories->count() > 0)
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Related Categories</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($relatedCategories as $relatedCategory)
                        <li class="list-group-item border-0 ps-0">
                            <a href="{{ route('shop.category', $relatedCategory->slug) }}" class="text-decoration-none text-dark">{{ $relatedCategory->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>

        <!-- Products -->
        <div class="col-lg-9">
            <!-- Sorting Options -->
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded">
                <div>
                    <span class="text-muted">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() ?? 0 }} products</span>
                </div>
                <div class="d-flex align-items-center">
                    <label class="me-2 mb-0" for="sortBy">Sort by:</label>
                    <select class="form-select form-select-sm" id="sortBy" onchange="updateSort(this.value)">
                        <option value="newest" {{ $sortBy == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_low" {{ $sortBy == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ $sortBy == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="rating" {{ $sortBy == 'rating' ? 'selected' : '' }}>Ratings: High to Low</option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row">
                @if($products->count() > 0)
                    @foreach($products as $product)
                    <div class="col-6 col-md-4 col-lg-4 mb-4">
                        <div class="product-card">
                            <div class="position-relative">
                                <a href="{{ route('shop.product', $product->slug) }}">
                                    @if($product->images->count() > 0)
                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                                    @else
                                        <img src="https://via.placeholder.com/300x300?text={{ $product->name }}" class="card-img-top product-img" alt="{{ $product->name }}">
                                    @endif
                                </a>
                                @if($product->sale_price && $product->sale_price < $product->base_price)
                                    <span class="discount-badge">-{{ round((($product->base_price - $product->sale_price) / $product->base_price) * 100) }}%</span>
                                @endif
                                {{-- <form method="POST" action="{{ route('shop.wishlist.add') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-sm position-absolute top-0 end-0 m-2 bg-white rounded-circle p-2 wishlist-btn">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </form> --}}
                            </div>
                            <div class="card-body">
                                <a href="{{ route('shop.product', $product->slug) }}" class="text-decoration-none text-dark">
                                    <h5 class="product-title">{{ $product->name }}</h5>
                                </a>
                                <div class="d-flex justify-content-between align-items-center">
                                    @if($product->sale_price && $product->sale_price < $product->base_price)
                                    <div>
                                        <p class="product-price mb-0">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</p>
                                        <small class="text-decoration-line-through text-muted">Rp {{ number_format($product->base_price, 0, ',', '.') }}</small>
                                    </div>
                                    @else
                                    <p class="product-price mb-0">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                                    @endif
                                    <form method="POST" action="{{ route('shop.cart.add') }}" class="d-inline cart-form">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="button" class="btn btn-sm btn-primary add-to-cart-btn"><i class="bi bi-cart-plus"></i></button>
                                    </form>
                                </div>
                                <div class="mt-2">
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $product->reviews->avg('rating') ?? 0)
                                                ⭐
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                        <span class="text-muted small ms-1">
                                            ({{ $product->reviews->count() ?? 0 }})
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-12 text-center py-5">
                        <h4>No products found</h4>
                        <p>Try adjusting your filters or check out other categories</p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends([
                    'sort' => $sortBy,
                    'min_price' => $minPrice,
                    'max_price' => $maxPrice,
                    'rating' => $rating
                ])->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function updateSort(value) {
        const form = document.getElementById('filterForm');
        const sortInput = form.querySelector('input[name="sort"]');
        sortInput.value = value;
        form.submit();
    }

    // Add to cart functionality
    document.querySelectorAll('.cart-form .add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const form = this.closest('form');
            const formData = new FormData(form);

            // Show loading state
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

            fetch('{{ route('shop.cart.add') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Reset button state
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-cart-plus"></i>';

                if (data.success) {
                    // Update cart count
                    const cartBadges = document.querySelectorAll('.cart-badge');
                    cartBadges.forEach(badge => {
                        badge.textContent = data.cart.items_quantity;
                        badge.classList.add('pulse-animation');
                        setTimeout(() => {
                            badge.classList.remove('pulse-animation');
                        }, 1000);
                    });

                    // Show success toast
                    alert('Product added to cart!');
                } else {
                    alert(data.message || 'Failed to add to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-cart-plus"></i>';
                alert('Failed to add to cart. Please try again.');
            });
        });
    });
</script>
@endsection