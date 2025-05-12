@extends('layouts.frontend.layout_homemaster')
@section('title', 'Tentang Kami | Menyediakan produk Pintu dan Jendela Berbahan Upvc dan Alumunium')

@section('css')
  <style>
    .about-section {
        margin-top:40px;
        padding: 100px 0;
        background-color: #f9f9f9;
    }

    .about-content {
        display: flex;
        justify-content: space-between;
        gap: 40px;
    }

    .about-text {
        flex: 1;
        max-width: 50%;
    }

    .about-text .section-title {
        font-size: 32px;
        font-weight: bold;
        color: var(--mainColor);
        margin-bottom: 15px;
    }

    .about-text p {
        font-size: 16px;
        line-height: 1.6;
        color: #333;
        text-align: justify;
        margin-bottom: 10px;
    }

    .about-list {
        list-style: none;
        padding: 0;
        margin-top: 15px;
    }

    .about-list li {
        font-size: 16px;
        color: #444;
        margin-bottom: 8px;
    }

    .about-image {
        flex: 1;
        max-width: 45%;
    }

    .about-image img {
        width: 100%;
        max-height: 400px;
        padding: 10px;
        object-fit: cover;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15), 0 2px 4px rgba(0, 0, 0, 0.08);

    }

    .visi-misi-section {
      background-color: var(--mainColor);
      color: #fff;
      padding: 80px 0;
      text-align: center;
    }

    .section-title {
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 30px;
        color: var(--whiteColor);
    }

    .visi-misi-content {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
    }

    .visi-box, .misi-box {
        background: rgba(255, 255, 255, 0.1);
        padding: 30px;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
        width: 45%;
        transition: transform 0.3s ease, background 0.3s ease;
    }

    .visi-box:hover, .misi-box:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.2);
    }

    .visi-box h3, .misi-box h3 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 15px;
        color: var(--secondColor);
    }

    .visi-box p {
        font-size: 16px;
        color: #fff;
        line-height: 1.6;
    }

    .misi-box ul {
        list-style: none;
        padding: 0;
    }

    .misi-box li {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .direksi-section {
        background-color: #f8f9fa;
        padding: 80px 0;
        text-align: center;
    }

    .section-title {
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 30px;
        color: var(--mainColor);
    }

    .direksi-content {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .direksi-item {
        background: #fff;
        padding: 20px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15), 0 2px 4px rgba(0, 0, 0, 0.08);
        text-align: center;
        width: 250px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .direksi-item:hover {
        transform: translateY(-5px);
        box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.2);
    }

    .direksi-item img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
    }

    .direksi-item h3 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
        color: var(--secondColor);
    }

    .direksi-item p {
        font-size: 14px;
        color: #555;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .about-content {
          flex-direction: column;
          text-align: center;
      }
      .about-text, .about-image {
          max-width: 100%;
      }
        .about-text .section-title {
          font-size: 20px;
        }
      .about-text p {
        font-size: 14px;
      }
      .about-list li {
        font-size: 14px;
        text-align: start;
      }
      .visi-misi-content {
        flex-direction: column;
        align-items: center;
      }

      .visi-box, .misi-box {
        width: 100%;
      }
      .misi-box li {
        font-size: 14px;
        text-align: start;
      }

      .direksi-content {
          flex-direction: column;
          align-items: center;
      }

      .direksi-item {
          width: 90%;
      }
    }

  </style>
@endsection
@section("content_home")
     <!-- Start Section Banner Area -->
        <!-- <div class="section-banner bg-1">
            <div class="container">
                <div class="banner-spacing">
                    <div class="section-info">
                        <h2 data-aos="fade-up" data-aos-delay="100">{{ __('tentangkami.banner_title') }}</h2>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- End Section Banner Area -->

        <section id="tentang-kami" class="about-section">
            <div class="container">
                <div class="about-content">
                    <div class="about-text">
                        <h2 class="section-title">{{__('tentangkami.title')}}</h2>
                        <p>{!!__('tentangkami.paragraph1')!!}

                        </p>
                        <p>
                           {{__('tentangkami.paragraph2')}}
                        </p>
                        <p>
                          {{__('tentangkami.paragraph3')}}
                        </p>
                        <ul class="about-list">
                        @foreach(__('tentangkami.tentang_list') as $tentang)
                            <li>{{$tentang}}</li>
                         @endforeach
                        </ul>
                    </div>
                    <div class="about-image">
                        <img src="{{ asset('assets_home/img/banner/hotel-santika.jpeg') }}" alt="Image Tentang Splatinum Indonesia Upvc">
                    </div>
                </div>
            </div>
        </section>

        <section id="visi-misi" class="visi-misi-section">
          <div class="container">
              <h2 class="section-title text-white">{{__('tentangkami.visimisi')}}</h2>
              <div class="visi-misi-content">
                  <div class="visi-box">
                      <h3>{{__('tentangkami.visi')}}</h3>
                      <p>
                        {!!__('tentangkami.visi_desc')!!}
                      </p>
                  </div>
                  <div class="misi-box">
                      <h3>{{__('tentangkami.misi')}}</h3>
                      <ul>
                          @foreach(__('tentangkami.misi_desc') as $misi)
                            <li>{!!$misi!!}</li>
                         @endforeach
                      </ul>
                  </div>
              </div>
          </div>
      </section>

      <!-- <section id="jajaran-direksi" class="direksi-section">
          <div class="container">
              <h2 class="section-title">{{__('tentangkami.struktur')}}</h2>
              <div class="direksi-content">
                  <div class="direksi-item">
                      <img src="{{ asset('assets_home/img/foto-testi/adam.jpg') }}" alt="Direktur Splatinum upvc">
                      <h3>Admin</h3>
                      <p>{{__('tentangkami.struktur1')}}</p>
                  </div>
                  <div class="direksi-item">
                      <img src="{{ asset('assets_home/img/foto-testi/desi.png') }}" alt="Manager Splatinum upvc">
                      <h3>Desi</h3>
                      <p>{{__('tentangkami.struktur2')}}</p>

                  </div>
                  <div class="direksi-item">
                      <img src="{{ asset('assets_home/img/foto-testi/ofi.png') }}" alt="Keuangan Splatinum upvc">
                      <h3>Ofi</h3>
                      <p>{{__('tentangkami.struktur3')}}</p>
                  </div>
                  <div class="direksi-item">
                      <img src="{{ asset('assets_home/img/foto-testi/syifa.png') }}" alt="Marketing Splatinum upvc">
                      <h3>Syifa</h3>
                      <p>{{__('tentangkami.struktur4')}}</p>
                  </div>
              </div>
          </div>
      </section> -->


<hr>

@endsection
