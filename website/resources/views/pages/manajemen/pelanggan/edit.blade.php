@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Perbarui Pelanggan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Manajemen</li>
                <li class="breadcrumb-item"><a href="{{ route('indexPelanggan') }}">Data Pelanggan</a></li>
                <li class="breadcrumb-item active">Perbarui Pelanggan</li>
            </ol>
        </nav>
    </div>
    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-start align-items-center">
                <h5 class="card-title">
                    Pelanggan
                </h5>
            </div>
            <form action="{{ route('updatePelanggan', $pelanggan->id) }}" method="POST" enctype="multipart/form-data"
                class="row g-3">
                @csrf
                <div class="col-12">
                    <label for="foto" class="form-label">Foto Karyawan</label><br>
                    <input class="m-3" type="file" name="imagePelanggan" id="imagePelanggan" accept="image/*">
                    <div id="imagePreview">
                        @if ($pelanggan && $pelanggan->foto)
                            <img src="{{ asset('img/DataPelanggan/' . $pelanggan->foto) }}" alt="Foto Pelanggan"
                                style="max-width: 200px;">
                        @endif
                    </div>
                </div>
                <div class="col-6">
                    <label for="nama" class="form-label">Nama Karyawan</label>
                    <input type="text" class="form-control" id="nama" name="nama"
                        value="{{ old('nama', $pelanggan->nama) }}" maxlength="20" required>
                </div>
                <div class="col-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ old('email', $pelanggan->users->email) }}" required>
                    @if ($errors->has('email'))
                        <div class="text-danger">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>
                <div class="col-6">
                    <label for="no_telp" class="form-label">No Telp</label>
                    <input type="text" class="form-control" id="no_telp" name="no_telp" value="{{ old('no_telp', $pelanggan->no_telp) }}" oninput="limitNumberLength(this, 14)" required>
                </div>
                <div class="col-6">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat"
                        value="{{ old('alamat', $pelanggan->alamat) }}" required>
                </div>
                <p class="card-text d-flex justify-content-end">
                    <a href="{{ route('indexPelanggan') }}" class="btn btn-danger m-1">Batal</a>
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
            $("#imagePelanggan").change(function() {
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

        document.getElementById('nama').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^A-Za-z\s]/g, ''); // Hanya huruf dan spasi
        });

        document.getElementById('no_telp').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9\s]/g, ''); // Hanya angka dan spasi
        });
    </script>
@endsection
