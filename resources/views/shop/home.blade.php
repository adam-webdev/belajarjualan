@extends('layouts.frontend.master')

@section('title', 'Apriori Shop - Home')

@section('css')
<style>
    /* Carousel Styles */
    .carousel-caption {
        background-color: rgba(0, 0, 0, 0.6);
        padding: 20px;
        border-radius: 8px;
        max-width: 80%;
        margin: 0 auto;
        bottom: 40px;
    }

    .carousel-caption h2 {
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .carousel-caption p {
        font-size: 1.2rem;
        margin-bottom: 15px;
    }

    .carousel-caption .btn {
        font-weight: 600;
        padding: 8px 20px;
    }

    .carousel-item img {
        object-fit: cover;
        height: 500px;
    }

    .carousel-control-prev, .carousel-control-next {
        width: 5%;
        opacity: 0.8;
    }

    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.5);
        padding: 25px;
        border-radius: 50%;
    }

    /* Product Card Styles */
    .product-card {
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .product-img {
        height: 200px;
        object-fit: cover;
    }

    .discount-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #ff3d00;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: bold;
    }

    .product-title {
        font-size: 1rem;
        font-weight: 600;
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-price {
        font-weight: 700;
        color: #ff3d00;
    }

    /* Flash Sale Section */
    .flash-sale {
        background: linear-gradient(to right, #ff416c, #ff4b2b);
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(255, 75, 43, 0.3);
    }

    #countdown {
        font-weight: bold;
        color: white;
    }

    /* Category Card Styles */
    .category-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        height: 120px;
        display: block;
        margin: 0 8px;
        box-shadow: 0 2px 8px rgba(139, 69, 19, 0.1);
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(139, 69, 19, 0.2);
    }

    .category-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .category-card:hover img {
        transform: scale(1.1);
    }

    .category-name {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(139, 69, 19, 0.9), rgba(139, 69, 19, 0.4), transparent);
        color: #fff8f0;
        padding: 15px 10px 10px;
        margin: 0;
        font-size: 0.85rem;
        font-weight: 600;
        text-align: center;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }

    .category-icon {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(45deg, #f5e6d3, #e6d5c3);
    }

    .category-icon i {
        font-size: 2rem;
        color: #8B4513;
        opacity: 0.8;
    }

    /* Category Carousel Styles */
    .category-carousel {
        position: relative;
        padding: 0 50px;
        margin: 0 -8px;
    }

    .category-carousel .carousel-control-prev,
    .category-carousel .carousel-control-next {
        width: 40px;
        height: 40px;
        background: #fff8f0;
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 3px 10px rgba(139, 69, 19, 0.15);
        border: none;
        z-index: 2;
    }

    .category-carousel:hover .carousel-control-prev,
    .category-carousel:hover .carousel-control-next {
        opacity: 1;
    }

    .category-carousel .carousel-control-prev:hover,
    .category-carousel .carousel-control-next:hover {
        background: #8B4513;
        box-shadow: 0 5px 15px rgba(139, 69, 19, 0.4);
        transform: translateY(-50%) scale(1.1);
    }

    .category-carousel .carousel-control-prev {
        left: 5px;
    }

    .category-carousel .carousel-control-next {
        right: 5px;
    }

    .category-carousel .carousel-control-prev-icon,
    .category-carousel .carousel-control-next-icon {
        width: 24px;
        height: 24px;
        filter: brightness(0) saturate(100%) invert(28%) sepia(51%) saturate(1234%) hue-rotate(358deg) brightness(95%) contrast(101%);
        transition: all 0.3s ease;
    }

    .category-carousel .carousel-control-prev:hover .carousel-control-prev-icon,
    .category-carousel .carousel-control-next:hover .carousel-control-next-icon {
        filter: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(0%) hue-rotate(93deg) brightness(103%) contrast(103%);
    }

    .category-carousel .carousel-inner {
        padding: 15px 0;
    }

    .category-carousel .carousel-item {
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .category-carousel .carousel-item.active {
        display: flex;
    }

    .category-carousel .carousel-item-next:not(.carousel-item-start),
    .category-carousel .active.carousel-item-end {
        transform: translateX(100%);
    }

    .category-carousel .carousel-item-prev:not(.carousel-item-end),
    .category-carousel .active.carousel-item-start {
        transform: translateX(-100%);
    }

    /* Section Title Enhancement */
    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #8B4513;
        margin-bottom: 0;
        position: relative;
        padding-bottom: 10px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: #8B4513;
        border-radius: 2px;
    }

    .btn-outline-primary {
        color: #8B4513;
        border-color: #8B4513;
    }

    .btn-outline-primary:hover {
        background-color: #8B4513;
        border-color: #8B4513;
        color: #fff8f0;
    }
</style>
@endsection

@section('content')
<!-- Hero Slider -->
<section class="hero-slider mb-5">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=1200&auto=format&fit=crop" class="d-block w-100" alt="Flash Sale">
                <div class="carousel-caption">
                    <h2>Flash Sale Up To 50% Off</h2>
                    <p>Limited time offer on selected items</p>
                    <a href="{{ url('/flash-sales') }}" class="btn btn-light">Shop Now</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?q=80&w=1200&auto=format&fit=crop" class="d-block w-100" alt="New Arrivals">
                <div class="carousel-caption">
                    <h2>New Arrivals</h2>
                    <p>Check out our latest products</p>
                    <a href="{{ url('/products/new-arrivals') }}" class="btn btn-light">Explore</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1472851294608-062f824d29cc?q=80&w=1200&auto=format&fit=crop" class="d-block w-100" alt="Free Shipping">
                <div class="carousel-caption">
                    <h2>Free Shipping</h2>
                    <p>On orders over Rp 200,000</p>
                    <a href="{{ url('/products') }}" class="btn btn-light">Shop Now</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- Featured Categories -->
<section class="featured-categories mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Featured Categories</h2>
            <a href="{{ url('/categories') }}" class="btn btn-outline-primary btn-sm">View All</a>
        </div>

        <div id="categoryCarousel" class="carousel slide category-carousel" data-bs-ride="carousel">
            <div class="carousel-inner">
                @php
                    $categories = App\Models\Category::where('is_active', true)
                        ->orderBy('name')
                        ->get();
                    $chunks = $categories->chunk(6);
                @endphp

                @foreach($chunks as $index => $chunk)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="row">
                        @foreach($chunk as $category)
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="{{ route('shop.category', $category->slug) }}" class="category-card text-decoration-none">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                                @else
                                    <div class="category-icon">
                                        <i class="bi bi-grid"></i>
                                    </div>
                                @endif
                                <h5 class="category-name">{{ $category->name }}</h5>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#categoryCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#categoryCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-products mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Featured Products</h2>
            <a href="{{ url('/products') }}" class="btn btn-outline-primary btn-sm">View All</a>
        </div>
        <div class="row">
            @foreach($featuredProducts as $product)
            <div class="col-6 col-md-4 col-lg-3 mb-4">
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
                    </div>
                    <div class="card-body">
                        <h5 class="product-title">
                            <a href="{{ route('shop.product', $product->slug) }}" class="text-decoration-none text-dark">
                                {{ $product->name }}
                            </a>
                        </h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if($product->sale_price && $product->sale_price < $product->base_price)
                                    <p class="product-price mb-0">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</p>
                                    <small class="text-decoration-line-through text-muted">Rp {{ number_format($product->base_price, 0, ',', '.') }}</small>
                                @else
                                    <p class="product-price mb-0">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                                @endif
                            </div>
                            <button class="btn btn-sm btn-primary add-to-cart"
                                    data-product-id="{{ $product->id }}"
                                    data-combination-id="{{ $product->combinations->first()->id ?? 0 }}">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            @php
                                $avgRating = $product->reviews->avg('rating') ?? 0;
                                $totalReviews = $product->reviews->count();
                            @endphp
                            <small class="text-muted">
                                {!! str_repeat('⭐', round($avgRating)) !!}
                                {!! str_repeat('☆', 5 - round($avgRating)) !!}
                                ({{ $totalReviews }})
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Best Selling Products -->
<section class="best-selling-products mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Best Selling Products</h2>
            <a href="{{ url('/best-sellers') }}" class="btn btn-outline-primary btn-sm">View All</a>
        </div>
        <div class="row">
            @foreach($bestSellingProducts as $product)
            <div class="col-6 col-md-4 col-lg-3 mb-4">
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
                    </div>
                    <div class="card-body">
                        <h5 class="product-title">
                            <a href="{{ route('shop.product', $product->slug) }}" class="text-decoration-none text-dark">
                                {{ $product->name }}
                            </a>
                        </h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if($product->sale_price && $product->sale_price < $product->base_price)
                                    <p class="product-price mb-0">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</p>
                                    <small class="text-decoration-line-through text-muted">Rp {{ number_format($product->base_price, 0, ',', '.') }}</small>
                                @else
                                    <p class="product-price mb-0">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                                @endif
                            </div>
                            <button class="btn btn-sm btn-primary add-to-cart"
                                    data-product-id="{{ $product->id }}"
                                    data-combination-id="{{ $product->combinations->first()->id ?? 0 }}">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            @php
                                $avgRating = $product->reviews->avg('rating') ?? 0;
                                $totalReviews = $product->reviews->count();
                            @endphp
                            <small class="text-muted">
                                {!! str_repeat('⭐', round($avgRating)) !!}
                                {!! str_repeat('☆', 5 - round($avgRating)) !!}
                                ({{ $totalReviews }})
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Promotions -->
<section class="promotion-banners mb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card bg-light overflow-hidden">
                    <div class="row g-0">
                        <div class="col-5">
                            <img src="https://via.placeholder.com/300x200/42b549/ffffff?text=New+Arrivals" class="w-100 h-100 object-fit-cover" alt="New Arrivals">
                        </div>
                        <div class="col-7">
                            <div class="card-body">
                                <h5 class="card-title">New Arrivals</h5>
                                <p class="card-text">Check out our latest products for this season.</p>
                                <a href="{{ url('/new-arrivals') }}" class="btn btn-outline-primary btn-sm">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card bg-light overflow-hidden">
                    <div class="row g-0">
                        <div class="col-5">
                            <img src="https://via.placeholder.com/300x200/ff4d00/ffffff?text=Best+Sellers" class="w-100 h-100 object-fit-cover" alt="Best Sellers">
                        </div>
                        <div class="col-7">
                            <div class="card-body">
                                <h5 class="card-title">Best Sellers</h5>
                                <p class="card-text">Our most popular products that customers love.</p>
                                <a href="{{ url('/best-sellers') }}" class="btn btn-outline-primary btn-sm">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Add to cart functionality
    $('.add-to-cart').on('click', function() {
        const productId = $(this).data('product-id');
        const combinationId = $(this).data('combination-id');

        // Show loading state
        $(this).prop('disabled', true);
        $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

        // Send AJAX request
        $.ajax({
            url: '{{ route("shop.cart.add") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                combination_id: combinationId,
                quantity: 1
            },
            success: function(response) {
                // Reset button state
                $('.add-to-cart').prop('disabled', false);
                $('.add-to-cart').html('<i class="bi bi-cart-plus"></i>');

                if (response.success) {
                    // Update cart count
                    const cartBadges = document.querySelectorAll('.cart-badge');
                    cartBadges.forEach(badge => {
                        badge.textContent = response.cart.items_quantity;
                        badge.classList.add('pulse-animation');
                        setTimeout(() => {
                            badge.classList.remove('pulse-animation');
                        }, 1000);
                    });

                    // Show success message
                    alert('Product added to cart!');
                } else {
                    alert(response.message || 'Failed to add to cart');
                }
            },
            error: function(xhr) {
                // Reset button state
                $('.add-to-cart').prop('disabled', false);
                $('.add-to-cart').html('<i class="bi bi-cart-plus"></i>');

                alert('Failed to add to cart. Please try again.');
            }
        });
    });

    // Add wishlist functionality
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function() {
            const heartIcon = this.querySelector('i');
            if (heartIcon.classList.contains('bi-heart')) {
                heartIcon.classList.remove('bi-heart');
                heartIcon.classList.add('bi-heart-fill');
                heartIcon.style.color = '#ff5252';
            } else {
                heartIcon.classList.remove('bi-heart-fill');
                heartIcon.classList.add('bi-heart');
                heartIcon.style.color = '';
            }
        });
    });
});
</script>
@endsection