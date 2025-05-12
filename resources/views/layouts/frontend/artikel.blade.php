@extends('layouts.frontend.layout_homemaster')
@section('title', 'Artikel Splatinum Upvc dan Alumunium')
@section('description', 'Artikel Splatinum Upvc dan Alumunium')
@section('canonical', url()->current())
@section('og_url', url()->current())
@section('css')
  <style>

    .pagination .page-link {
      border: 1px solid var(--mainColor);
      font-size: 0.875rem; /* sesuaikan ukuran font jika diperlukan */
      padding: 0.5rem 0.75rem; /* atur padding agar ukurannya tidak terlalu besar */
    }

    .pagination .page-item.active .page-link {
      background-color: var(--mainColor) !important;
      border-color: var(--mainColor) !important;
    }

    .pagination .page-link:hover {
      background-color: var(--mainColor) !important; /* jika ingin warna tetap sama saat hover */
      color: #fff !important;
    }

    .card-artikel{
      padding: none;
      box-sizing: border-box;
      box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);

    }
    /* .card-artikel:hover{
      box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
    } */

    .card-artikel-body{
      padding: 0 10px 10px 10px;
    }
    .card-artikel-body h5{
      margin-top: 10px;
      color: var(--secondColor)!important;
    }
    .card-artikel-body span{
      font-size: 12px;
      padding: 0;
    }
    .card-artikel-body p{
      font-size: 14px;
      padding: 0;
      margin: 0;
    }
    a.btn-artikel{
      display: inline-block;
      border: 1px solid var(--mainColor);
      padding:6px 10px;
      color: var(--mainColor);
      font-size: 14px;
      text-align: center;
      width: 100%;
      margin-top: 10px;
      border-radius: none;
    }
    a.btn-artikel:hover{
      border: 1px solid var(--secondColor);
      color: var(--secondColor);

    }

    .card-artikel .img-artikel{
      width: 100%;
      height: 200px;
    }
    .card-artikel .img-artikel img{
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .filter-card{
      background-color: #fff;
      cursor: pointer;
      box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
    }
    .filter-card select{
      cursor: pointer;
    }
    .btn-filter{
      background-color: var(--mainColor);
      padding: 4px 8px;
      border-radius: 4px;
      color: #fff;
      outline: none;
      border: none;
      text-decoration: none;
      margin-top: 10px;
      transition: .5s ease-in;
    }
    .btn-filter:hover{
      background-color: var(--secondColor);
    }

    .input-query{
      width: 100%;
      padding: 6px 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      outline: none;
    }
    .btn-load-more {
        background-color: var(--mainColor);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-load-more:hover {
        background-color: var(--secondColor);
    }
  </style>

@endsection
@section('content_home')
<div class="container pt-100 ptb-100">
    <div class="row">
        <!-- Konten Artikel -->
        <div class="col-lg-9">
            <h4 class="mb-4 text-dark">Artikel Terbaru</h4>
                  @if($artikel->isEmpty())
                    <div class="alert alert-warning" role="alert">
                        Artikel tidak ditemukan
                    </div>
                  @endif
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($artikel as $art)
                <div class="col">
                    <div class="card-artikel">
                      <div class="img-artikel">
                        <img src="/storage/{{ $art->banner }}"  alt="{{ $art->judul }}" class="img-fluid">
                      </div>

                        <div class="card-artikel-body">
                            <h5 class="card-title">
                                <a href="#" class="text-decoration-none ">
                                     {{ $art->judul }}
                                </a>
                            </h5>
                            <span class="text-muted small">Ditulis oleh <strong>{{$art->user->name}}</strong> •
                            {{ \Carbon\Carbon::parse($art->tanggal)->translatedFormat('d F Y') }}
                            </span>
                            <a href="{{route('artikel.slug', ['lang' => app()->getLocale(), 'slug' => $art->slug])}}" class="btn-artikel">
                           Baca selengkapnya</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-4">
                <!-- {{ $artikel->links() }} -->
                {!! $artikel->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
        </div>


        <!-- Sidebar -->
        <div class="col-lg-3 mt-4">
          <div id="filter-sidebar" class=" mt-4" style="top: 100px;">
                <!-- Filter Tahun & Bulan -->
                <div class="mb-4 filter-card p-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Filter Tahun & Bulan</h5>
                        <form method="GET" action="{{ route('artikel',['lang'=>app()->getLocale()]) }}">

                            <select class="form-select mb-2" name="bulan">
                                <option value="">Bulan</option>
                                @foreach ([
                                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                                    '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                                    '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                                    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                ] as $num => $month)
                                    <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                              </select>
                              <select class="form-select " name="tahun">
                                  <option value="">Tahun</option>
                                  @for ($i = now()->year - 5; $i <= now()->year + 5; $i++)
                                      <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                  @endfor
                              </select>
                            <button type="submit" class="btn-filter w-100">Terapkan</button>

                          </form>
                    <div class="card-body mt-4">
                      <h5 class="card-title fw-bold">Filter Pencarian</h5>
                      <form method="GET" action="{{ route('artikel',['lang'=>app()->getLocale()]) }}">
                        <input type="text" class="input-query " name="query" placeholder="Cari judul.." value="{{ request('query') }}">
                          <button type="submit" class="btn-filter w-100">Cari</button>
                      </form>
                    </div>


                    </div>
                </div>
          </div>
          <div id="filter-sidebar" class=" mt-4" style="top: 100px;">
              <!-- Filter Tahun & Bulan -->
              <div class="mb-4 filter-card p-4">
                  <h5 class="card-title fw-bold">Reset Filter</h5>
                  <a href="{{ route('artikel',['lang'=>app()->getLocale()]) }}">
                    <button class="btn-filter w-100">Reset</button>
                  </a>
              </div>
          </div>

        </div>
    </div>
    <!-- <div class="row">
      <div class="col-md-9">
           <div id="load-more-container" class="text-center mt-4">
            @if ($artikel->hasMorePages())
                <button id="load-more" class="btn btn-load-more" data-page="{{ $artikel->currentPage() + 1 }}">
                    Lihat Artikel Lainnya
                </button>
            @endif
        </div>
      </div>
    </div> -->
</div>

<hr>
@endsection
@section('js')
  <script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("form").addEventListener("submit", function (event) {
        let form = event.target;
        let url = new URL(form.action);
        let params = new URLSearchParams(new FormData(form));

        // Hapus parameter yang kosong
        for (let [key, value] of params.entries()) {
            if (!value) {
                params.delete(key);
            }
        }

        // Redirect ke URL yang sudah dibersihkan
        event.preventDefault();
        window.location.href = url.pathname + "?" + params.toString();
    });


    document.getElementById("load-more")?.addEventListener("click", function () {
        let button = this;
        let nextPage = button.getAttribute("data-page");
        let url = "{{ route('artikel', ['lang' => app()->getLocale()]) }}" + "?page=" + nextPage;

        button.innerHTML = "Loading...";
        button.disabled = true;

        fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
            .then(response => response.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, "text/html");
                let newArticles = doc.querySelector(".row-cols-md-3");

                if (newArticles) {
                    document.querySelector(".row-cols-md-3").insertAdjacentHTML("beforeend", newArticles.innerHTML);
                    let newPage = parseInt(nextPage) + 1;
                    if (doc.querySelector("#load-more")) {
                        button.setAttribute("data-page", newPage);
                        button.innerHTML = "Show More";
                        button.disabled = false;
                    } else {
                        button.remove();
                    }
                }
            });
    });
});

</script>
@endsection
