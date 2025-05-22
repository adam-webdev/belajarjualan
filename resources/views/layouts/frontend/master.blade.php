<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'THRIFT SHOP')</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'THRIFT SHOP - Your one-stop online shopping destination')">
    <meta name="keywords" content="@yield('meta_keywords', 'ecommerce, online shopping, thrift, shop')">
    <meta name="author" content="THRIFT SHOP">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

    <style>
        /* Custom Theme Colors */
        :root {
            --primary-color: #8B4513;      /* Brown - Primary color */
            --secondary-color: #D2691E;    /* Chocolate - Secondary color */
            --accent-color: #A0522D;       /* Sienna - Accent color */
            --hover-color: #6B3410;        /* Dark Brown - Hover color */
            --dark-color: #2a2a2a;         /* Dark gray - Text color */
            --light-color: #f7f7f7;        /* Light gray - Background color */
            --white-color: #ffffff;        /* White */
            --success-color: #8B4513;      /* Success color */
            --danger-color: #ff5252;       /* Danger color */
            --warning-color: #ffc107;      /* Warning color */
            --info-color: #03a9f4;         /* Info color */
        }

        /* Override Bootstrap primary colors */
        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
            color: var(--white-color);
        }

        /* Navbar customization */
        .navbar-custom {
            background-color: var(--primary-color) !important;
        }

        .navbar-custom .navbar-brand {
            color: var(--white-color) !important;
        }

        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            transition: color 0.3s ease;
        }

        .navbar-custom .nav-link:hover {
            color: var(--white-color) !important;
        }

        /* Dropdown menu customization */
        .dropdown-menu {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .dropdown-item {
            padding: 8px 20px;
            transition: background-color 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: var(--light-color);
        }

        .dropdown-item.text-danger:hover {
            background-color: #fff5f5;
        }

        /* Footer customization */
        .footer {
            background-color: var(--primary-color);
            color: var(--white-color);
        }

        .footer a {
            color: rgba(255, 255, 255, 0.9);
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--white-color);
            text-decoration: underline;
        }

        .badge-primary {
            background-color: var(--primary-color);
        }

        body {
            font-family: 'Inter', 'Segoe UI', Roboto, -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--dark-color);
            background-color: var(--light-color);
        }

        /* Header styles */
        .navbar-custom {
            background-color: var(--white-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand img {
            height: 40px;
        }

        .search-form {
            width: 100%;
            max-width: 500px;
        }

        .search-input {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 10px 15px;
        }

        /* Navigation */
        .nav-link {
            color: var(--dark-color);
            font-weight: 500;
            padding: 8px 15px;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .nav-link.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Category pills */
        .category-pill {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            background-color: var(--white-color);
            color: var(--dark-color);
            font-size: 14px;
            font-weight: 500;
            margin: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .category-pill:hover {
            background-color: var(--hover-color);
            color: var(--white-color);
            border-color: var(--hover-color);
        }

        .category-pill.active {
            background-color: var(--primary-color);
            color: var(--white-color);
            border-color: var(--primary-color);
        }

        /* Product cards */
        .product-card {
            background: var(--white-color);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .product-img {
            height: 180px;
            object-fit: cover;
        }

        .product-title {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            height: 40px;
        }

        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-color);
        }

        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--danger-color);
            color: var(--white-color);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #389e3e;
            border-color: #389e3e;
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--white-color);
        }

        /* Footer */
        .footer {
            background-color: var(--white-color);
            padding: 50px 0 20px;
            color: var(--dark-color);
        }

        .footer-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
            padding-left: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: var(--dark-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .footer-bottom {
            border-top: 1px solid #e0e0e0;
            padding-top: 20px;
            text-align: center;
            font-size: 14px;
        }

        /* Cart badge */
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger-color);
            color: var(--white-color);
            border-radius: 50%;
            font-size: 10px;
            width: 18px;
            height: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .pulse-animation {
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        /* User avatar in dropdown */
        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 5px;
        }

        /* Style active category pill */
        .category-pill.active {
            background-color: var(--primary-color);
            color: var(--white-color);
            border-color: var(--primary-color);
        }

        /* Better dropdown menu on mobile */
        @media (max-width: 767px) {
            .dropdown-menu {
                position: static !important;
                width: 100%;
                margin-top: 0;
                background-color: #f8f9fa;
                border: none;
                box-shadow: none;
            }

            .dropdown-item {
                padding: 12px 20px;
            }

            .navbar .nav-item {
                border-bottom: 1px solid rgba(0,0,0,0.05);
            }

            .navbar .nav-item:last-child {
                border-bottom: none;
            }
        }

        /* Media queries for responsive design */
        @media (max-width: 768px) {
            .search-form {
                max-width: 100%;
                margin-top: 10px;
                margin-bottom: 10px;
            }

            .product-img {
                height: 140px;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }

            .category-pill {
                padding: 5px 12px;
                font-size: 12px;
            }
        }

        /* Improved mobile navbar */
        @media (max-width: 991px) {
            .navbar-collapse {
                background-color: var(--white-color);
                padding: 1rem;
                border-radius: 0.5rem;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                margin-top: 0.5rem;
            }

            .search-form {
                margin-bottom: 1rem;
            }

            .navbar-nav {
                padding-top: 0.5rem;
                border-top: 1px solid rgba(0,0,0,0.1);
            }
        }

        /* Mobile category scroll */
        .category-mobile-scroll {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none; /* For Firefox */
            -ms-overflow-style: none; /* For Internet Explorer and Edge */
        }

        .category-mobile-scroll::-webkit-scrollbar {
            display: none; /* For Chrome, Safari, and Opera */
        }

        .category-mobile-scroll .category-pill {
            flex-shrink: 0;
            white-space: nowrap;
        }

        /* Button hover styles */
        .btn-checkout:hover,
        .btn-add-to-cart:hover,
        .search-form .btn:hover,
        .btn-success:hover,
        .btn-primary:hover,
        .btn-filter:hover,
        .btn-apply-filter:hover,
        .btn-place-order:hover,
        .btn-process-checkout:hover {
            background-color: var(--hover-color) !important;
            border-color: var(--hover-color) !important;
            color: var(--white-color) !important;
        }

        /* Button base styles */
        .btn-checkout,
        .btn-add-to-cart,
        .search-form .btn,
        .btn-success,
        .btn-primary,
        .btn-filter,
        .btn-apply-filter,
        .btn-place-order,
        .btn-process-checkout {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: var(--white-color) !important;
        }

        /* Override Bootstrap success button */
        .btn-success {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .btn-success:hover {
            background-color: var(--hover-color) !important;
            border-color: var(--hover-color) !important;
        }

        /* Search button specific */
        .search-form .btn {
            background-color: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
            color: var(--white-color) !important;
        }

        .search-form .btn:hover {
            background-color: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
        }

        .search-form .input-group {
            border-radius: 8px;
            overflow: hidden;
        }

        .search-form .form-control {
            border: none;
            padding: 10px 15px;
        }

        .search-form .form-control:focus {
            box-shadow: none;
        }
    </style>

    <!-- Additional CSS -->
    @yield('css')
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <strong>THRIFT</strong> SHOP
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <form class="search-form mx-auto" action="{{ route('shop.search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control search-input" placeholder="Search products..." value="{{ request('q') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ url('/cart') }}">
                                <i class="bi bi-cart fs-5"></i>
                                @php
                                    $cartCount = 0;
                                    if (Auth::check()) {
                                        $cart = App\Models\Cart::where('user_id', Auth::id())->first();
                                        if ($cart) {
                                            $cartCount = $cart->items->sum('quantity');
                                        }
                                    } else {
                                        // For guest users
                                        $cartId = session('cart_id');
                                        if ($cartId) {
                                            $cart = App\Models\Cart::find($cartId);
                                            if ($cart) {
                                                $cartCount = $cart->items->sum('quantity');
                                            }
                                        }
                                    }
                                @endphp
                                <span class="cart-badge" id="cart-badge">{{ $cartCount }}</span>
                            </a>
                        </li>
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <div class="d-flex align-items-center">
                                        <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(Auth::user()->email))) }}?d=mp&s=60"
                                            alt="{{ Auth::user()->name }}"
                                            class="user-avatar d-none d-md-inline-block">
                                        <span class="d-none d-md-inline-block ms-1">{{ Auth::user()->name }}</span>
                                        <i class="bi bi-person-circle fs-5 d-inline-block d-md-none"></i>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <span class="dropdown-item-text">
                                            <small class="text-muted">Signed in as</small><br>
                                            <strong>{{ Auth::user()->name }}</strong>
                                        </span>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>

                                    @if(Auth::user()->role === 'admin')
                                        <li><a class="dropdown-item" href="{{ url('/dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i> Admin Dashboard
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif

                                    <li><a class="dropdown-item" href="{{ url('/profile') }}">
                                        <i class="bi bi-person me-2"></i> My Profile
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ url('/orders') }}">
                                        <i class="bi bi-bag me-2"></i> My Orders
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('custom.logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('custom.logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Categories Navigation -->
        <div class="bg-white border-bottom py-2 d-none d-md-block">
            <div class="container">
                <div class="row">
                    <div class="col-12 category-scroll">
                        <div class="d-flex justify-content-start overflow-auto pb-2">
                            <a href="{{ url('/') }}" class="category-pill">All Products</a>
                            @php
                                $categories = App\Models\Category::where('is_active', true)
                                    ->orderBy('name')
                                    ->take(10)
                                    ->get();
                            @endphp

                            @foreach($categories as $category)
                                <a href="{{ route('shop.category', $category->slug) }}" class="category-pill">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile-friendly Categories (visible only on mobile) -->
        <div class="bg-white border-bottom py-2 d-block d-md-none">
            <div class="container-fluid px-2">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex overflow-auto pb-2 category-mobile-scroll">
                            <a href="{{ url('/') }}" class="category-pill {{ request()->is('/') ? 'active' : '' }}">All</a>
                            @foreach($categories as $category)
                                <a href="{{ route('shop.category', $category->slug) }}"
                                   class="category-pill {{ request()->is('category/'.$category->slug) ? 'active' : '' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <h5 class="footer-title">THRIFT SHOP</h5>
                    <p>Your one-stop destination for all your shopping needs. Find the best products at the best prices.</p>
                    <div class="mt-3">
                        <a href="#" class="text-white me-2"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-white me-2"><i class="bi bi-twitter fs-5"></i></a>
                        <a href="#" class="text-white me-2"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="text-white me-2"><i class="bi bi-youtube fs-5"></i></a>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ route('shop.flash-sales') }}">Flash Sales</a></li>
                        <li><a href="{{ route('shop.new-arrivals') }}">New Arrivals</a></li>
                        <li><a href="{{ route('shop.best-sellers') }}">Best Sellers</a></li>
                    </ul>
                </div>

                <div class="col-md-3 mb-4">
                    <h5 class="footer-title">Categories</h5>
                    <ul class="footer-links">
                        @php
                            $footerCategories = App\Models\Category::where('is_active', true)
                                ->orderBy('name')
                                ->take(5)
                                ->get();
                        @endphp

                        @foreach($footerCategories as $category)
                            <li>
                                <a href="{{ route('shop.category', $category->slug) }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-md-3 mb-4">
                    <h5 class="footer-title">Customer Service</h5>
                    <ul class="footer-links">
                        <li><a href="{{ Auth::check() ? route('shop.profile') : route('login') }}">My Account</a></li>
                        <li><a href="{{ Auth::check() ? route('shop.orders') : route('login') }}">Track Orders</a></li>
                        <li><a href="{{ url('/cart') }}">Shopping Cart</a></li>

                    </ul>
                </div>

                <div class="col-md-3 mb-4">
                    <h5 class="footer-title">Contact Info</h5>
                    <ul class="footer-links">
                        <li><i class="bi bi-geo-alt me-2"></i>  Bekasi, Indonesia</li>
                        <li><i class="bi bi-telephone me-2"></i> +62 123 4567 890</li>
                        <li><i class="bi bi-envelope me-2"></i> info@thriftshop.com</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="mb-0">&copy; {{ date('Y') }} THRIFT SHOP. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- jQuery (for additional functionality) -->
    <script src="{{ asset('assets/vendors/jquery/jquery.min.js') }}"></script>

    <!-- Custom JavaScript -->
    <script>
        $(document).ready(function() {
            // Close navbar collapse when clicking outside
            $(document).click(function(e) {
                if(!$(e.target).closest('.navbar').length) {
                    $('.navbar-collapse').collapse('hide');
                }
            });

            // Smooth scrolling for anchor links
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $($(this).attr('href')).offset().top - 70
                }, 500);
            });

            // Add active class to current category pill
            const currentPath = window.location.pathname;
            $('.category-pill').each(function() {
                const linkPath = $(this).attr('href');
                if (currentPath === linkPath || currentPath.includes(linkPath) && linkPath !== '/') {
                    $(this).addClass('active');
                }
            });

            // If cart has items, pulse the cart badge
            @if($cartCount > 0)
                setInterval(function() {
                    $('.cart-badge').toggleClass('pulse-animation');
                }, 2000);
            @endif
        });
    </script>

    <!-- Additional Scripts -->
    @yield('js')
</body>
</html>