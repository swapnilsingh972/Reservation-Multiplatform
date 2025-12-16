@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Data Pengguna</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Manajemen</li>
                <li class="breadcrumb-item active">Data Pengguna</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pengguna</h5>
                        <table class="table datatable" id="myTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Level</th>
                                    <th class="text-center">Reset Password
                                        <button type="button" class="btn" data-bs-toggle="tooltip"
                                            data-bs-placement="right" data-bs-html="true"
                                            title="<b>Passwod Reset</b><br>Karyawan: <b>karyawan123</b><br>Pengguna: <b>pengguna123</b>">
                                            <i class="bi bi-info-circle"></i>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penggunas as $pengguna)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="text-center">
                                            {{ $pengguna->email }}
                                        </td>
                                        <td class="text-center">
                                            {{ $pengguna->roles }}
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger m-1"
                                                {{ $pengguna->roles === 'admin' ? 'hidden' : '' }} data-bs-toggle="modal"
                                                data-bs-target="#updatePengguna{{ $pengguna->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="updatePengguna{{ $pengguna->id }}" tabindex="-1"
                                        aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Reset Password</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin reset password pengguna dengan email
                                                    <strong>{{ $pengguna->email }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <form method="GET"
                                                        action="{{ route('updatePengguna', $pengguna->id) }}">
                                                        <button type="submit" class="btn btn-danger">Reset</button>
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