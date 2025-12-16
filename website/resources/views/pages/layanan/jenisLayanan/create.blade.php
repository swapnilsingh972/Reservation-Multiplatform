@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Layanan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Layanan</li>
                <li class="breadcrumb-item"><a href="{{ route('indexLayanan') }}">Data Jenis Layanan</a></li>
                <li class="breadcrumb-item active">Tambah Layanan</li>
            </ol>
        </nav>
    </div>
    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-start align-items-center">
                <h5 class="card-title">
                    Layanan
                </h5>
            </div>
            <form action="{{ route('storeLayanan') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-12">
                    <label for="foto" class="form-label">Gambar Layanan</label><br>
                    <input class="m-3" type="file" name="imageLayanan" id="imageLayanan" accept="image/*">
                    <div id="imagePreview">
                    </div>
                </div>
                <div class="col-12">
                    <label for="nama" class="form-label">Nama Layanan</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}"
                        maxlength="20" required>
                </div>
                <div class="col-12">
                    <label for="deskripsi" class="form-label">Deskripsi Layanan</label>
                    <textarea type="text" class="form-control" id="deskripsi" name="deskripsi" required>{{ old('deskripsi') }}</textarea>
                </div>
                <div class="col-6">
                    <label for="durasi" class="form-label">Durasi</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="durasi" name="durasi" value="{{ old('durasi') }}"
                            required>
                        <button type="button" class="input-group-text">
                            <span>jam</span>
                        </button>
                    </div>
                    @error('durasi')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="harga" class="form-label">Harga</label>
                    <div class="input-group">
                        <button type="button" class="input-group-text">
                            <span>Rp</span>
                        </button>
                        <input type="text" class="form-control" id="harga" name="harga" value="{{ old('harga') }}"
                            required>
                    </div>
                </div>
                <div class="col-6">
                    <label for="poin" class="form-label">Poin</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="poin" name="poin" checked>
                        <label class="form-check-label" for="poin">Aktif</label>
                    </div>
                </div>
                <p class="card-text d-flex justify-content-end">
                    <a href="{{ route('indexLayanan') }}" class="btn btn-danger m-1">Batal</a>
                    <button type="submit" class="btn btn-primary m-1">Simpan</button>
                </p>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        //foto
        $(document).ready(function() {
            // Image preview for question
            $("#imageLayanan").change(function() {
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
                            var maxWidth = 200; // Set your maximum width
                            var maxHeight = 200; // Set your maximum height
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

        //password
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var passwordButton = document.querySelector(".btn-show-password");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordButton.innerHTML = '<i class="bi bi-eye"></i>';
            } else {
                passwordInput.type = "password";
                passwordButton.innerHTML = '<i class="bi bi-eye-slash"></i>';
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#durasi", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i", // Format 24 jam
                time_24hr: true, // Pastikan menggunakan format 24 jam
                minuteIncrement: 60, // Hanya jam penuh
            });
        });

        document.getElementById('harga').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9\s]/g, ''); // Hanya angka dan spasi
        });
    </script>
@endsection
