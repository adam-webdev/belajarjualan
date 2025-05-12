<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.css')}}">

    <link rel="stylesheet" href="{{asset('assets/vendors/toastify/toastify.css')}}">

    <link rel="stylesheet" href="{{asset('assets/vendors/iconly/bold.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/summernote/summernote-lite.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/simple-datatables/style.css')}}">

    <link rel="stylesheet" href="{{asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/bootstrap-icons/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/app.css')}}">
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.svg" type="image/x-icon')}}">

    <style>
        /* Override Choices.js styles */
        .choices__inner {
            background-color: #fff;
            border-radius: 0.3rem;
            border: 1px solid #dce7f1;
            min-height: 40px;
        }
        .choices__list--dropdown {
            z-index: 999;
        }
    </style>

    @yield('css')
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <a href="{{ route('dashboard') }}">
                                Apriori
                            </a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item has-sub {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Master Data</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.users.index') }}">Pengguna</a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('admin.addresses.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.addresses.index') }}">Alamat</a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.categories.index') }}">Kategori</a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.products.index') }}">Produk</a>
                                </li>
                            </ul>
                        </li>



                        <li class="sidebar-title">Pages</li>


                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <h3>@yield('title', 'Dashboard')</h3>
            </div>
            <div class="page-content">
                @yield('content')
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2024 &copy; Apriori</p>
                    </div>
                    <div class="float-end">
                        <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a href="#">Apriori Team</a></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
     <script src="{{asset('assets/js/main.js')}}"></script>
     <script src="{{asset('assets/vendors/simple-datatables/simple-datatables.js')}}"></script>
    <script src="{{asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{asset('assets/vendors/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/vendors/toastify/toastify.js')}}"></script>
    <script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>
    <script src="{{asset('assets/vendors/summernote/summernote-lite.min.js')}}"></script>

    <script src="{{asset('assets/vendors/apexcharts/apexcharts.js')}}"></script>
    <script src="{{asset('assets/js/pages/dashboard.js')}}"></script>

    <!-- Display session messages using Toastify -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle success messages
            @if(session('success'))
                Toastify({
                    text: "{{ session('success') }}",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#4fbe87",
                }).showToast();
            @endif

            // Handle error messages
            @if(session('error'))
                Toastify({
                    text: "{{ session('error') }}",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#ff6b6b",
                }).showToast();
            @endif

            // Handle info messages
            @if(session('info'))
                Toastify({
                    text: "{{ session('info') }}",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#3abaf4",
                }).showToast();
            @endif

            // Handle warning messages
            @if(session('warning'))
                Toastify({
                    text: "{{ session('warning') }}",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#ffa426",
                }).showToast();
            @endif
        });
    </script>

    <script>
        // Initialize components
        document.addEventListener('DOMContentLoaded', function() {
            // Simple Datatable
            let table1 = document.querySelector('#table1');
            if (table1) {
                new simpleDatatables.DataTable(table1);
            }

            // Initialize Choices.js for all select boxes with class 'choices'
            if (typeof Choices !== 'undefined') {
                const choicesElements = document.querySelectorAll('.choices');
                if (choicesElements.length > 0) {
                    choicesElements.forEach(element => {
                        new Choices(element, {
                            searchEnabled: true,
                            itemSelectText: '',
                            removeItemButton: true,
                            classNames: {
                                containerOuter: 'choices form-select'
                            }
                        });
                    });
                }
            }
        });
    </script>

    @yield('js')
</body>

</html>