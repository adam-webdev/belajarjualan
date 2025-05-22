@extends('layouts.frontend.master')

@section('title', 'Search Results - Apriori Shop')

@section('content')
<div class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Search Results</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Search Results Header -->
        <div class="col-12 mb-4">
            <h1 class="h3 mb-3">Search Results for "{{ $query }}"</h1>
            <p class="text-muted">{{ $products->total() }} products found</p>
        </div>

        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('shop.search') }}" method="GET" id="filterForm">
                        <input type="hidden" name="q" value="{{ $query }}">

                        <!-- Price Range -->
                        <div class="mb-4">
                            <h6 class="mb-3">Price Range</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="mb-4">
                            <h6 class="mb-3">Categories</h6>
                            @php
                                $categories = App\Models\Category::where('is_active', true)->get();
                            @endphp
                            @foreach($categories as $category)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}"
                                        id="category{{ $category->id }}"
                                        {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Rating -->
                        <div class="mb-4">
                            <h6 class="mb-3">Rating</h6>
                            @for($i = 5; $i >= 1; $i--)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" value="{{ $i }}"
                                        id="rating{{ $i }}"
                                        {{ request('rating') == $i ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating{{ $i }}">
                                        @for($j = 1; $j <= $i; $j++)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @endfor
                                        @for($j = $i + 1; $j <= 5; $j++)
                                            <i class="bi bi-star text-warning"></i>
                                        @endfor
                                    </label>
                                </div>
                            @endfor
                        </div>

                        <!-- Sort By -->
                        <div class="mb-4">
                            <h6 class="mb-3">Sort By</h6>
                            <select class="form-select form-select-sm" name="sort" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-6 col-md-4 mb-4">
                            <div class="product-card">
                                <div class="position-relative">
                                    @if($product->images->count() > 0)
                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                             class="card-img-top product-img"
                                             alt="{{ $product->name }}">
                                    @else
                                        <img src="https://via.placeholder.com/300x300?text={{ $product->name }}"
                                             class="card-img-top product-img"
                                             alt="{{ $product->name }}">
                                    @endif

                                    <!-- Wishlist Button -->
                                    {{-- <form method="POST" action="{{ route('shop.wishlist.add') }}" class="wishlist-form">
                                        @csrf
                                        <input type="hidden" name="product_combination_id" value="{{ $product->combinations->first()->id ?? 0 }}">
                                        <button type="button" class="btn btn-sm position-absolute top-0 end-0 m-2 bg-white rounded-circle p-2 wishlist-btn">
                                            <i class="bi bi-heart"></i>
                                        </button>
                                    </form> --}}
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
                                            <button type="button" class="btn btn-sm btn-primary add-to-cart-btn">
                                                <i class="bi bi-cart-plus"></i>
                                            </button>
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

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-search fs-1 text-muted"></i>
                    <h5 class="mt-3">No products found</h5>
                    <p class="text-muted">Try adjusting your search or filter criteria</p>
                    <a href="{{ url('/') }}" class="btn btn-primary mt-3">Back to Home</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Handle wishlist button click
        {{-- $('.wishlist-btn').on('click', function() {
            const form = $(this).closest('form');
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        alert('Product added to wishlist');
                    } else {
                        alert(response.message || 'Failed to add to wishlist');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        }); --}}

        // Handle add to cart button click
        $('.add-to-cart-btn').on('click', function() {
            const form = $(this).closest('form');
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        // Update cart badge
                        $('.cart-badge').text(response.cart.items_quantity);
                        $('.cart-badge').addClass('pulse-animation');
                        setTimeout(() => {
                            $('.cart-badge').removeClass('pulse-animation');
                        }, 1000);

                        alert('Product added to cart');
                    } else {
                        alert(response.message || 'Failed to add to cart');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        });

        // Handle filter form submission
        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            window.location.href = '{{ route("shop.search") }}?' + formData;
        });
    });
</script>
@endsection