<!doctype html>
<html lang="id">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Favicon -->

        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets_home/favicon_io/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets_home/favicon_io/favicon-32x32.png') }}">

        <!-- Apple Touch Icon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets_home/favicon_io/apple-touch-icon.png') }}">

        <!-- Android Chrome Icons -->
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets_home/favicon_io/android-chrome-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('assets_home/favicon_io/android-chrome-512x512.png') }}">

        <!-- Manifest (optional, for PWA support) -->
        <link rel="manifest" href="{{ asset('assets_home/favicon_io/site.webmanifest') }}">




        <!-- SEO Meta Tags -->
        <meta name="description" content="@yield('meta_description', 'Splatinum solusi Pintu dan Jendela berbahan UPVC dan Alumunium berkualitas tinggi.')">
        <meta name="keywords" content="@yield('meta_keywords', 'upvc, Pintu Upvc, Jendela Upvc, Splatinum, PVC, Kaca Upvc, Pintu Alumunium, harga pintu upvc, harga jendela upvc, harga pintu alumunium, harga jendela alumunium')">

        <meta name="robots" content="@yield('meta_robots', 'index, follow')">
        <meta name="author" content="@yield('meta_author', 'Splatinum Skyreach Indonesia | Adam Webdev')">
        <link rel="canonical" href="@yield('canonical', url()->current())">

        <!-- Open Graph / Facebook -->
        <meta property="og:title" content="@yield('og_title', 'Splatinum Indonesia')">
        <meta property="og:description" content="@yield('og_description', 'Splatinum Indonesia menyediakan solusi pintu dan jendela Berbahan upvc dan alumunium berkualitas tinggi.')">
        <meta property="og:image" content="@yield('og_image', asset('assets_home/img/logo/logo_splatinum_upvc_alumunium.png'))">
        <meta property="og:url" content="@yield('og_url', url()->current())">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="pintu jendela berbahan upvc dan alumunium terbaik | Splatinum Skyreach Indonesia">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('twitter_title', 'Splatinum Indonesia')">
        <meta name="twitter:description" content="@yield('twitter_description', 'Splatinum Indonesia menyediakan solusi pintu dan jendela Berbahan upvc dan alumunium berkualitas tinggi.')">

        <meta name="twitter:image" content="@yield('twitter_image', asset('assets_home/img/logo/logo_splatinum_upvc_alumunium.png'))">

        <meta name="twitter:site" content="@yield('twitter_site', '@splatinum_indonesia')">

        <link rel="preload" as="image" href="{{ asset('assets_home/img/banner/hotel-santika.webp') }}">

        <!-- Links of CSS files -->
        <link rel="stylesheet" href="{{asset('assets_home/css/aoss.css')}}">
        <link rel="stylesheet" href="{{asset('assets_home/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets_home/css/boxicons.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets_home/css/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets_home/css/flaticon.css')}}">
        <link rel="stylesheet" href="{{asset('assets_home/css/magnific-popup.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets_home/css/style.css')}}">

        <!-- @php
            $stylePath = public_path('assets_home/css/style.css');
            $version = file_exists($stylePath) ? filemtime($stylePath) : time();
        @endphp

        <link rel="stylesheet" href="{{ asset('assets_home/css/style.css') }}?v={{ $version }}"> -->

        <link rel="stylesheet" href="{{asset('assets_home/css/header.css')}}">
        <link rel="stylesheet" href="{{asset('assets_home/css/responsive.css')}}">
        <link rel="stylesheet" href="{{asset('assets_home/favicon_io/favicon.ico')}}">
        @yield('css')
        <style>
            /* Pastikan container tidak menyebabkan overflow */
            .container {
                max-width: 100% !important;
                width: 100% !important;
                padding-left: 50px;
                padding-right: 50px;
                margin: 0 auto;
            }

            /* Pastikan navbar tidak menyebabkan scroll horizontal */
            .navbar-area {
                max-width: 100% !important;
                width: 100% !important;
            }

            /* Atur agar navbar sticky tetap dalam batas layar */
            .navbar {
                position: sticky !important;
                top: 0;
                left: 0;
                right: 0;
                width: 100% !important;
                z-index: 999 !important;
            }

            /* Pastikan offcanvas menu tidak keluar layar */
            .offcanvas {
                max-width: 100% !important;
                width: 100% !important;
            }

            /* Tambahkan media query untuk mobile */
            @media (max-width: 768px) {
                .navbar-area {
                    max-width: 100% !important;
                    width: 100% !important;
                    overflow-x: hidden !important;
                }
                .container {
                    padding-left: 30px;
                    padding-right: 30px;
                }
                .navbar-collapse {
                    width: 100% !important;
                }

                .navbar-toggler {
                    margin-right: 10px !important;
                }
            }

             @media (max-width: 540px) {
                .container {
                    padding-left: 15px;
                    padding-right: 15px;
                }
             }
            /* Mencegah body dan html memiliki scroll horizontal */
            body, html {
                overflow-x: hidden !important;
            }
            .nav-link .nav-contact{
                display: block!important;
                background-color: #ffffff;
                border: 2px solid var(--mainColor)!important;
                color: #000;
                padding: 10px 14px;
            }
            .sr-only {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }

        </style>
        <title>@yield('title','Splatinum | Pintu Jendela upvc, alumunium ')</title>


    </head>
    <body>

        <div class="preloader-container" id="preloader">
            <div class="preloader-text">
                <span class="letter">S</span>
                <span class="letter">p</span>
                <span class="letter">l</span>
                <span class="letter">a</span>
                <span class="letter">t</span>
                <span class="letter">i</span>
                <span class="letter">n</span>
                <span class="letter">u</span>
                <span class="letter">m</span>
            </div>
        </div>
        <!-- preloader -->
    <div class="navbar-area style-2" id="navbar" >
    <!-- <div class="navbar-area style-2" id="navbar"> -->
        <div class="container">
            <nav class="navbar  navbar-expand-lg">
                <a class="navbar-brand" href="/">
                    <img class="logo-light" width="300" height="150" src="{{asset('assets_home/img/logo/logo-splatinum-1.png')}}" alt="logo splatinum indonesia penyedia pintu dan jendela berbahan upvc dan alumunium">
                    <img class="logo-dark" width="300" height="150" src="{{asset('assets_home/img/logo/logo-splatinum-1.png')}}" alt="logo splatinum indonesia penyedia pintu dan jendela berbahan upvc dan alumunium">
                </a>

                <a class="navbar-toggler" data-bs-toggle="offcanvas" href="#navbarOffcanvas" role="button" aria-controls="navbarOffcanvas">
                    <i class='bx bx-menu-alt-right'></i>
                </a>
                <div class="collapse navbar-collapse justify-content-between">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="{{ url('id' . Str::replaceFirst(request()->segment(1), '', request()->path())) }}" class="dropdown-toggle nav-link">
                                <img width="24px" src="{{asset('assets_home/img/icon/translate-bahasa.png')}}" alt="Logo Bahasa">
                                {{__('home.language')}}
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-item d-flex">

                                    <a href="{{ url('id' . Str::replaceFirst(request()->segment(1), '', request()->path())) }}" class="nav-link d-block ml-2">Indonesia</a>
                                </li>
                                <li class="nav-item d-flex">

                                    <a href="{{ url('en' . Str::replaceFirst(request()->segment(1), '', request()->path())) }}" class="nav-link d-block ml-2">English</a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('home',['lang' => app()->getLocale()])}}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                                {{__('home.home')}}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('tentangkami',['lang' => app()->getLocale()])}}" class="nav-link {{ request()->routeIs('tentangkami') ? 'active' : '' }}">
                                <!-- {{__('custom:home.about_us')}} -->
                                @lang('home.about_us')
                            </a>
                        </li>
                         <li class="nav-item">
                            <a href="{{route('produk',['lang' => app()->getLocale()])}}" class="nav-link {{ request()->routeIs('produk') ? 'active' : '' }}">
                                {{__('home.products')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('artikel',['lang' => app()->getLocale()])}}" class="nav-link {{ request()->routeIs('artikel') ? 'active' : '' }}">
                                <!-- {{__('custom:home.about_us')}} -->
                                @lang('home.article')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/hubungi-kami" onclick="hubungiKami(); return false" class="nav-link nav-contact">
                                <!-- {{__('custom:home.about_us')}} -->
                                @lang('home.contact_us')
                            </a>
                        </li>



                    </ul>

                </div>

            </nav>
        </div>
    </div>
       <!-- End Navbar Area Start -->

        <!-- Start Responsive Navbar Area -->
        <div class="responsive-navbar offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="navbarOffcanvas">
            <div class="offcanvas-header">
                <div>
                    <a href="/" class="logo d-inline-block">
                        <img class="logo-light" src="{{asset('assets_home/img/logo/logo-splatinum-1.png')}}" alt="logo splatinum indonesia penyedia pintu dan jendela berbahan upvc dan alumunium" width="200">
                    </a><br>
                    <!-- <span style="font-size: 12px;">PT Splatinum Skyreach Indonesia</span><br>
                    <span style="font-size: 12px;">{{__('home.footer_jargon2')}}</span> -->
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                <div class="accordion" id="navbarAccordion">
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <a href="{{ url('id' . Str::replaceFirst(request()->segment(1), '', request()->path())) }}" class="dropdown-toggle nav-link">
                                <img width="24px" src="{{asset('assets_home/img/icon/translate-bahasa.png')}}" alt="Logo Bahasa">
                                {{__('home.language')}}
                            </a>
                        </button>

                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#navbarAccordion">
                        <div class="accordion-body">
                            <div class="accordion" id="navbarAccordion8">
                                <div class="accordion-item d-flex">
                                    <a href="{{ url('id' . Str::replaceFirst(request()->segment(1), '', request()->path())) }}" class="nav-link d-block ml-2">Indonesia</a>
                                </div>
                                <div class="accordion-item d-flex">
                                    <a href="{{ url('en' .          Str::replaceFirst(request()->segment(1), '', request()->path())) }}" class="nav-link d-block ml-2">English</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                    <div class="accordion-item">
                         <a href="{{route('home',['lang' => app()->getLocale()])}}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }} accordion-link without-icon">
                            {{__('home.home')}}
                        </a>
                    </div>
                    <div class="accordion-item">
                         <a href="{{route('produk',['lang' => app()->getLocale()])}}" class="nav-link {{ request()->routeIs('produk') ? 'active' : '' }} accordion-link without-icon">
                            {{__('home.products')}}
                        </a>
                    </div>
                    <div class="accordion-item">
                         <a href="{{route('tentangkami',['lang' => app()->getLocale()])}}" class="nav-link {{ request()->routeIs('tentangkami') ? 'active' : '' }} accordion-link without-icon">
                            {{__('home.about_us')}}
                        </a>
                    </div>
                    <div class="accordion-item">
                         <a href="{{route('artikel',['lang' => app()->getLocale()])}}" class="nav-link {{ request()->routeIs('artikel') ? 'active' : '' }} accordion-link without-icon">
                            {{__('home.article')}}
                        </a>
                    </div>


                    <div class="accordion-item">
                        <a class="accordion-link without-icon" href="/hubungi-kami" onclick="hubungiKami(); return false">
                            {{__('home.contact_us')}}
                        </a>
                    </div>
                </div>
                <div class="offcanvas-contact-info">
                    <!-- <h4>Contact Info</h4> -->
                    <ul class="contact-info list-style">
                        <li>
                            <i class="bx bxs-envelope"></i>
                            <a href="mailto:admin@splatinum.co.id">admin@splatinum.co.id</a>
                        </li>
                        <li>
                            <i class="bx bxs-envelope"></i>
                            <a href="mailto:marketing@splatinum.co.id">marketing@splatinum.co.id</a>
                        </li>
                        <li >
                            <i class="bx bxs-phone"></i>
                            <a href="tel:+62182735592" style="font-size: 14px;">021 8273 5592</a>
                        </li>
                        <li >
                            <i class="bx bxl-whatsapp"></i>
                            <a href="/hubungi-kami" onclick="hubungiKami(); return false" style="font-size: 14px;">0812 1415 5598</a>
                        </li>
                        <li>
                            <i class="bx bxs-time"></i>
                            <p> {{__('home.footer_jam_operasional')}}: 9:00 - 17:00</p>
                        </li>
                    </ul>
                    <ul class="social-profile list-style">
                        <li>
                            <a href="https://www.facebook.com" target="_blank">
                                <i class='bx bxl-facebook'></i>
                                <span class="sr-only">Facebook</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/splatinum_windoors/" target="_blank">
                                <i class='bx bxl-instagram'></i>
                                <span class="sr-only">Instagram</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/@splatinumindonesia3505" target="_blank">
                                <i class='bx bxl-youtube'></i>
                                <span class="sr-only">YouTube</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com" target="_blank">
                                <i class='bx bxl-linkedin'></i>
                                <span class="sr-only">LinkedIn</span>
                            </a>
                        </li>
                    </ul>

                </div>
                <!-- <div class="offcanvas-other-options">
                    <div class="option-item d-flex gap-4">
                        <a href="javascript:void(0)" onclick="hubungiKami()" class="default-btn">0877 4462 5264</a>
                        <a href="javascript:void(0)" onclick="sales2()" class="default-btn">0878 8853 1006</a>
                    </div>
                </div> -->
            </div>
        </div>
        <!-- End Responsive Navbar Area -->
        <div style="margin-top: 50px;">
            @yield("content_home")
        </div>
        <div class="go-top active">
            <i class="bx bx-up-arrow-alt"></i>
        </div>

        <!-- Start Footer Area -->
        <div class="footer-area-3">
            <div class="footer-widget-info ptb-100">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 col-md-6">
                            <div class="footer-widget">
                                <!-- <div class="image"> -->
                                    <img width="200px" height="200px" src="{{asset('assets_home/img/logo/logo-splatinum-1.png')}}" alt="logo splatinum indonesia penyedia pintu dan jendela berbahan upvc dan alumunium">
                                <!-- </div> -->
                                <!-- <span>{{__('home.footer_jargon')}}</span><br>
                                <span>{{__('home.footer_jargon2')}}</span> -->
                                <p style="margin-top:10px">2012 - {{date('Y')}}</p>
                                <div>
                                <h4>{{__('home.footer_judul_6')}}</h4>
                                    <span style="font-size: 14px;">
                                        <i class="bx bx-phone"></i>
                                    <a href="tel:+62182735592" style="font-size: 14px;">021 8273 5592</a>
                                    </span><br>
                                    <span style="font-size: 14px;">
                                    <i class="bx bxl-whatsapp"></i>
                                    <a href="/hubungi-kami" onclick="hubungiKami(); return false" style="font-size: 14px;">0812 1415 5598</a>
                                    </span><br>
                                    <span style="font-size: 14px;">
                                    <i class="bx bx-envelope"></i>
                                    <a  href="mailto:admin@splatinum.co.id" style="font-size: 14px;">admin@splatinum.co.id</a>
                                    </span><br>
                                    <span style="font-size: 14px;">
                                    <i class="bx bx-envelope"></i>
                                    <a  href="mailto:marketing@splatinum.co.id" style="font-size: 14px;">marketing@splatinum.co.id</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-md-6">
                            <div class="footer-widget">
                                <h4>{{__('home.footer_judul_1')}}</h4>
                                <ul>
                                   @foreach(__('home.footer_produk') as $produk)
                                        <li>
                                            <a href="{{ url(app()->getLocale() . '/produk?kategori=' . $produk['link']) }}">
                                                <i class='bx bx-link-alt'></i> {{ $produk['name'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-md-6">
                            <div class="footer-widget">
                                <h4>{{__('home.footer_judul_2')}}</h4>
                                <ul>
                                    <li><a href="/"><i class='bx bx-link-alt'></i>{{__('home.home')}}</a></li>
                                    <li><a href="{{route('produk',['lang'=> app()->getLocale()])}}"><i class='bx bx-link-alt'></i> {{__('home.products')}}</a></li>
                                    <li><a href="{{route('tentangkami',['lang'=> app()->getLocale()])}}"><i class='bx bx-link-alt'></i> {{__('home.about_us')}}</a></li>
                                    <li><a href="{{route('artikel',['lang'=> app()->getLocale()])}}"><i class='bx bx-link-alt'></i> {{__('home.article')}}</a></li>
                                    <li><a href="/hubungi-kami" onclick="hubungiKami(); return false"><i class='bx bx-link-alt'></i> {{__('home.contact_us')}}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-md-6">
                            <div class="footer-widget">
                                <h4>{{__('home.footer_judul_4')}}</h4>
                                <div class="d-flex gap-2 flex-column">
                                    <div class="image">
                                        <a href="https://www.tokopedia.com/splatinum-indonesia" target="_blank" rel="nofollow">
                                            <img width="120" src="{{asset('assets_home/img/icon/tokped.png')}}" alt="tokopedia splatinum">
                                        </a>
                                    </div>
                                    <div class="image">
                                        <a href="https://shopee.co.id/splatinum_skyreach" target="_blank" rel="nofollow">
                                            <img width="120" src="{{asset('assets_home/img/icon/shoope.png')}}" alt="shoope splatinum upvc">
                                        </a>
                                    </div>
                                    <h4>{{__('home.footer_judul_5')}}</h4>
                                   <ul class="social-profile list-style d-flex">
                                    <li>
                                        <a href="https://www.fb.com" target="_blank">
                                            <i class='bx bxl-facebook'></i>
                                            <span class="sr-only">Facebook</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.instagram.com/splatinum_windoors/" target="_blank">
                                            <i class='bx bxl-instagram'></i>
                                            <span class="sr-only">Instagram</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.youtube.com/@splatinumindonesia3505" target="_blank">
                                            <i class='bx bxl-youtube'></i>
                                            <span class="sr-only">Youtube</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.linkedin.com" target="_blank">
                                            <i class='bx bxl-linkedin'></i>
                                            <span class="sr-only">Linkedin</span>
                                        </a>
                                    </li>
                                </ul>

                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-lg-3 col-sm-6 col-md-6">
                            <div class="footer-widget">
                                <h4>{{__('home.footer_judul_3')}}</h4>
                                <p class="mb-4"> {{__('home.footer_jam_operasional')}} <br>(08.00 - 17.00 WIB)</p>
                                <h4>{{__('home.footer_medsos')}}</h4>
                                <ul class="d-flex">
                                    <li><a href="#"><i class='bx bxl-instagram'></i> </a></li>
                                    <li><a href="#"><i class='bx bxl-facebook'></i></a></li>
                                    <li><a href="#"><i class='bx bxl-youtube'></i></a></li>
                                    <li><a href="#"></a></li>
                                </ul>
                            </div>
                        </div> -->

                    </div>
                </div>
            </div>
            <div class="container">

                <div class="copy-right-area">
                    <div class="row d-flex justify-content-between">
                        <div class="col-md-6 text-md-start">
                            <p>Copyright© <span class="footer-splatinum">PT Splatinum Skyreach Indonesia</span></p>
                        </div>
                        <div class="col-md-6 text-md-end ">
                            <p style="font-size: 14px!important;
                            margin-right: 25px;
                            ">
                                {{ __('home.dibuat') }}
                                <a style="font-size: 14px!important;" href="https://www.instagram.com/adam_webdev/"
                                target="_blank"
                                class="footer-splatinum">Adam Webdev</a>
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <!-- End Footer Area -->
        <!-- Links of JS files -->
        <script src="{{asset('assets_home/js/jquery.min.js')}}"></script>
        <script src="{{asset('assets_home/js/aos.js')}}"></script>
        <script src="{{asset('assets_home/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('assets_home/js/magnific-popup.min.js')}}"></script>
        <script src="{{asset('assets_home/js/owl.carousel.min.js')}}"></script>
        <script src="{{asset('assets_home/js/main.js')}}"></script>
        <script>
            function hubungiKami() {
                let message = encodeURIComponent(`Halo, saya ingin bertanya mengenai produk yang ada di Splatinum Indonesia. berupa list produk dan harga. Terimakasih`);
                let phoneNumber = "6281214155598"; // Nomor WhatsApp tujuan
                let waUrl = `https://wa.me/${phoneNumber}?text=${message}`;

                if (/Android|iPhone/i.test(navigator.userAgent)) {
                    waUrl = `https://wa.me/${phoneNumber}?text=${message}`;
                }
                window.open(waUrl, "_blank");
            }
            function sales2() {
                let message = encodeURIComponent(`Halo, saya ingin bertanya mengenai produk yang ada di Splatinum Indonesia. berupa list produk dan harga. Terimakasih`);


                let phoneNumber = "6281214155598"; // Nomor WhatsApp tujuan
                let waUrl = `https://wa.me/${phoneNumber}?text=${message}`;

                if (/Android|iPhone/i.test(navigator.userAgent)) {
                    waUrl = `https://wa.me/${phoneNumber}?text=${message}`;
                }
                window.open(waUrl, "_blank");
            }
        </script>
        @yield('js')
    </body>
</html>