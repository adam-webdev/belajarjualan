
 @extends('layouts.layoutmaster')

@section('content')
@include('sweetalert::alert')

    <section class="section dashboard">
      <div class="d-flex gap-4">

        <!-- Left side columns -->
       <!-- Sales Card -->
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Data Produk <span>| Hari ini</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-box"></i>

                    </div>
                    <div class="ps-3">
                      <h6>{{\App\Models\Produk::count()}}</h6>
                      <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>
              </div>
              <div class="card info-card sales-card">

                <div class="card-body">
                  <h5 class="card-title">Data Artikel <span>| Hari ini</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-newspaper"></i>

                    </div>
                    <div class="ps-3">
                      <h6>{{\App\Models\Artikel::count()}}</h6>
                      <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>

              </div>

      </div>
    </section>

@endsection
