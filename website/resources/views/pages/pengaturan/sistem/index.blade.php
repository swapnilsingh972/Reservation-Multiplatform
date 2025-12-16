@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Pengaturan Sistem</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Pengaturan</li>
                <li class="breadcrumb-item active">Sistem</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-12">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#sistem">Sistem</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sistem-edit">Edit
                                    Sistem</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">
                            {{-- Overview --}}
                            <div class="tab-pane fade show active sistem" id="sistem">
                                <h5 class="card-title">Logo</h5>
                                <img src="{{ asset('img/DataLogo/' . $sistems->logo) }}" alt="Logo"
                                    style="max-width: 300px;">

                                <h5 class="card-title">Detail Profil</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Nama Perusahaan</div>
                                    <div class="col-lg-9 col-md-8">{{ $sistems->nama }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">No Telephone</div>
                                    <div class="col-lg-9 col-md-8">{{ $sistems->no_telp }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Alamat</div>
                                    <div class="col-lg-9 col-md-8">{{ $sistems->alamat }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Jam Operational</div>
                                    <div class="col-lg-9 col-md-8">{{ $sistems->jam_operasional_buka }} -
                                        {{ $sistems->jam_operasional_tutup }}</div>
                                </div>

                                {{-- <h5 class="card-title">Detail Sistem</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Jumlah Pembatalan Pelanggan (sehari)</div>
                                    <div class="col-lg-9 col-md-8">{{ $sistems->jumlah_pembatalan }} kali</div>
                                </div> --}}
                            </div>

                        </div>

                        {{-- Edit --}}
                        <div class="tab-pane fade profile-edit pt-3" id="sistem-edit">

                            <!-- Sistem Edit Form -->
                            <form action="{{ route('updateSistem') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Logo</label>
                                    <div class="col-md-8 col-lg-9">
                                        <div id="imagePreview">
                                            @if ($sistems && $sistems->logo)
                                                <img src="{{ asset('img/DataLogo/' . $sistems->logo) }}" alt="Logo"
                                                    style="max-width: 300px; max-height: 300px;">
                                            @endif
                                        </div>
                                        <div class="pt-2">
                                            <input class="m-3" type="file" name="imageLogo" id="imageLogo"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="nama" class="col-md-4 col-lg-3 col-form-label">Nama
                                        Perusahaan</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="nama" type="text" class="form-control" id="nama"
                                            value="{{ old('nama', $sistems->nama) }}" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="no_telp" class="col-md-4 col-lg-3 col-form-label">No Telephone</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="no_telp" type="text" class="form-control" id="no_telp"
                                            value="{{ old('no_telp', $sistems->no_telp) }}" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="alamat" class="col-md-4 col-lg-3 col-form-label">Alamat</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="alamat" type="text" class="form-control" id="alamat"
                                            value="{{ old('alamat', $sistems->alamat) }}" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="jam_operasional_buka" class="col-md-4 col-lg-3 col-form-label">Jam
                                        Operasional</label>
                                    <div class="col-md-4 col-lg-4">
                                        <input name="jam_operasional_buka" type="time" class="form-control"
                                            id="jam_operasional_buka"
                                            value="{{ old('jam_operasional_buka', $sistems->jam_operasional_buka) }}"
                                            required>
                                    </div>
                                    <label for="jam_operasional_tutup"
                                        class="col-md-2 col-lg-2 col-form-label">hingga</label>
                                    <div class="col-md-4 col-lg-3">
                                        <input name="jam_operasional_tutup" type="time" class="form-control"
                                            id="jam_operasional_tutup"
                                            value="{{ old('jam_operasional_tutup', $sistems->jam_operasional_tutup) }}"
                                            required>
                                    </div>
                                </div>

                                {{-- <div class="row mb-3">
                                    <label for="jumlah_pembatalan" class="col-md-4 col-lg-3 col-form-label">Jumlah
                                        Pembatalan Pelanggan (sehari)</label>
                                    <div class="col-md-4 col-lg-4">
                                        <input name="jumlah_pembatalan" type="number" class="form-control"
                                            id="jumlah_pembatalan"
                                            value="{{ old('jumlah_pembatalan', $sistems->jumlah_pembatalan) }}" required>
                                    </div>
                                </div> --}}


                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form><!-- End Profile Edit Form -->

                        </div>
                    </div>
                </div>
            </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        //foto
        $(document).ready(function() {
            // Image preview for question
            $("#imageLogo").change(function() {
                readURL(this, "#imagePreview");
            });

            // Function to read and display image preview
            function readURL(input, previewId) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = new Image();
                        img.src = e.target.result;
                        img.onload = function() {
                            var maxWidth = 100; // Set your maximum width
                            var maxHeight = 100; // Set your maximum height
                            var width = img.width;
                            var height = img.height;

                            // Calculate new width and height while maintaining aspect ratio
                            if (width > maxWidth || height > maxHeight) {
                                var ratio = Math.min(maxWidth / width, maxHeight / height);
                                width = width * ratio;
                                height = height * ratio;
                            }

                            $(previewId).html('<img src="' + e.target.result +
                                '" width="' + width + '" height="' + height + '" />');
                        };
                    };
                    reader.readAsDataURL(input.files[0]);
                }

            }
        });
    </script>
@endsection
