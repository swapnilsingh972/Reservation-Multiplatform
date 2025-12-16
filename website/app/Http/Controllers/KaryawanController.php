<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::with('users')->get();
        return view('pages.manajemen.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        return view('pages.manajemen.karyawan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|unique:users,email,NULL,id,deleted_at,NULL',
        ], [
            'email.unique' => 'Email sudah digunakan.',
        ]);

        $user = User::create([
            'email'    => $validated['email'],
            'password' => Hash::make($request->password),
            'roles' => "karyawan",
        ]);

        $karyawan = [
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'id_user' => $user->id,
        ];

        if ($request->hasFile('imageKaryawan')) {
            $tujuan_upload = 'img/DataKaryawan';
            $file = $request->file('imageKaryawan');
            $filegambar = time() . "_" . $file->getClientOriginalName();
            $file->move($tujuan_upload, $filegambar);
            $karyawan['foto'] = $filegambar;
        }

        Karyawan::create($karyawan);
        Alert::success('Success', 'Berhasil Menambahkan Data');
        return redirect()->route('indexKaryawan')->with($validated);
    }

    public function edit($id)
    {
        $karyawan = Karyawan::where('id', $id)->first();
        return view('pages.manajemen.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        if ($request->hasFile('imageKaryawan')) {
            $tujuan_upload = 'img/DataKaryawan';
            $file = $request->file('imageKaryawan');
            $filegambar = time() . "_" . $file->getClientOriginalName();

            if ($karyawan->foto !== "default_foto.jpg") {
                $last_upload = 'img/DataKaryawan/' . $karyawan->foto;
                if (File::exists($last_upload)) {
                    File::delete($last_upload);
                }
            }

            $karyawan->foto = $filegambar;
            $file->move($tujuan_upload, $filegambar);
        }

        if ($karyawan->users->email !== $request->email) {
            $validated = $request->validate([
                'email' => 'required|unique:users,email,' . $karyawan->users->id . ',id,deleted_at,NULL',
            ], [
                'email.unique' => 'Email sudah digunakan.',
            ]);

            $karyawan->users->email = $request->email;
            $karyawan->users->save();
        }

        $karyawan->nama = $request->nama;
        $karyawan->no_telp = $request->no_telp;
        $karyawan->save();

        if ($karyawan->users->email !== $request->email) {
            return redirect()->route('indexKaryawan')->with($validated);
        } else {
            Alert::success('Success', 'Berhasil Memperbarui Data');
            return redirect()->route('indexKaryawan');
        }
    }

    public function delete(User $id)
    {
        if ($id->karyawans->foto !== "default_foto.jpg") {
            $last_upload = 'img/DataKaryawan/' . $id->karyawans->foto;
            if (File::exists($last_upload)) {
                File::delete($last_upload);
            }
        }
        $id->delete();
        Alert::success('Success', 'Data karyawan berhasil dihapus.');
        return redirect()->back();
    }
}
