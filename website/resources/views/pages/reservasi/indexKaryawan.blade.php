@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Data Reservasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Reservasi</li>
                <li class="breadcrumb-item active">Data Reservasi</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <!-- Card Pelanggan Saat Ini -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Pelanggan yang Sedang Dikerjakan</h5>
                        @if ($currentReservation)
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Urutan:</strong> {{ $currentReservation->urutan }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Nama:</strong> {{ $currentReservation->nama }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Layanan:</strong> {{ $currentReservation->layanans->nama }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Jam Mulai:</strong> {{ $currentReservation->jam_awal }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Jam Berakhir:</strong> {{ $currentReservation->jam_berakhir }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>No Telp:</strong>
                                        {{ $currentReservation->users->pelanggans->no_telp ?? '-' }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada pelanggan yang sedang dikerjakan saat ini.</p>
                        @endif
                    </div>
                </div>
                <!-- End Card Pelanggan Saat Ini -->

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Reservasi</h5>
                        <form action="{{ route('indexReservasiKaryawan') }}" method="GET" enctype="multipart/form-data"
                            class="row g-3">
                            @csrf
                            <div class="col-4">
                                <label for="tanggal" class="form-label">Pilih Tanggal</label>
                                <div class="d-flex">
                                    <!-- Input Tanggal -->
                                    <input type="date" class="form-control col-8" id="tanggal" name="tanggal"
                                        value="{{ request('tanggal', now()->toDateString()) }}">

                                    <!-- Tombol Submit -->
                                    <button type="submit" class="btn btn-primary col-4 ms-2">Tampilkan</button>
                                </div>
                            </div>
                        </form>

                        <table class="table datatable" id="myTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Layanan</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">No Telp</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Jam Mulai</th>
                                    <th class="text-center">Jam Berakhir</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservasis as $reservasi)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="text-center">
                                            {{ $reservasi->layanans->nama }}
                                        </td>
                                        <td class="text-center">
                                            {{ $reservasi->nama }}
                                        </td>
                                        <td class="text-center">
                                            {{ $reservasi->users->email }}
                                        </td>
                                        <td class="text-center">
                                            {{ $reservasi->users->pelanggans->no_telp ?? '-' }}
                                        </td>

                                        <td class="text-center">
                                            {{ $reservasi->tanggal_pemesanan }}
                                        </td>
                                        <td class="text-center">
                                            {{ $reservasi->jam_awal }}
                                        </td>
                                        <td class="text-center">
                                            {{ $reservasi->jam_berakhir }}
                                        </td>
                                        <td class="text-center">
                                            @if ($reservasi->status === 'finished' || $reservasi->status === 'canceled')
                                                <button class="btn btn-danger m-1" disabled>
                                                    {{ $reservasi->status }}
                                                </button>
                                            @else
                                                <button id="btn-status-{{ $reservasi->id }}"
                                                    class="btn {{ $reservasi->status === 'upcoming' ? 'btn-warning' : 'btn-primary' }} m-1 update-status btn-status"
                                                    data-id="{{ $reservasi->id }}"
                                                    data-status="{{ $reservasi->status === 'verification' ? 'verification' : ($reservasi->status === 'upcoming' ? 'processing' : 'upcoming') }}">
                                                    {{ $reservasi->status }} </button>
                                                <button id="btn-verification-{{ $reservasi->id }}"
                                                    class="btn {{ $reservasi->status === 'verification' ? 'btn-secondary' : 'btn-primary' }} m-1 btn-verification"
                                                    data-id="{{ $reservasi->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#verificationModal-{{ $reservasi->id }}"
                                                    data-status="{{ $reservasi->status }}">
                                                    {{ $reservasi->status }} </button>
                                                <button id="btn-pembayaran-{{ $reservasi->id }}"
                                                    class="btn btn-success m-1"
                                                    style="display: {{ $reservasi->status === 'processing' ? 'show' : 'none' }};"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#pembayaranModal-{{ $reservasi->id }}">
                                                    Pembayaran
                                                </button>
                                                <button id="btn-batal-{{ $reservasi->id }}"
                                                    class="btn btn-danger btn-batal m-1" data-id="{{ $reservasi->id }}"
                                                    data-status="{{ $reservasi->status }}" data-bs-toggle="modal"
                                                    data-bs-target="#batalModal-{{ $reservasi->id }}"
                                                    style="{{ $reservasi->status === 'finished' || $reservasi->status === 'canceled' ? 'display: none;' : '' }}">
                                                    Batalkan
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                     <!-- Modal Konfirmasi Verification -->
                                     <div class="modal fade" id="verificationModal-{{ $reservasi->id }}" tabindex="-1"
                                        aria-labelledby="verificationModalLabel-{{ $reservasi->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg"> <!-- modal-lg to make modal larger -->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="verificationModalLabel-{{ $reservasi->id }}">
                                                        Konfirmasi Verification</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            {!! $reservasi->foto_payment
                                                                ? '<img src="' .
                                                                    asset('img/DataPayment/' . $reservasi->foto_payment) .
                                                                    '" alt="Gambar Payment" style="width: 100%; max-height: 500px; object-fit: contain;">'
                                                                : 'tidak ada Foto' !!}
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-12">
                                                            <p>Apakah Anda yakin ingin menyetujui verifikasi pembayaran ini?
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button class="btn btn-success m-1 update-status"
                                                        data-id="{{ $reservasi->id }}" data-status="upcoming">
                                                        Ya, Lanjutkan Verifikasi
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Konfirmasi Pembayaran -->
                                    <div class="modal fade" id="pembayaranModal-{{ $reservasi->id }}" tabindex="-1"
                                        aria-labelledby="pembayaranModalLabel-{{ $reservasi->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="pembayaranModalLabel-{{ $reservasi->id }}">
                                                        Konfirmasi Pembayaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><strong>Nama:</strong></p>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p>{{ $reservasi->nama }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><strong>Layanan:</strong></p>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p>{{ $reservasi->layanans->nama }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><strong>Tanggal:</strong></p>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p>{{ $reservasi->tanggal_pemesanan }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><strong>Total Harga:</strong></p>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p>Rp{{ number_format($reservasi->layanans->harga, 0, ',', '.') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <p>Apakah Anda yakin ingin melakukan pembayaran untuk reservasi
                                                                ini?</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button class="btn btn-success m-1 update-status"
                                                        data-id="{{ $reservasi->id }}" data-status="finished">
                                                        Ya, Lanjutkan
                                                        Pembayaran
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Konfirmasi Pembatalan -->
                                    <div class="modal fade" id="batalModal-{{ $reservasi->id }}" tabindex="-1"
                                        aria-labelledby="batalModalLabel-{{ $reservasi->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Konfirmasi Pembatalan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>Nama / No Telp</strong></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p>: {{ $reservasi->nama }} /
                                                                {{ $reservasi->users->pelanggans->no_telp ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>Layanan / Hairstylist</strong></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p>: {{ $reservasi->layanans->nama }} /
                                                                {{ $reservasi->karyawans->nama }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>Tanggal / Jam Mulai</strong></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p>: {{ $reservasi->tanggal_pemesanan }} /
                                                                {{ $reservasi->jam_awal }}</p>
                                                        </div>
                                                    </div>
                                                    <p>Apakah Anda yakin ingin membatalkan reservasi ini?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button class="btn btn-danger update-status"
                                                        data-id="{{ $reservasi->id }}" data-status="canceled">
                                                        Ya, Lanjutkan Pembatalan
                                                    </button>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Cek status awal dan sembunyikan tombol "Batalkan" jika perlu
        $('.btn-status').each(function() {
            const status = $(this).data('status');
            const reservasiId = $(this).data('id');
            if(status === 'verification'){
                $('#btn-status-' + reservasiId).hide();
            }
        });

        $('.btn-verification').each(function() {
            const status = $(this).data('status');
            const reservasiId = $(this).data('id');
            if(status !== 'verification'){
                $('#btn-verification-' + reservasiId).hide();
            }
        });

        $('.btn-batal').each(function() {
            const status = $(this).data('status');
            const reservasiId = $(this).data('id');

            if (status === 'finished' || status === 'canceled') {
                $('#btn-batal-' + reservasiId).hide();
            }
        });
    });

    $(document).on('click', '.update-status', function() {
        const reservasiId = $(this).data('id');
        const newStatus = $(this).data('status');
        const button = $(this);

        $.ajax({
            url: "{{ route('reservasi.updateStatus') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: reservasiId,
                status: newStatus
            },
            success: function(response) {
                if (response.success) {
                    // Ubah teks tombol
                    button.text(newStatus);

                    // Perbarui data-status
                    const nextStatus = newStatus === 'processing' ? 'upcoming' : 'processing';
                    button.data('status', nextStatus);

                    // Perbarui kelas tombol berdasarkan status baru
                    if (newStatus === 'processing') {
                        button.removeClass('btn-warning').addClass('btn-primary');
                        // Tampilkan tombol pembayaran jika status adalah Processing
                        $('#btn-pembayaran-' + reservasiId).show();
                    } else {
                        button.removeClass('btn-primary').addClass('btn-warning');
                        // Sembunyikan tombol pembayaran jika status bukan Processing
                        $('#btn-pembayaran-' + reservasiId).hide();
                    }

                    // Jika status menjadi 'finished', ubah tombol menjadi finished dan sembunyikan tombol pembayaran
                    if (newStatus === 'finished') {
                        $('#btn-status-' + reservasiId).removeClass('btn-primary btn-warning')
                            .addClass('btn-danger');
                        $('#btn-status-' + reservasiId).text('finished');
                        $('#btn-status-' + reservasiId).prop('disabled', true);
                        $('#btn-pembayaran-' + reservasiId).hide();

                        // Menutup modal setelah status diubah
                        $('#pembayaranModal-' + reservasiId).modal('hide');
                        $('#btn-batal-' + reservasiId).hide();
                    }

                    $('#btn-batal-' + reservasiId).attr('data-status', newStatus);

                    if (newStatus === 'canceled') {
                        $('#btn-status-' + reservasiId).removeClass('btn-primary btn-warning')
                            .addClass('btn-danger');
                        $('#btn-status-' + reservasiId).text('canceled');
                        $('#btn-status-' + reservasiId).prop('disabled', true);
                        $('#btn-batal-' + reservasiId).hide();
                        $('#batalModal-' + reservasiId).modal('hide');
                    }

                    if (newStatus === 'upcoming') {
                        $('#btn-status-' + reservasiId).removeClass('btn-primary btn-warning')
                            .addClass('btn-warning');
                        $('#btn-status-' + reservasiId).data('status', 'processing');
                        $('#btn-status-' + reservasiId).text('upcoming');
                        $('#btn-verification-' + reservasiId).hide();
                        $('#btn-status-' + reservasiId).show();
                        $('#verificationModal-' + reservasiId).modal('hide');
                    }
                } else {
                    alert('Gagal memperbarui status.');
                }
            },
            error: function(xhr) {
                alert('Terjadi kesalahan.');
            }
        });
    });
</script>
