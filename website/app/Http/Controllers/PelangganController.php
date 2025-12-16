<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::with('users')->get();
        return view('pages.manajemen.pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pages.manajemen.pelanggan.create');
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
            'roles' => "pelanggan",
        ]);

        $pelanggan = [
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'id_user' => $user->id,
        ];

        if ($request->hasFile('imagePelanggan')) {
            $tujuan_upload = 'img/DataPelanggan';
            $file = $request->file('imagePelanggan');
            $filegambar = time() . "_" . $file->getClientOriginalName();
            $file->move($tujuan_upload, $filegambar);
            $pelanggan['foto'] = $filegambar;
        }

        Pelanggan::create($pelanggan);
        Alert::success('Success', 'Berhasil Menambahkan Data');
        return redirect()->route('indexPelanggan')->with($validated);
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::where('id', $id)->first();
        return view('pages.manajemen.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        if ($request->hasFile('imagePelanggan')) {
            $tujuan_upload = 'img/DataPelanggan';
            $file = $request->file('imagePelanggan');
            $filegambar = time() . "_" . $file->getClientOriginalName();

            if ($pelanggan->foto !== "default_foto.jpg") {
                $last_upload = 'img/DataPelanggan/' . $pelanggan->foto;
                if (File::exists($last_upload)) {
                    File::delete($last_upload);
                }
            }

            $pelanggan->foto = $filegambar;
            $file->move($tujuan_upload, $filegambar);
        }

        if ($pelanggan->users->email !== $request->email) {
            $validated = $request->validate([
                'email' => 'required|unique:users,email,' . $pelanggan->users->id . ',id,deleted_at,NULL',
            ], [
                'email.unique' => 'Email sudah digunakan.',
            ]);

            $pelanggan->users->email = $request->email;
            $pelanggan->users->save();

        }

        $pelanggan->nama = $request->nama;
        $pelanggan->no_telp = $request->no_telp;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->save();
        
        if ($pelanggan->users->email !== $request->email) {
            return redirect()->route('indexPelanggan')->with($validated);
        } else {
            Alert::success('Success', 'Berhasil Memperbarui Data');
            return redirect()->route('indexPelanggan');
        }
    }

    public function delete(User $id)
    {
        if ($id->pelanggans->foto !== "default_foto.jpg") {
            $last_upload = 'img/DataPelanggan/' . $id->pelanggans->foto;
            if (File::exists($last_upload)) {
                File::delete($last_upload);
            }
        }
        $id->delete();
        Alert::success('Success', 'Data pelanggan berhasil dihapus.');
        return redirect()->back();
    }
}
