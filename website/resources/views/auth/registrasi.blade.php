@extends('auth.main')

@section('content')
    <main>
        <div class="container">

            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="index.html" class="logo d-flex align-items-center w-auto">
                                    <img src="{{ asset('img/DataLogo/' . $sistemData->logo) }}" alt="">
                                    <span class="d-none d-lg-block">{{ $sistemData->nama }}</span>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3" style="width: 35rem;">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Registrasi</h5>
                                        <p class="text-center small">Masukkan data Anda untuk membuat akun</p>
                                    </div>

                                    <form action="{{ route('storeAuth') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                        <div class="col-7 mb-3">
                                            <label for="nama" class="form-label">Nama Lengkap</label>
                                            <input type="text" name="nama" class="form-control" id="nama" value="{{ old('nama') }}" required>
                                            <div class="invalid-feedback">Tolong, masukkan nama Lengkap</div>
                                        </div>

                                        <div class="col-5 mb-3">
                                            <label for="email" class="form-label">Email Anda</label>
                                            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
                                            <div class="invalid-feedback">Tolong masukkan nama yang sesuai!</div>
                                            @error('email')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
        
                                        <div class="col-7 mb-3">
                                            <label for="alamat" class="form-label">Alamat</label>
                                            <input type="text" name="alamat" class="form-control" id="alamat" value="{{ old('alamat') }}"
                                                required>
                                            <div class="invalid-feedback">Tolong, masukkan alamat yang sesuai</div>
                                        </div>

                                        <div class="col-5 mb-3">
                                            <label for="no_telp" class="form-label">No Telp</label>
                                            <input type="number" name="no_telp" class="form-control" id="no_telp" value="{{ old('no_telp') }}" required>
                                            <div class="invalid-feedback">Tolong, masukkan no telp</div>
                                        </div>
    
                                        <div class="col-12 mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="password" name="password"
                                                    required>
                                                <button type="button" class="btn-show-password input-group-text"
                                                    onclick="togglePasswordVisibility()">
                                                    <i id="password-toggle-icon" class="bi bi-eye-slash"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback">Tolong masukkan password Anda</div>
                                        </div>
    
                                        <div class="col-12 mb-4">
                                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="password_confirmation"
                                                    name="password_confirmation" required>
                                                <button type="button" class="btn-show-password-konfirmasi input-group-text"
                                                    onclick="toggleKonfirmasiVisibility()">
                                                    <i id="password-toggle-icon" class="bi bi-eye-slash"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
    
                                        <div class="col-12 mb-3">
                                            <button class="btn btn-primary w-100" type="submit">Buat Akun</button>
                                        </div>
    
                                        <div class="col-12">
                                            <p class="small mb-0">Telah memiliki akun? <a href="{{ route('indexAuth') }}">Masuk Akun</a>
                                            </p>
                                        </div>
                                    </form>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>
    <script>
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
        //password konfirmasi
        function toggleKonfirmasiVisibility() {
            var passwordInput = document.getElementById("password_confirmation");
            var passwordButton = document.querySelector(".btn-show-password-konfirmasi");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordButton.innerHTML = '<i class="bi bi-eye"></i>';
            } else {
                passwordInput.type = "password";
                passwordButton.innerHTML = '<i class="bi bi-eye-slash"></i>';
            }
        }
    </script>
@endsection