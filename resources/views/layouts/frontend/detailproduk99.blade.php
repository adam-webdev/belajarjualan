@extends('layouts.frontend.layout_homemaster')
@section('title', 'Detail Produk')
@section('meta_description', $produk['nama_produk_'.app()->getLocale()])
@section('og_image', asset('storage/' . $produk->foto_produk))
@section('twitter_image', asset('storage/' . $produk->foto_produk))
@section('canonical', route('products.slug', ['lang' => app()->getLocale(), 'slug' => $produk->slug]))


@section('css')
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .navbar{
          width: 100%;
          height: 100px;
          background-color: #f8f8f8;
          border:1px solid red;
          position: fixed;
        }
        .wrapper {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            gap: 20px;
            padding: 20px;
        }
        .main {
            width: 60%;
            min-height: 1000px;
            background: #f8f8f8;
            padding: 20px;
            border: 3px solid black;
        }
        .sidebar-container {
            width: 25%;
            position: relative;
        }
        .sidebar {
            width: 100%;
            height: 300px;
            padding: 15px;
            background-color: #fff;
            border: 3px solid black;
            position: sticky;
            top: 120px;
        }
        .next-section {
            background: #632020;
            padding: 20px;
            height: 500px;
            margin-top: 50px;
        }

        .image-utama{
    display: flex;
    justify-content: center;
    padding: 10px;
    cursor: pointer;
    width: 100%;
    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
    height: 400px;

  }
  .image-utama img{
    width: 100%;
    object-fit: cover;
  }
  .content{
    padding: 20px;
    /* box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1); */

  }
  .thumbnail-container{
    margin-top:10px;
    display: flex;
    border: 1px solid var(--secondaryColor);
    justify-content: center;
    align-items: center;
    padding:10px 16px;
    gap:6px;
  }
  .thumbnail {
    width: 100px;
    height: 100px;
    cursor: pointer;
    border-radius: 5px;
    object-fit: cover;
    border: 1px solid var(--paragraphColorSecond);
  }
  .thumbnail:hover {
    border: 2px solid var(--mainColor);
  }
  .section-foto{
    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
    padding:20px;
  }
  .produk-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 15px;
      justify-content: center;
  }

    .produk-detail {
      position: relative;
      overflow: hidden;
      cursor: pointer;
      transition: transform 0.3s ease-in-out;
    }
    .produk-detail:hover img {
      transition: .6s;
      transform: scale(1.1);
    }
    .produk-detail img {
      width: 100%;
      max-width:250px;
      height: 250px;
      object-fit: cover;
    }


    /* rekomendasi */
    /* Grid Produk */
    .produk-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));

        gap: 20px;
        justify-content: center;
    }

    .produk-item {
        background: #fff;
        max-width: 250px;
        padding: 15px;
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
        transition: 0.3s ease;
    }

    .produk-item:hover {
        transform: translateY(-5px);
        box-shadow: 0px 12px 20px rgba(0, 0, 0, 0.2);
    }

    .produk-item img {
        width: 100%;
        max-width: 250px;
        height: 200px;
        object-fit: cover;
        margin-bottom: 10px;
    }

    .produk-item h3 {
        font-size: 16px;
        font-weight: bold;
        color: var(--mainColor);
        text-align: center;
    }

    .tokopedia-btn {
      background-color: #05B34A; /* Warna Tokopedia */
      color: #fff;
      border: none;
      padding: 8px 20px;
      transition: background-color 0.3s ease;
      width: 100%;
    }

    .tokopedia-btn:hover {
      background-color: #049b3c; /* Warna saat hover */
    }

    .shopee-btn {
      background-color: #f53d2d; /* Warna Shopee */
      color: #fff;
      border: none;
      padding: 8px 20px;
      transition: background-color 0.3s ease;
      width: 100%;
    }

    .shopee-btn:hover {
      background-color: #e03a25; /* Warna saat hover */
    }
  @media (max-width: 768px) {
    .image-utama{
      width: 100%;
      height: 400px;
    }
    .thumbnail {
      width: 90px;
      height: 90px;
      margin-right: 5px;
    }
  }

  /* @media (max-width: 480px) {
    .image-utama img{
      width: 250px;
      height: 350px;
    }
  } */

  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
  }
  .modal-content {
    display: block;
    max-width: 90%;
    max-height: 90%;
    margin: auto;
    top: 50%;
    background-color: transparent;
    transform: translateY(-50%);
    position: relative;
    object-fit: contain;
    image-rendering: high-quality;
  }
  .close {
    position: absolute;
    top: 15px;
    right: 25px;
    color: white;
    font-size: 35px;
    cursor: pointer;
}
    </style>
    @endsection
@section("content_home")
<!-- Modal Overlay -->
    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <div class="wrapper row">
        <div class="sidebar-container">
            <div class="sidebar" id="sticky-sidebar">
                <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="200" >
                <div class="image-utama mt-4" style="position: sticky!important;">
                    <img id="mainImage" src="/storage/{{$produk->foto_produk}}" alt="image" onclick="openModal()">
                </div>
            </div>
            </div>
        </div>
        <div class="main" id="main-content">
             <div class="col-md-6 mt-4">
                <span class="content">
                    <h3>{{$produk['nama_produk_'.app()->getLocale()]}}</h3>
                    <span class="minimal">
                        <i class='bx bx-info-circle'></i>  {{ __('detailproduk.minimal') }}
                    </span>
                    <span class="content-area">
                      <span class="content-title">Deskripsi :</span>
                    </span>
                    <span class="content-area">
                      <span class="content-deskripsi">{!!$produk['nama_deskripsi_'.app()->getLocale()]!!}</span>
                    </span>

                    <a href="#" onclick="sendWhatsAppMessage()">
                      <button  class="btn-detail-produk">{{ __('detailproduk.tombol_pesan') }}</button>
                    </a>
                    <div class="row mt-2">
                    @if($produk->link_tokped)
                      <div class="col-12  mb-2">
                        <a href="{{ $produk->link_tokped }}" target="_blank">
                          <button class="tokopedia-btn">
                            <i class="bx bx-cart" style="font-size: 18px;"></i> Tokopedia
                          </button>
                        </a>
                      </div>
                    @endif

                    @if($produk->link_shopee)
                      <div class="col-12  mb-2">
                        <a href="{{ $produk->link_shopee }}" target="_blank">
                          <button class="shopee-btn">
                            <i class="bx bx-cart" style="font-size: 18px;"></i> Shopee
                          </button>
                        </a>
                      </div>
                    @endif
                  </div>

                </span>
            </div>
        </div>
        <div class="row pt-100 ptb-100">
          <section class="section-foto">
                <h5 class="card-title">Foto Lainnya</h5>
                <div class="produk-container">

                  <!-- Foto Lainnya -->
                  @foreach($produkimages as $foto)
                    <div class="produk-detail" onclick="openModalDetail('/storage/{{$foto->foto_lainnya}}')">
                      <img src="/storage/{{$foto->foto_lainnya}}" alt="Foto Produk Upvc Splatinum Lainnya">
                    </div>
                  @endforeach
                </div>
          </section>
        </div>
        <!-- Grid Produk -->
      @if($rekomendasi->count() > 0)
        <h4>Rekomedasi Produk</h4>
        <div id="produkGrid" class="produk-grid mt-4">
            @foreach($rekomendasi as $p)
            <a href="{{route('products.slug', ['lang' => app()->getLocale(), 'slug' => $p->slug])}}">
                <div class="produk-item kategori-{{ $p->kategori->slug }}"
                    style="{{ request('kategori') && request('kategori') != $p->kategori->slug ? 'display: none;' : '' }}">
                    <img src="/storage/{{ $p->foto_produk }}" alt="{{ $p->name_id }}">
                    <h3>
                        {{ $p['nama_produk_'.app()->getLocale()] }}
                    </h3>
                </div>
            </a>
            @endforeach
        </div>
      @endif
    </div>
    <!-- <div class="next-section">Bagian bawah setelah sticky berhenti</div> -->
    <script>
        window.addEventListener("scroll", function () {
            let sidebar = document.getElementById("sticky-sidebar");
            let mainContent = document.getElementById("main-content");
            let sidebarContainer = document.querySelector(".sidebar-container");
            let rect = mainContent.getBoundingClientRect();
            let sidebarRect = sidebar.getBoundingClientRect();
            let windowHeight = window.innerHeight;

            if (rect.bottom <= sidebarRect.height + 20) {
                sidebar.style.position = "absolute";
                sidebar.style.top = (mainContent.offsetHeight - sidebar.offsetHeight) + "px";
            } else {
                sidebar.style.position = "sticky";
                sidebar.style.top = "120px";
            }
        });
    </script>
</body>
</html>
