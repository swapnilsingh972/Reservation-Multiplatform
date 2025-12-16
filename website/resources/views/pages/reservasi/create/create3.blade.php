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
                                <div class="d-flex align-items-center mb-3">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('img/DataLayanan/' . $layanan->foto) }}" alt="Profile"
                                            class="rounded-circle" style="max-width: 70px;">
                                    </div>
                                    <div class="ps-3">
                                        <h6 class="small">{{ $layanan->nama }}</h6>
                                        <span class="text-muted small pt-2 ps-1">{{ $layanan->formatted_waktu }}</span>
                                    </div>
                                </div>
                                <h5 class="card-title">Hair Stylis</h5>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('img/DataKaryawan/' . $karyawan->foto) }}" alt="Profile"
                                            class="rounded-circle" style="max-width: 70px;">
                                    </div>
                                    <div class="ps-3">
                                        <h6 class="small">{{ $karyawan->nama }}</h6>
                                    </div>
                                </div>
                                <h5 class="card-title mt-3">Detail</h5>
                                <p>Waktu :</p>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $slot['jam_awal'] }} - {{ $slot['jam_berakhir'] }}</h6>
                                <form action="{{ route('store3Reservasi') }}" method="POST" enctype="multipart/form-data"
                                    class="row g-3">
                                    @csrf
                                    <input type="hidden" name="id_karyawan" value="{{ $karyawan->id }}">
                                    <input type="hidden" name="id_layanan" value="{{ $layanan->id }}">
                                    <input type="hidden" name="jam_awal" value="{{ $slot['jam_awal'] }}">
                                    <div class="col-12">
                                        <label for="nama" class="form-label">Nama Pengguna :</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            value="{{ old('nama') }}" required>
                                    </div>

                                    <h6 class="mt-2 text-end">Rp{{ $layanan->harga }}</h6>

                                    <!-- Tombol Pilih dan Kembali -->
                                    <div class="card-text d-flex justify-content-end">
                                        <a href="{{ route('indexReservasi') }}" class="btn btn-danger m-1">Kembali</a>
                                        <button type="submit" class="btn btn-primary m-1">Submit</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div><!-- End Sales Card -->
                </div>
            </div>

        </div>
    </section>
@endsection
