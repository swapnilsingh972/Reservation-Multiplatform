@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Data Jenis Layanan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Layanan</li>
                <li class="breadcrumb-item active">Data Jenis Layanan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="d-flex justify-content-start">
            <a href="{{ route('createLayanan') }}" class="btn btn-primary mb-3">Tambah Layanan</a>
        </div>
        <div class="row align-items-top">
            @foreach ($layanans as $layanan)
                <div class="col-12">
                    {{-- Card --}}
                    <div class="card">
                        <div class="card-body row">
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
                                <label class="small my-0">
                                    <i class="bi bi-coin" style="color: orange;"></i> Poin
                                </label>
                                <div class="form-check form-switch small">
                                    <input class="form-check-input" type="checkbox" id="kehadiran" name="kehadiran" disabled
                                        {{ $layanan->poin_aktif === 'aktif' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="kehadiran">Aktif</label>
                                </div>
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

                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $layanan->id }}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                    <a href="{{ route('editLayanan', $layanan->id) }}" class="btn btn-success m-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <div class="modal fade" id="deleteModal{{ $layanan->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Apakah Anda yakin ingin menghapus layanan <strong>{{ $layanan->nama }}</strong>?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <form method="POST" action="{{ route('deleteLayanan', $layanan->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
