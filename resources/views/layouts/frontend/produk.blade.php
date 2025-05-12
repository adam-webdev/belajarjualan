@extends('layouts.frontend.layout_homemaster')
@section('title', 'Produk Pintu dan Jendela Berbahan Upvc dan Alumunium')

@section('canonical', url()->current())
@section('og_url', url()->current())




@section('css')
<!-- Start Section Produk Area -->
<style>

    .section-title {
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 30px;
        color: var(--mainColor);
    }

    /* Filter Kategori */
    .produk-filter {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 30px;
    }

    .filter-btn {
        background: var(--secondColor);
        color: #fff;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        transition: 0.3s ease;
        border-radius: 5px;
    }

    .filter-btn.active,
    .filter-btn:hover {
        background: var(--mainColor);
    }

    .produk-section{
        margin-top: 120px;
        margin-bottom: 120px;
    }
   .produk-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 20px;
        padding: 20px;
    }

    .produk-item {
        background: #fff;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .produk-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }

    .produk-img-wrapper {
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
    }

    .produk-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .product-content {
        padding: 8px;
    }

    .product-title {
        font-size: 14px;
        font-weight: 600;
        margin: 10px 0 5px;
        color: #333;
    }

    .product-price {
        font-size: 13px;
        color: #555;
        margin-bottom: 10px;
    }

    /* .product-button {
        background-color: var(--mainColor);
        color: #fff;
        padding: 8px 15px;
        border-radius: 8px;
        cursor: pointer;
        display: inline-block;
        transition: background 0.3s ease, transform 0.2s ease;
    } */

    .product-button:hover {
        background-color: #07478df6;
        transform: translateY(-2px);
    }

    .filter-dropdown {
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ddd;
        margin-bottom: 15px;
    }

    .tab-kategori {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 30px;
    }

    .tab-btn {
        background: #ddd;
        color: #333;
        border: none;
        padding: 6px 8px;
        cursor: pointer;
        transition: 0.3s ease;
        font-size: 14px;
    }

    .tab-btn.active,
    .tab-btn:hover {
        background: var(--mainColor);
        color: #fff;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .produk-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .tab-kategori {
            display: none;
        }
        .filter-dropdown {
            width: 100%;
        }
        .produk-grid {
            grid-template-columns: 1fr;
        }

        .produk-filter {
            flex-wrap: wrap;
        }
    }

</style>




@endsection
@section("content_home")
<section id="produk" class="produk-section">
    <div class="container">
        <h2 class="section-title">Produk</h2>

        <!-- Dropdown Kategori -->
        <select id="filterKategori" class="filter-dropdown">
            <option value="">Semua Kategori</option>
            @foreach($kategori as $kat)
                <option value="{{ $kat->slug }}" {{ request('kategori') == $kat->slug ? 'selected' : '' }}>
                    {{ $kat['name_'.app()->getLocale()] }}
                </option>
            @endforeach
        </select>

        <!-- Tab Kategori -->
        <div class="tab-kategori">
            <button class="tab-btn {{ request('kategori') == '' ? 'active' : '' }}" data-filter="">Semua</button>
            @foreach($kategori as $kat)
                <button class="tab-btn {{ request('kategori') == $kat->slug ? 'active' : '' }}"
                        data-filter="{{ $kat->slug }}">
                    {{ $kat['name_'.app()->getLocale()] }}
                </button>
            @endforeach
        </div>

        <!-- Grid Produk -->
        <div id="produkGrid" class="produk-grid">
        @foreach($produk as $p)
            <div class="produk-item kategori-{{ $p->kategori->slug }}"
                style="{{ request('kategori') && request('kategori') != $p->kategori->slug ? 'display: none;' : '' }}">
                <a href="{{ route('produk.slug', ['lang' => app()->getLocale(), 'slug' => $p->slug]) }}">
                    <div class="produk-img-wrapper">
                        <img src="/storage/{{ $p->foto_produk }}" alt="{{ $p->name_id }}">
                    </div>
                    <div class="product-content">
                        <p class="product-title">{{ Str::limit($p['nama_produk_'.app()->getLocale()], 35, '...') }}</p>
                        <p class="product-price">
                            {{ $p->harga !== null ? 'Rp. ' . number_format($p->harga, 0, ',', '.') : 'Harga belum tersedia' }}
                        </p>

                        <!-- <div class="product-button">
                            <span>Lihat Detail</span>
                        </div> -->
                    </div>
                </a>
            </div>
        @endforeach
        </div>

    </div>
</section>
<hr>

     <!-- Start Section Banner Area -->
        <!-- <div class="section-banner bg-1">
            <div class="container">
                <div class="banner-spacing">
                    <div class="section-info">
                        <h2 data-aos="fade-up" data-aos-delay="100">{{ __('produk.banner_title') }}</h2>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- End Section Banner Area -->




@endsection

@section('js')
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const kategoriDropdown = document.getElementById("filterKategori");
        const kategoriButtons = document.querySelectorAll(".tab-btn");

        function applyFilter(slug) {
            let url = new URL(window.location.href);
            if (slug) {
                url.searchParams.set('kategori', slug);
            } else {
                url.searchParams.delete('kategori');
            }
            window.location.href = url.toString();
        }

        // Event listener untuk dropdown kategori
        kategoriDropdown.addEventListener("change", function () {
            applyFilter(this.value);
        });

        // Event listener untuk tombol kategori
        kategoriButtons.forEach(btn => {
            btn.addEventListener("click", function () {
                applyFilter(this.getAttribute("data-filter"));
            });
        });
    });

    </script>
@endsection