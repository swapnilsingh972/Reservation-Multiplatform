<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class PenggunaController extends Controller
{
    public function index()
    {
        $penggunas = User::all();
        return view('pages.manajemen.pengguna.index', compact('penggunas'));
    }

    public function update($id)
    {
        $pengguna = User::findOrFail($id);
        if ($pengguna->roles === "karyawan") {
            $pengguna->password =  Hash::make("karyawan123");
        } else {
            $pengguna->password =  Hash::make("pengguna123");
        }

        $pengguna->save();
        Alert::success('Success', 'Berhasil Memperbarui Data');
        return redirect()->route('indexPengguna');
    }
}
