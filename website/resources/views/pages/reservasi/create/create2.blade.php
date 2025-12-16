@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Daftar Reservasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Reservasi</li>
                <li class="breadcrumb-item"><a href="{{ route('indexReservasi') }}">Jenis Layanan</a></li>
                <li class="breadcrumb-item active">Daftar Reservasi</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">
                    <!-- Sales Card -->
                    <div class="col-xxl-12 col-md-12 position-relative">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Layanan</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('img/DataLayanan/' . $layanans->foto) }}" alt="Profile"
                                            class="rounded-circle" style="max-width: 70px;">
                                    </div>
                                    <div class="ps-3">
                                        <h6 class="small">{{ $layanans->nama }}</h6> <span
                                            class="text-muted small pt-2 ps-1">{{ $layanans->formatted_waktu }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Sales Card -->
                    <div class="col-xxl-12 col-md-12 position-relative">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Karyawan</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('img/DataKaryawan/' . $karyawan->foto) }}" alt="Profile"
                                            class="rounded-circle" style="max-width: 70px;">
                                    </div>
                                    <div class="ps-3">
                                        <h6 class="small">{{ $karyawan->nama }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Sales Card -->

                    <!-- Sales Card -->
                    <div class="col-12 position-relative">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Reservasi tgl {{ $tanggal }} </h5>

                                <div class="row align-items-top">
                                    @foreach ($availableSlots as $slot)
                                        <div class="col-6">
                                            {{-- Card --}}
                                            <div class="alert border-secondary alert-dismissible fade show d-flex align-items-center justify-content-between" role="alert">
                                                <div>
                                                    {{ $slot['jam_awal'] }} - {{ $slot['jam_berakhir'] }}
                                                </div>
                                                <div>
                                                    <form action="{{ route('store2Reservasi') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="karyawan_id" value="{{ $karyawan->id }}">
                                                        <input type="hidden" name="layanan_id" value="{{ $layanans->id }}">
                                                        <input type="hidden" name="availableSlots" value="{{ json_encode($slot) }}">
                                                        <button type="submit" class="btn btn-primary">Pilih</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
