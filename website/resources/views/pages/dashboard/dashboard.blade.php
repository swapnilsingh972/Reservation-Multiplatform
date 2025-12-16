@extends('component.main')

@section('content')
    @if (auth()->user()->roles === 'admin')
        <div class="pagetitle">
            <h1>Dashboard</h1>
            <p class="text-secondary">Selamat datang <b>{{ $myData->nama }}</b></p>
        </div>
        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row">

                        <!-- Sales Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card sales-card">

                                <div class="card-body">
                                    <h5 class="card-title">Reservasi <span>| Hari ini</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cart"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $countReservasi }}</h6> <span
                                                class="text-muted small pt-2 ps-1">reservasi</span>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Sales Card -->

                        <!-- Revenue Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card revenue-card">

                                <div class="card-body">
                                    <h5 class="card-title">Pendapatan <span>| Bulan ini</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-coin"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>Rp{{ number_format($pendapatan, 0, ',', '.') }}</h6><span
                                                class="text-muted small pt-2 ps-1">rupiah</span>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Revenue Card -->

                        <!-- Customers Card -->
                        <div class="col-xxl-4 col-xl-12">

                            <div class="card info-card customers-card">

                                <div class="card-body">
                                    <h5 class="card-title">Pelanggan Terdaftar<span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $countPelanggan }}</h6><span
                                                class="text-muted small pt-2 ps-1">pelanggan</span>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div><!-- End Customers Card -->

                    </div>
                </div><!-- End Left side columns -->

            </div>
        </section>
    @elseif (auth()->user()->roles === 'karyawan')
        <div class="pagetitle">
            <h1>Dashboard</h1>
            <p class="text-secondary">Selamat datang <b>{{ $myData->nama }}</b></p>
        </div>
        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row">

                        <!-- Sales Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card sales-card">

                                <div class="card-body">
                                    <h5 class="card-title">Reservasi <span>| Hari ini</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cart"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $countReservasi }}</h6> <span
                                                class="text-muted small pt-2 ps-1">reservasi</span>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Sales Card -->

                    </div>
                </div><!-- End Left side columns -->

            </div>
        </section>
    @elseif (auth()->user()->roles === 'pelanggan')
        <div class="pagetitle">
            <h1>Dashboard</h1>
            <p class="text-secondary">Selamat datang <b>{{ $myData->nama }}</b></p>
        </div>
        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-12">
                    <div class="row">

                        <!-- Revenue Card -->
                        <div class="col-4 col-6">
                            <div class="card info-card revenue-card">

                                <div class="card-body">
                                    <h5 class="card-title">Poin</h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-coin"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $myData->poin }}</h6><span class="text-muted small pt-2 ps-1">poin</span>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Revenue Card -->

                        <!-- Sales Card -->
                        <div class="col-4 col-6">
                            <div class="card info-card sales-card">

                                <div class="card-body">
                                    <h5 class="card-title">Reservasi <span>| Mendatang</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cart"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $countReservasi }}</h6> <span class="text-muted small pt-2 ps-1">reservasi</span>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Sales Card -->

                    </div>
                </div><!-- End Left side columns -->

            </div>
        </section>
    @endif
@endsection
