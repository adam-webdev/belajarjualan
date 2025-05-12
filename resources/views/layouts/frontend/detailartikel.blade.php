@extends('layouts.frontend.layout_homemaster')

@section('title', $artikel->judul .  '| Splatinum Pintu dan Jendela Upvc Alumunium | ')


@section('meta_description', $artikel->meta['judul'])
@section('og_image', asset('storage/' . $artikel->banner))
@section('twitter_image', asset('storage/' . $artikel->banner))
@section('canonical', route('artikel.slug', ['lang' => app()->getLocale(), 'slug' => $artikel->slug]))



@section('css')
    <style>
    /* .img-artikel{
        width: 100%;
        height: 400px;
    }
    .img-artikel img{
        width: 100%;
        height: 100%;
        object-fit: cover;
    } */
    .breadcrumb {
        --bs-breadcrumb-divider: ">";
    }
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
    }
     .img-artikel {
        width: 100%;
        /* height dihapus agar mengikuti aspect ratio alami */
        overflow: hidden;
    }

    .img-artikel img {
        width: 100%;
        height: auto; /* penting! agar tinggi otomatis */
        display: block;
    }
    .filter-card{
        background-color: #fff;
        cursor: pointer;
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
    }
    .filter-card select{
        cursor: pointer;
    }

    .artikel-terkait .list-group-item {
        padding: 15px;
        border: none;
    }

    .artikel-terkait .img-terkait {
        width: 100%;
        height: 120px;
        overflow: hidden;
    }
    .artikel-terkait .img-terkait:hover img{
        transform: scale(1.1);
        transition: transform 0.5s;
    }

    .artikel-terkait .img-terkait img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .artikel-terkait .list-group-item a {
        display: flex;
        flex-direction: column;
        text-decoration: underline!important;
    }

    .artikel-terkait .list-group-item span {
        font-weight: bold;
        margin-top: 5px;
        color: var(--mainColor);
    }
    .content iframe{
        width: 100%;
    }
    .icon-share > a > i {
        font-size: 20px;
        color: var(--mainColor);
    }
    @media (max-width: 768px) {
        nav .breadcrumb  {
          padding-top: 30px;
        }
    }
</style>


@section('content_home')

<div class="container mt-4 pt-100 ptb-100">
    <div class="row">
        <!-- Bagian Kiri: Detail Artikel -->
        <div class="col-md-9">

              <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="mb-3 ">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/id/artikel">Artikel</a></li>

                </ol>
            </nav>

            <!-- Gambar Artikel -->

                <!-- Tombol Share + Eye -->
            <div class="d-flex gap-4">
                <div class="d-flex align-items-center gap-2  m-2 icon-share">
                    <!-- Tombol Share -->
                     <small>Share On :</small>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::url()) }}" target="_blank" class="text-dark">
                        <i class='bx bxl-facebook-circle'></i>
                        <!-- <i class="bi bi-facebook fs-5"></i> -->
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($artikel->slug . ' ' . Request::url()) }}" target="_blank" class="text-dark">
                        <!-- <i class="bi bi-whatsapp fs-5"></i> -->
                        <i class='bx bxl-whatsapp' ></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(Request::url()) }}&text={{ urlencode($artikel->slug) }}" target="_blank" class="text-dark">
                        <!-- <i class="bi bi-twitter-x fs-5"></i> -->
                        <i class='bx bxl-twitter'></i>
                    </a>


                </div>
                <!-- Icon Eye -->
                <div class="d-flex align-items-center">
                    <!-- <i class="bi bi-eye fs-6 me-1"></i> -->
                    <!-- <small>{{ $artikel->views }}</small> -->
                        <i class='bx bx-show me-2' ></i>
                    <small> {{$artikel->views}} pembaca</small>
                </div>
            </div>

            <div class="img-artikel">
                <img src="/storage/{{ $artikel->banner}}" class="card-img-top" alt="{{ $artikel->judul }}">
            </div>
            <h1 class="card-title">{{ $artikel->judul }}</h1>
            <p class="text-muted">Ditulis oleh: {{ $artikel->user->name }} | {{ $artikel->created_at->translatedFormat('d F Y') }}</p>
            <div class="content" style="padding:0;margin:0">
                {!! $artikel->content !!}
            </div>
        </div>

        <!-- Bagian Kanan: Filter & Artikel Terkait -->
        <div class="col-md-3">
            <!-- Filter Pencarian -->


            <!-- Artikel Terkait (Statis) -->
            <!-- <div class="mb-4 filter-card p-4 mt-3">
                <div class="artikel-terkait">
                    <div class="card-header">Artikel Terkait</div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="#">Judul Artikel 1</a></li>
                        <li class="list-group-item"><a href="#">Judul Artikel 2</a></li>
                        <li class="list-group-item"><a href="#">Judul Artikel 3</a></li>
                    </ul>
                </div>
            </div> -->
            <!-- Artikel Terkait -->
            @if ($artikel_terkait->count() > 0)
            <div class="mb-4 filter-card  mt-3">
                <div class="artikel-terkait">
                    <div class="card-header fw-bold " style="padding-left: 14px; padding-top: 14px;">Artikel Terkait</div>
                    <ul class="list-group list-group-flush">
                        @foreach ($artikel_terkait as $terkait)
                        <li class="list-group-item">
                            <a href="{{ route('artikel.slug', ['lang' => app()->getLocale(), 'slug' => $terkait->slug]) }}" class="text-decoration-none">
                                <div class="d-flex  flex-column">
                                    <div class="img-terkait" >
                                        <img src="{{ asset('storage/' . $terkait->banner) }}" alt="{{ $terkait->judul }}" >
                                    </div>
                                    <span>{{ $terkait->judul }}</span>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<hr>
@endsection
