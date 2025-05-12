@extends('layouts.frontend.layout_homemaster')
@section('css')
    <style>
        .slider-content .content .short-info h3.banner{
            font-size: 18px!important;
        }
        .tentang-splatinum .content h2{
            font-size: 30px;
            font-weight: 700;
        }
        .tentang-splatinum .content h3{
            font-size: 24px;
            font-weight: 700;
        }
        .tentang-splatinum .content p{
            margin: 20px 0;
            font-size: 16px;
        }
        /* .produk-kategori-area .owl-carousel{

        } */
        .produk-kategori-area .product-carousel {
            background: #f8f9fa; /* Warna background */
            /* padding:30px 0; */
            /* box-shadow: inset 0px 4px 10px rgba(0, 0, 0, 0.1); Efek tertanam */
            position: relative;
        }
        /* .produk-kategori-area .owl-carousel .item{
            padding: 12px;

        } */
        /* product */
        .product-card {
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);

            /* box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); */
            transition: transform 0.3s ease-in-out;
            background: #fff;
            margin-bottom: 20px;
        }
        .product-card img:hover {
            transform: scale(1.04);
        }
        .product-card img {
            width: 100%;
            height: 300px;
            transition: .4s ease-in;
            object-fit: cover;
        }
        .product-carousel .owl-nav {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }
        .product-carousel h5{
            font-size: 18px;
        }
        .product-carousel .owl-prev, .product-carousel .owl-next {
            background: rgba(0, 0, 0, 0.5) !important;
            color: white !important;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 20px;
        }
        /* end product */

        /* counter section */

        .counter-section {
            padding: 50px 20px;
            text-align: center;
        }

        .counter-grid {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }

        .counter-box {
            border-radius: 10px;
            text-align: center;
            width: 220px;
            transition: transform 0.3s;
        }

        .counter-box i {
            font-size: 60px;
            color: var(--whiteColor);
        }

        .counter-box h2 {
            font-size: 32px;
            margin: 10px 0;
            color: var(--secondColor);
        }

        .counter-box p {
            font-size: 16px;
            color: #fff;
        }

        /* end counter section */

        /*  mengapa kami section */

        .why-choose-us {
            background: #f8f9fa;
            padding: 70px 0;
        }

        .why-choose-us .card {
            border: none;
            /* border-radius: 10px; */
            overflow: hidden;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .why-choose-us .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .why-choose-us .card i {
            font-size: 60px;
            color: var(--mainColor);
            margin-top: 20px;
        }

        .why-choose-us .card-title {
            font-weight: bold;
            margin-top: 15px;
            color: var(--secondColor);

        }

        .why-choose-us .card-text {
            color: var(--blackColor);
            font-size: 14px;
            padding: 0 15px 20px;
        }

        /* testimonial section  */
        .testimonial-section {
            background: var(--mainColor);
            text-align: center;
        }

        .testimonial-slider {
            margin: 0 auto;
        }

        .testimonial-item {
            padding: 20px;
            text-align: center; /* Biar lebih rapi */
        }

        .testimonial-item .wrapp-img-testi {
            width: 100px; /* Ukuran lebih kecil */
            height: 100px; /* Pastikan sama dengan width */
            overflow: hidden; /* Biar tidak ada bagian yang keluar */
            border-radius: 50%; /* Buat lingkaran */
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .testimonial-item .wrapp-img-testi img {
            width: 100%;
            height: 100%;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);
            object-fit: cover; /* Jaga proporsi gambar */
            border-radius: 50%;
        }

        .testimonial-item p {
            font-size: 14px;
            color: var(--whiteColor);
            font-style: italic;
            margin-bottom: 10px;
        }

        .testimonial-item h5 {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            color: var(--secondColor);

            margin-bottom: 3px;
        }

        .testimonial-item span {
            font-size: 14px;
            color: var(--whiteColor);

        }

        /* Beda dari Carousel Lain */
        /* Dot Default (Tidak Aktif) */
        .testimonial-slider .owl-dot {
            width: 10px;
            height: 10px;
            background-color: #777!important; /* Warna lebih soft */
            border-radius: 50%;
            margin: 5px;
            transition: background-color 0.3s ease;
        }

        /* Dot Aktif */
        .testimonial-slider .owl-dot.active {
            background: var(--secondColor)!important;
            transform: scale(1.2); /* Efek memperbesar saat aktif */
        }
        .lokasi-card{
            background-color: var(--whiteColor);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);

        }

        /* end testimonial section  */
        @media screen and (max-width:768px){
            .tentang-splatinum .content h2{
                font-size: 22px;
            }
            .tentang-splatinum .content h3{
                font-size: 18px;
            }
            .lokasi-area h2{
                font-size: 16px;

            }
            .lokasi-card h4,
            .lokasi-card p{
                font-size: 14px;
            }

        }
    </style>
@endsection
@section("content_home")

        <!-- Start Navbar Area Start -->

        <!-- Start Clgun Searchbar Area -->
        <div class="clgun offcanvas offcanvas-start" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop">
            <div class="offcanvas-header">
                <a href="/" class="logo">
                    <img src="{{asset('assets_home/img/logo/logo_splatinum_upvc_alumunium.png')}}" alt="Logo Splatinum Penyedia Pintu dan Jendela berbahan Upvc dan Alumunium">
                </a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

        </div>


        <div class="slider-banner-area">
            <div class="slider-courser owl-carousel owl-theme">
                <div class="slider-content">
                    <img
                    src="/assets_home/img/banner/hotel-santika.webp"
                    srcset="
                        /assets_home/img/banner/hotel-santika-768.webp 768w,
                        /assets_home/img/banner/hotel-santika.webp 1920w
                    "
                    sizes="(max-width: 768px) 768px, 1920px"
                    width="1920"
                    height="960"
                    class="slider-img"
                    alt="Hotel Santika Upvc Splatinum"
                    loading="lazy"
                    />
                    <div class="content">
                        <div class="text">
                            <div class="container">
                                <h1 data-aos="fade-up" data-aos-delay="100">@lang('home.judul_slide_1')</h1>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="short-info text-center">
                                    <h3 class="banner" data-aos="fade-up" data-aos-delay="200">
                                        {{__('home.deskripsi_slide_1')}}
                                    </h3>
                                     <button class="default-btn" onclick="hubungiKami()" data-aos="fade-zoom-in" data-aos-delay="100">
                                        {{ __('home.contact_us') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="slider-content">
                    <img
                        src="{{asset('assets_home/img/banner/hotel-amaris.webp')}}"
                        width="1920" height="960"
                        class="slider-img"
                        alt="Hotel Amaris Upvc Splatinum"
                        loading="lazy"
                    >
                    <div class="content">
                        <div class="text">
                            <div class="container">
                                <h1 data-aos="fade-up" data-aos-delay="100"> {{__('home.judul_slide_2')}}</h1>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="short-info">
                                    <h3 class="banner"  data-aos="fade-up" data-aos-delay="200">{{__('home.deskripsi_slide_2')}}</h3>
                                     <button class="default-btn" onclick="hubungiKami()" data-aos="fade-zoom-in" data-aos-delay="100">
                                        {{ __('home.contact_us') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="slider-content">
                    <img
                        src="{{asset('assets_home/img/banner/banner.webp')}}"
                        width="1920" height="960"
                        class="slider-img"
                        alt="Hotel Amaris Upvc Splatinum"
                        loading="lazy"
                    >
                    <div class="content">
                        <div class="text">
                            <div class="container">
                                <h1 data-aos="fade-up" data-aos-delay="100">{{__('home.judul_slide_3')}}</h1>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="short-info">
                                    <h3 class="banner"  data-aos="fade-up" data-aos-delay="200">@lang('home.deskripsi_slide_3')</h3>
                                     <button class="default-btn" onclick="hubungiKami()" data-aos="fade-zoom-in" data-aos-delay="100">
                                        {{ __('home.contact_us') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="slider-content">
                    <img
                        src="{{asset('assets_home/img/banner/polresta-surakarta.webp')}}"
                        width="1920" height="960"
                        class="slider-img"
                        alt="Polresta Surakarta Upvc Splatinum"
                        loading="lazy"
                    >
                    <div class="content">
                        <div class="text">
                            <div class="container">
                                <h1 data-aos="fade-up" data-aos-delay="100">{{__('home.judul_slide_4')}}</h1>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="short-info">
                                    <h3 class="banner"  data-aos="fade-up" data-aos-delay="200">@lang('home.deskripsi_slide_4')</h3>
                                    <button class="default-btn" onclick="hubungiKami()" data-aos="fade-zoom-in" data-aos-delay="100">
                                        {{ __('home.contact_us') }}
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- End Clgun Slider Banner Area -->

        <!-- tentang splatinum Area -->
         <div class="tentang-splatinum-section">
            <div class="tentang-splatinum">
                <div class="container">
                    <div class="row align-items-center">

                        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                            <!-- <div class="sub-title">
                                <p>{{__('home.judul_tentang_splatinum')}}</p>
                            </div> -->
                            <div class="content">
                                <h2>{{__('home.h1_tentang_splatinum')}}</h2>
                                <h3>{{__('home.h3_professional_splatinum')}}</h3>
                                <p style="text-align:justify">{!! __('home.p_tentang_splatinum') !!}</p>
                                <span class="section-btn" data-aos="fade-zoom-in" data-aos-delay="100">
                                    <p> <a href="{{route('tentangkami',['lang'=> app()->getLocale()])}}">{{__('home.link_tentang_splatinum')}} <i class='bx bx-right-arrow-alt'></i></a></p>
                                </span>
                            </div>
                        </div>
                         <div class="col-lg-6" data-aos="fade-zoom-in" data-aos-delay="100">
                            <div class="tentang-images mt-4">
                                <img src="{{asset('assets_home/img/all-img/partisi-kaca-upvc.jpg')}}" alt="Partisi Kaca Upvc Splatinum" width="100%" height="auto">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tentang-splatinum-section">
            <div class="tentang-splatinum">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6" data-aos="fade-zoom-in" data-aos-delay="100">
                            <div class="tentang-images mt-4">
                                <img src="{{asset('assets_home/img/all-img/jendela-upvc-splatinum.jpg')}} " width="100%" height="auto" alt="Jendela Upvc Splatinum">
                            </div>
                        </div>
                        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                            <!-- <div class="sub-title">
                                <p>{{__('home.judul_tentang_splatinum')}}</p>
                            </div> -->
                            <div class="content">
                                <h2>{{__('home.judul_tentang_splatinum2')}}</h2>
                                <p style="text-align:justify">{{__('home.h3_professional_splatinum2')}}</p>
                                <span class="section-btn" data-aos="fade-zoom-in" data-aos-delay="100">
                                    <p> <a href="{{route('tentangkami',['lang'=> app()->getLocale()])}}">{{__('home.link_tentang_splatinum2')}} <i class='bx bx-right-arrow-alt'></i></a></p>
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- tentang splatinum  Area -->

        <!--  produk Area -->
        <div class="produk-kategori-area pt-100 ptb-100">
            <div class="container my-5">
                <h2 class="text-center mb-4">{{__('home.p_produk')}}</h2>
                 <p class="text-center mb-4" style="font-size: 16px;" class="section-description mb-5" data-aos="fade-down" data-aos-delay="200">
                    {{__('home.deskripsi_produk')}}
                </p>
                <!-- <div class="owl-carousel product-carousel owl-theme">
                    <div class="item">
                        <div class="product-card text-center">
                            <img src="{{asset('assets_home/img/all-img/pintu-swing.jpeg')}}" alt="Pintu Swing UPVC">
                            <h5 class="mt-2">Pintu Swing Upvc </h5>
                        </div>
                    </div>
                    <div class="item">
                        <div class="product-card text-center">
                            <img src="{{asset('assets_home/img/all-img/pintu-lipat.jpg')}}" alt="Pintu Lipat UPVC Splatinum">
                            <h5 class="mt-2">Pintu Lipat Upvc</h5>
                        </div>
                    </div>
                    <div class="item">
                        <div class="product-card text-center">
                            <img src="{{asset('assets_home/img/all-img/pintu-sliding.jpeg')}}" alt="Pintu Sliding UPVC Splatinum">
                            <h5 class="mt-2">Pintu Sliding Upvc</h5>
                        </div>
                    </div>
                    <div class="item">
                        <div class="product-card text-center">
                            <img src="{{asset('assets_home/img/all-img/jendelaswing.jpeg')}}" alt="Jendela Swing UPVC Splatinum">
                            <h5 class="mt-2">Jendela Swing Upvc </h5>
                        </div>
                    </div>
                    <div class="item">
                        <div class="product-card text-center">
                            <img src="{{asset('assets_home/img/all-img/jendelajungkit.jpeg')}}" alt="Splatinum Jendela Jungkit UPVC">
                            <h5 class="mt-2">Jendela Jungkit Upvc</h5>
                        </div>
                    </div>
                    <div class="item">
                        <div class="product-card text-center">
                            <img src="{{asset('assets_home/img/all-img/jendelasliding.jpeg')}}" alt="Jendela Sliding UPVC Splatinum">
                            <h5 class="mt-2">Jendela Sliding Upvc</h5>
                        </div>
                    </div>
                    <div class="item">
                        <div class="product-card text-center">
                            <img src="{{asset('assets_home/img/all-img/kaca-mati.jpeg')}}" alt="Kaca Mati UPVC Splatinum">
                            <h5 class="mt-2">Kaca Mati Upvc</h5>
                        </div>
                    </div>
                    <div class="item">
                        <div class="product-card text-center">
                            <img src="{{asset('assets_home/img/all-img/partisi-kaca.jpeg')}}" alt="Kaca Rumah UPVC Splatinum">
                            <h5 class="mt-2">Kaca Rumah Upvc</h5>
                        </div>
                    </div>
                </div> -->
                <div class="owl-carousel product-carousel owl-theme">
                    @foreach(__('home.produk') as $product)
                        <div class="item">
                            <div class="product-card text-center">
                                <img width="100%"
                                height="300px" src="{{ $product['gambar'] }}" alt="{{ $product['nama'] }}" loading="lazy">
                                <h5 class="mt-2">{{ $product['nama'] }}</h5>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex align-items-center justify-content-center mt-4">
                    <a class="default-btn text-center" href="{{route('produk',['lang' => app()->getLocale()])}}" data-aos="fade-zoom-in" data-aos-delay="100">
                    {{__('home.link_produk')}}  <i class='bx bx-right-arrow-alt'></i>
                    </a>
                </div>

            </div>
        </div>

        <!-- modal produk -->
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imageModalLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="modalImage" src="" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
            </div>

        <!-- end modal produk -->
        <!-- end produk Area 3 -->
        <!-- pemesanan Area 3 -->
        <div class="pemesanan-area ptb-100">
            <!-- Section Counter -->
             <div class="container">
                <h2 class="text-center  text-white">{{__('home.komitmen_judul')}}</h2>

             </div>
            <section class="counter-section">
                <div class="container">
                    <div class="counter-grid">
                        <div class="counter-box">
                            <i class='bx bx-task'></i>
                            <h2 class="counter" data-target="250" data-suffix="+">0</h2>
                            <p>{{__('home.counter1')}}</p>
                        </div>
                        <div class="counter-box">
                            <i class='bx bx-happy-alt'></i>
                            <h2 class="counter" data-target="98" data-suffix="%">0</h2>
                            <p>{{__('home.counter2')}}</p>
                        </div>
                        <div class="counter-box">
                            <i class='bx bx-package'></i>
                            <h2 class="counter" data-target="50" data-suffix="+">0+</h2>
                            <p>{{__('home.counter3')}}</p>
                        </div>
                        <div class="counter-box">
                            <i class='bx bx-user'></i>
                            <h2 class="counter" data-target="100" data-suffix="+">0+</h2>
                            <p>{{__('home.counter4')}}</p>
                        </div>
                    </div>
                </div>
            </section>

        </div>
        <!-- End pemesanan Area -->

        <!-- Start Quick Search Area -->
        <div class="courses-area style-3 ptb-100">
            <div class="container">
                <div class="row align-items-end">
                <section class="process-section">
                    <div class="container py-5">
                        <h2 class="text-center mb-4" data-aos="fade-up" data-aos-delay="100">{{__('home.official_shop')}} </h2>
                        <p class="text-center mb-5" data-aos="fade-up" data-aos-delay="100">
                           {{__('home.p_shop')}}
                        </p>
                        <div class="row">
                            <!-- Langkah 1: Pemesanan -->
                            <div class="col-md-6 mt-4" data-aos="fade-left" data-aos-delay="100">
                                <div class="icon-shop">
                                       <img src="{{asset('assets_home/img/icon/shoope.png')}}"  alt="Pemesanan UPVC Splatinum Indonesia ">
                                </div>
                                <a href="https://shopee.co.id/splatinum_skyreach" target="_blank" rel="nofollow">
                                    <h4>Shopee <i class='bx bx-chevrons-right'></i></h4>


                                </a>

                            </div>

                            <!-- Langkah 2: Proses -->
                            <div class="col-md-6 mt-4" data-aos="fade-left" data-aos-delay="100">
                                <div class="icon-shop">
                                   <img src="{{asset('assets_home/img/icon/tokped.png')}}"  alt="Proses Pemesanan Splatinum">
                                </div>
                                <a href="https://www.tokopedia.com/splatinum-indonesia" target="_blank" rel="nofollow">
                                    <h4>Tokopedia<i class='bx bx-chevrons-right'></i></h4>
                                </a>

                            </div>



                        </div>
                    </div>
                </section>
                </div>
            </div>
        </div>
        <!-- End Quick Search Area -->

        <!-- Mengapa memilih kami -->
         <section class="why-choose-us">
            <div class="container text-center">
                <h3 data-aos="fade-down" data-aos-delay="100">{{__('home.kenapa_memilih_kami')}}</h3>
                <p style="font-size: 16px;" class="section-description mb-5" data-aos="fade-down" data-aos-delay="200">
                    {{__('home.deskripsi_kenapa_memilih_kami')}}
                </p>
                <div class="row">
                    <!-- Karyawan Profesional -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card" data-aos="fade-up" data-aos-delay="200">
                            <i class="bx bx-user-check"></i>
                            <div class="card-body">
                                <h5 class="card-title">{{__('home.karyawan_profesional')}}</h5>
                                <p class="card-text">{{__('home.deskripsi_karyawan_profesional')}}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Produk Berkualitas -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card" data-aos="fade-up" data-aos-delay="300">
                            <i class="bx bx-award"></i>
                            <div class="card-body">
                                <h5 class="card-title">{{__('home.produk_berkualitas')}}</h5>
                                <p class="card-text">{{__('home.deskripsi_produk_berkualitas')}}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Pelayanan Cepat -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card" data-aos="fade-up" data-aos-delay="400">
                            <i class="bx bx-time-five"></i>
                            <div class="card-body">
                                <h5 class="card-title">{{__('home.pelayanan_cepat')}}</h5>
                                <p class="card-text">{{__('home.deskripsi_pelayanan_cepat')}}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Garansi & Keamanan -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card" data-aos="fade-up" data-aos-delay="500">
                            <i class="bx bx-shield"></i>
                            <div class="card-body">
                                <h5 class="card-title">
                                {{__('home.garansi_keamanan')}}
                                </h5>
                                <p class="card-text">{{__('home.deskripsi_garansi_keamanan')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end Mengapa memilih kami -->
        <!-- Testimonial section -->
        <div class="testimonial-section ptb-100">
            <div class="container">
                <h2 class="text-center text-white mb-5">{{__('home.testimonial_title')}}</h2>
                <div class="testimonial-slider owl-carousel">
                    <div class="testimonial-item">
                        <div class="wrapp-img-testi">
                            <img src="{{asset('assets_home/img/foto-testi/adam.jpg')}}" alt="testi upvc splatinum 1">
                        </div>
                        <p>{{__('home.testimonial1')}}</p>
                        <h5>Adam Dwi</h5>
                        <span>Pengusaha</span>
                    </div>
                    <div class="testimonial-item">
                        <div class="wrapp-img-testi">
                            <img src="{{asset('assets_home/img/foto-testi/syifa.png')}}" alt="testi upvc splatinum 2">
                        </div>
                        <p>{{__('home.testimonial2')}}</p>
                        <h5>Syifa Fauziah</h5>
                        <span>Freelancer</span>
                    </div>
                    <div class="testimonial-item">
                        <div class="wrapp-img-testi">
                            <img src="{{asset('assets_home/img/foto-testi/desi.png')}}" alt="testi upvc splatinum 3">
                        </div>
                        <p>{{__('home.testimonial3')}}</p>
                        <h5>Defsi Yulianasari</h5>
                        <span>Entrepreneur</span>
                    </div>
                    <div class="testimonial-item">
                        <div class="wrapp-img-testi">
                            <img src="{{asset('assets_home/img/foto-testi/ofi.png')}}" alt="testi upvc splatinum 4">
                        </div>
                        <p>{{__('home.testimonial4')}}</p>
                        <h5>Ofi Pay</h5>
                        <span>Ibu Rumah Tangga</span>
                    </div>
                    <div class="testimonial-item">
                        <div class="wrapp-img-testi">
                            <img src="{{asset('assets_home/img/foto-testi/putri.png')}}" alt="testi upvc splatinum 5">
                        </div>
                        <p>{{__('home.testimonial5')}}</p>
                        <h5>Putri Rahmatillah</h5>
                        <span>Warga</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonial section -->



        <!-- End Testimonial section -->




        <!-- Start Success Award Area -->
        <div class="lokasi-area ptb-100">
            <div class="container">
                <h2 class="text-center  mb-5">{{__('home.lokasi_title')}}</h2>
                <div class="row align-items-center ">
                    <div class="col-lg-6">
                        <div class="image" data-aos="fade-zoom-in" data-aos-delay="100">
                            <iframe title="Lokasi Toko Kami di Google Maps" class="text-center" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.7903328486113!2d106.98923839999999!3d-6.2912647999999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e698dde9c768b71%3A0x1ec36c50de28254!2sSPLATINUM%20INDONESIA!5e0!3m2!1sen!2sid!4v1740302540486!5m2!1sen!2sid"width="100%"  height="300" style="border:0;" allowfullscreen="1" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="content lokasi-card" data-aos="fade-up" data-aos-delay="100">

                            <p class="mb-4">
                            <h4>{{__('home.lokasi_alamat')}} </h4>
                            {{__('home.lokasi_detail')}}</p>
                            <hr>
                            <h4>{{__('home.lokasi_operasional')}} </h4>
                            <p>{{__('home.lokasi_jam_operasional')}} </p>


                        </div>
                    </div>


                </div>
            </div>
        </div>


@endsection

@section('js')
    <script>
    $(document).ready(function(){
        // carousel produk
        $(".product-carousel").owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            responsive: {
                0: { items: 1 },
                576: { items: 2 },
                768: { items: 3 },
                992: { items: 4 }
            }
        });

        // modal produk
        $(".product-card img").on("click", function () {
            var imgSrc = $(this).attr("src");
            var imgTitle = $(this).attr("alt");

            $("#modalImage").attr("src", imgSrc);
            $("#imageModalLabel").text(imgTitle);
            $("#imageModal").modal("show");
        });



        //counter section
        function startCounter(counter) {
            const target = +counter.getAttribute('data-target');
            const suffix = counter.getAttribute('data-suffix') || '';
            const speed = 2000; // Kecepatan animasi
            let count = 0;

            function updateCount() {
                if (count < target) {
                    count += Math.ceil(target / speed);
                    counter.innerText = count + suffix;
                    setTimeout(updateCount, 15);
                } else {
                    counter.innerText = target + suffix;
                }
            }

            updateCount();
        }

        // Gunakan Intersection Observer agar animasi hanya berjalan saat elemen terlihat
        const counters = document.querySelectorAll('.counter');
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    startCounter(entry.target);
                    observer.unobserve(entry.target); // Hanya berjalan sekali
                }
            });
        }, { threshold: 0.5 });

            counters.forEach(counter => observer.observe(counter));
        });

        // testimonial section
        $(".testimonial-slider").owlCarousel({
            loop: true,
            margin: 20,
            nav: false,
            dots: true,
            autoplay: true,
            autoplayTimeout: 4000,
            responsive: {
                0: { items: 1 },
                768: { items: 2 },
                1024: { items: 3 }
            }
        });
        // end testimonial section
    </script>
@endsection