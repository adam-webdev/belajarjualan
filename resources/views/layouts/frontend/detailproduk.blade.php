
@extends('layouts.frontend.layout_homemaster')
@section('title', $produk['nama_produk_id'] . ' | Detail Produk Upvc Splatinum')

@section('meta_description', $produk['nama_produk_'.app()->getLocale()])
@section('og_image', asset('storage/' . $produk->foto_produk))
@section('twitter_image', asset('storage/' . $produk->foto_produk))
@section('canonical', route('produk.slug', ['lang' => app()->getLocale(), 'slug' => $produk->slug]))


@section('css')
<style>

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
    padding: 0 20px;
    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);

  }
  .content span.content-deskripsi{
    font-family: var(--fontFamily);
  }
  .content span.content-title{
    font-family: var(--fontFamily);
    font-weight: bold;
  }
  .content span.content-price{
    font-family: var(--fontFamily);
    font-weight: bold;
    color: var(--mainColor);
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
      flex-wrap: wrap;
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
    max-width: 250px;
    height: 250px;
    object-fit: cover;
  }

  /* rekomendasi */
  /* Grid Produk */
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
        /* Responsive */
  @media (max-width: 1024px) {
      .produk-grid {
          grid-template-columns: repeat(2, 1fr);
      }
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
    .produk-grid {
        grid-template-columns: 1fr;
    }

    .produk-filter {
        flex-wrap: wrap;
    }
  }

  @media (max-width: 480px) {
    .produk-grid {
      grid-template-columns: 2fr;
    }
    .produk-detail img {
      max-width: 200px;
    }
    .produk-detail img {
      width: 100%;
      max-width: 100%;
    }
  }

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
    top: 45%;
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



     <!-- Start Section Produk Area -->
    <div class="container mt-4 pt-100">
      <div class="detail-produk-area" >
          <!-- <h4 class="mb-4">Detail Produk</h4> -->
          <div class="row detail-area">
            <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="200" >
                <div class="image-utama mt-4" style="position: sticky!important;">
                    <img id="mainImage" src="/storage/{{$produk->foto_produk}}" alt="image" onclick="openModal()">
                </div>
            </div>
            <div class="col-md-6 mt-4">
                <span class="content">
                    <h3>{{$produk['nama_produk_'.app()->getLocale()]}}</h3>
                    <span class="minimal">
                        <i class='bx bx-info-circle'></i>  {{ __('detailproduk.minimal') }}
                    </span>
                    <span class="content-area">
                      <span class="content-price">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</span>
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
      </div>
      <!-- Foto Lainnya -->
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
            <div class="produk-item">
              <a href="{{route('produk.slug', ['lang' => app()->getLocale(), 'slug' => $p->slug])}}">
                  <div class="produk-img-wrapper">
                        <img src="/storage/{{ $p->foto_produk }}" alt="{{ $p->name_id }}">
                    </div>
                    <div class="product-content">
                        <p class="product-title">{{ Str::limit($p['nama_produk_'.app()->getLocale()], 35, '...') }}</p>
                        <p class="product-price">Rp. 800.000</p>
                        <!-- <div class="product-button">
                            <span>Lihat Detail</span>
                        </div> -->
                    </div>
              </a>
            </div>
            @endforeach
        </div>
      @endif
    </div>
<hr>

@endsection

@section('js')
  <script>
    function changeImage(src) {
        document.getElementById("mainImage").src = src;
    }

    function openModal() {
        let modal = document.getElementById("imageModal");
        let modalImg = document.getElementById("modalImage");
        let mainImg = document.getElementById("mainImage");
        modal.style.display = "block";
        modalImg.src = mainImg.src;

    }
    function openModalDetail(imageSrc) {
      document.getElementById("modalImage").src = imageSrc;
      document.getElementById("imageModal").style.display = "flex";
    }
    function sendWhatsAppMessage() {
        let productName = document.querySelector(".content h3").innerText;
        let message = encodeURIComponent(`Halo, saya ingin menanyakan produk *${productName}* apakah produk tersedia?`);
        let phoneNumber = "6281214155598"; // Nomor WhatsApp tujuan
        let waUrl = `https://wa.me/${phoneNumber}?text=${message}`;

        if (/Android|iPhone/i.test(navigator.userAgent)) {
            waUrl = `https://wa.me/${phoneNumber}?text=${message}`;
        }
        window.open(waUrl, "_blank");
    }
    function closeModal() {
        document.getElementById("imageModal").style.display = "none";
    }

      document.addEventListener("DOMContentLoaded", function () {
      const produkCard = document.querySelector(".detail-produk-card");
      const contentArea = document.querySelector(".content");

      if (produkCard && contentArea) {
          function checkSticky() {
              const contentRect = contentArea.getBoundingClientRect();
              const produkRect = produkCard.getBoundingClientRect();

              // Jika konten di sebelah kanan habis, nonaktifkan sticky
              if (contentRect.bottom < produkRect.bottom) {
                  produkCard.style.position = "absolute";
                  produkCard.style.bottom = "0";
              } else {
                  produkCard.style.position = "sticky";
                  produkCard.style.top = "100px"; // Sesuaikan dengan tinggi navbar
              }
          }

          // Panggil fungsi saat scroll dan saat resize
          window.addEventListener("scroll", checkSticky);
          window.addEventListener("resize", checkSticky);
          checkSticky(); // Panggil sekali saat halaman dimuat
        }


      });

</script>
@endsection