@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Data Karyawan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Manajemen</li>
                <li class="breadcrumb-item active">Data Karyawan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Karyawan</h5>
                        <div class="d-flex justify-content-start">
                            <a href="{{ route('createKaryawan') }}" class="btn btn-primary m-1">Tambah Karyawan</a>
                        </div>
                        <table class="table datatable" id="myTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Foto</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">No Telp</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($karyawans as $karyawan)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="text-center">
                                            @if ($karyawan->foto)
                                            <img src="{{ asset('img/DataKaryawan/' . $karyawan->foto) }}"
                                            alt="Gambar Pertanyaan" style="max-width: 40px;">
                                            @else
                                            tidak ada Foto
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $karyawan->nama }}
                                        </td>
                                        <td class="text-center">
                                            {{ $karyawan->users->email }}
                                        </td>
                                        <td class="text-center">
                                            {{ $karyawan->no_telp }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $karyawan->id_user }}">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                                <a href="{{ route('editKaryawan', $karyawan->id) }}" class="btn btn-warning m-1">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="deleteModal{{ $karyawan->id_user }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus karyawan <strong>{{ $karyawan->nama }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form method="POST" action="{{ route('deleteKaryawan', $karyawan->id_user) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
