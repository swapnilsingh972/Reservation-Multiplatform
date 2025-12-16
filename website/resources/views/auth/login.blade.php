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
                                    <span class="d-none d-lg-block" style="color: black">{{ $sistemData->nama }}</span>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4" style="color: black">Login</h5>
                                        <p class="text-center small">masukkan email dan password untuk login</p>
                                    </div>

                                    <form action="{{ route('authenticateAuth') }}" method="POST"
                                        class="row g-3 needs-validation">
                                        @csrf
                                        <div class="col-12">
                                            <label for="email" class="form-label">Email</label>
                                            <div class="input-group has-validation">
                                                <input type="email" class="form-control" id="email" name="email"
                                                    required>
                                                <div class="invalid-feedback">Tolong masukkan email Anda</div>
                                            </div>
                                        </div>

                                        <div class="col-12 mb-2">
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

                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Login</button>
                                        </div>
                                        {{-- <div class="col-12">
                                            <p class="small mb-0">Tidak memiliki Akun? <a href="{{ route('registerAuth') }}">Buat Akun</a>
                                            </p>
                                        </div> --}}
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main>
    @if (session('loginError'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('loginError') }}'
            });
        </script>
    @endif

    <script>
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
    </script>
@endsection
