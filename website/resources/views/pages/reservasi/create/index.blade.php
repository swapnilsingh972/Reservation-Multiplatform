@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Reservasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Reservasi</li>
                <li class="breadcrumb-item active">Jenis Layanan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row align-items-top">
            @foreach ($layanans as $layanan)
                <div class="col-12">
                    {{-- Card --}}
                    <div class="card">
                        <div class="card-body row position-relative">
                            <h5 class="card-title mx-3">
                                {{ $layanan->nama }}
                            </h5>
                            <div class="col-3 justify-content-center">
                                <img src="img/DataLayanan/{{ $layanan->foto }}" alt="" class="card-img mb-3"
                                    style="max-width: 170px">
                            </div>
                            <div class="col-9">
                                <p class="small mb-1">
                                    {{ $layanan->deskripsi }}
                                </p>
                                <div class="botton-card">
                                    <div class="d-flex justify-content-end">
                                        <p class="my-0 small text-secondary">
                                            {{ $layanan->formatted_waktu }}
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <p class="my-0">
                                            Rp{{ number_format($layanan->harga, 0, ',', '.') }} 
                                        </p>
                                    </div>
                                    <div class="card-text d-flex justify-content-end">
                                        <a href="{{ route('create1Reservasi', $layanan->id) }}"
                                            class="btn btn-primary m-1"><span>Pilih</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
            @endforeach
        </div>
    </section>
@endsection
