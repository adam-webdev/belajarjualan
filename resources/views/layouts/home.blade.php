<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Home </title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset('assets/img/favicon.png')}}" rel="icon">
  <link href="{{asset('assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
 <style>
        .video-background {
            justify-content: center;
            position: relative;
            align-items: center;
            height: 100vh;
            /* overflow: hidden; */
        }

        .video-background video {
            filter: brightness(50%);
            /* Mengurangi kecerahan menjadi 50% dari aslinya */
            contrast: 0.7;
            height: 100%;
            /* Mengurangi kontras sebesar 30% dari aslinya */
            /* Video akan memiliki tinggi 100% dari tinggi layar */
            /* Minimum tinggi video akan sama dengan tinggi layar */
            width: 100%;
            /* Video akan memiliki lebar 100% */
            /* Video akan mengisi area dengan menjaga aspek rasio */
        }

        .screen-layout {
            display: block;
            position: relative;
        }

        .navbar {
            top: 0;
            width: 100%;
            position: fixed;
            padding: 0 100px;
            transition: top 0.3s;
            z-index: 99999;
        }

        .content {
            display: flex;
            width: 52%;
            justify-content: center;
            align-items: center;
            margin-top: 300px;
            position: absolute;
            font-family: 'Montserrat';

        }

        .content p {
            font-size: 26px;
            color: white;
        }

        .section1 {
            display: flex;
            align-items: center;
            height: 400px;
        }

        @media screen and (max-width:767px) {
            .content p {
                font-size: 24px;
            }
        }

        @media screen and (max-width:450px) {
            .navbar {
                padding: 0 30px;
            }

            .content p {
                font-size: 18px;
            }

            .logo {
                font-size: 18px;

            }
        }

        #buttons {
            position: absolute;
            bottom: 0;
            margin-bottom: 10px;
            left: 100px;
            z-index: 1;
        }

        .marker-custom {
            border-radius: 50%;
            cursor: pointer;
            background-size: cover;
            border: 1px solid rgb(239, 98, 16);
        }

        .popup-btn:focus {
            border: none;
        }

        .popup-btn:hover {
            transition: .5s;
            background: rgba(1, 1, 1, 0.637) !important
        }

        .icon i {
            font-size: 34px;
        }

        .div {
            display: flex;
            flex-direction: column;
            row-gap: 8px;
            align-items: center;
            justify-content: center;
        }
        /* Wrapper untuk teks */
    .animated-tagline {
      padding-top: 350px;
      padding-right: 80px;
      padding-left: 80px;
      display: flex;
      position: relative;
      align-items: center;
      text-align: center;
      border-radius: 10px;
    }

    /* Teks */
    .animated-tagline h1 {
      font-size: 1.5rem;
      font-weight: bold;
      line-height: 1.5;
      color:#fff;
      z-index: 2;
    }

    /* Elemen bergerak */
    .floating-shapes {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
    }

    .shape {
      position: absolute;
      width: 20px;
      height: 20px;
      background: #ffffff;
      border-radius: 50%;
      animation: float 3s infinite ease-in-out;
    }

    /* Posisi acak */
    .shape:nth-child(1) {
      top: 20%;
      left: 20%;
      animation-delay: 0s;
    }

    .shape:nth-child(2) {
      top: 50%;
      left: 70%;
      animation-delay: 1s;
    }

    .shape:nth-child(3) {
      top: 80%;
      left: 30%;
      animation-delay: 2s;
    }

    /* Animasi melayang */
    @keyframes float {
      0% {
        transform: translateY(0) scale(1);
      }
      50% {
        transform: translateY(-20px) scale(1.2);
      }
      100% {
        transform: translateY(0) scale(1);
      }
    }
     /* Media queries untuk responsivitas */
      @media (max-width: 768px) {
        .animated-tagline {
          padding-top: 300px;
          padding-right: 30px;
          padding-left: 30px;
        }

        /* Teks */
        .animated-tagline h1 {
          font-size: 1rem;
          line-height: 1.2;
        }

      }
      @media (max-width: 480px) {
        .animated-tagline {
          padding-top: 300px;
        }

        /* Teks */
        .animated-tagline h1 {
          font-size: 1rem;
          line-height: 1.3;
        }

      }
    </style>
</head>

<body>
 <div class="video-background">
        <video autoplay muted loop poster="{{ asset('assets/home/sapi.mp4') }}" width="100%"
            style="object-fit: cover; position: absolute; z-index: -1;">
            <source src="{{ asset('assets/home/sapi.mp4') }}" type="video/mp4">
            <!-- Tambahan sumber video jika diperlukan -->
        </video>

        <nav class="navbar navbar-expand topbar justify-content-between" style="background-color: transparent!important; ">

            <div class="input-group-append mt-4">
                <a href="/" style="text-decoration: none" class="d-flex align-items-center">
                  <div class="bg-white rounded circle mr-3" width="40">
                   <img width="40" class="p-2" src="{{asset('assets/img/sapi.png')}}" alt="">
                   </div>
                    <h4 class=" logo text-white font-weight-bold">Peternakan Sapi Bihreun

                    </h4>
                </a>
            </div>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a href="{{ route('login') }}" class="nav-link text-white">Masuk</a>
                    </li>
                    <!-- <li class="nav-item dropdown no-arrow">
                        <a href="{{ route('register') }}" class="nav-link text-white">Daftar</a>
                    </li> -->

            </ul>
        </nav>

        <div class="animated-tagline">
          <h1>Temukan keunggulan sapi berkualitas tinggi dengan perawatan terbaik, solusi sempurna untuk meningkatkan produktivitas peternakan Anda dan memenuhi kebutuhan pasar yang terus berkembang!</h1>
          <div class="floating-shapes">
            <span class="shape"></span>
            <span class="shape"></span>
            <span class="shape"></span>
          </div>
        </div>


        <!-- <div class="container">

            <div class="content ">
                <p class="gradient-text">Temukan keunggulan sapi berkualitas tinggi dengan perawatan terbaik, solusi sempurna untuk meningkatkan produktivitas peternakan Anda dan memenuhi kebutuhan pasar yang terus berkembang!<p>
            </div>
        </div> -->

    </div>
  <!-- ======= Header ======= -->

  <!-- Vendor JS Files -->
  <script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/chart.js/chart.umd.js')}}"></script>
  <script src="{{asset('assets/vendor/echarts/echarts.min.js')}}"></script>
  <script src="{{asset('assets/vendor/quill/quill.js')}}"></script>
  <script src="{{asset('assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
  <script src="{{asset('assets/vendor/tinymce/tinymce.min.js')}}"></script>
  <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>

  <!-- Template Main JS File -->

  <script src="{{asset('assets/js/main.js')}}"></script>

  @yield('scripts')

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        var toastElList = [].slice.call(document.querySelectorAll('.toast'));
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl);
        });

        toastList.forEach(toast => toast.show());
    });
</script>


</body>

</html>