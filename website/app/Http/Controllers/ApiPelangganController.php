<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class ApiPelangganController extends Controller
{
    public function getData($id)
    {
        $pelanggans = Pelanggan::findOrFail($id);

        $currentDate = now()->toDateString();
        $currentTime = now()->toTimeString();

        // Hitung jumlah reservasi yang akan datang dalam satu query langsung
        $countReservasi = Reservasi::where('id_user', $pelanggans->id_user)
            ->where('tanggal_pemesanan', '>=', $currentDate)
            ->whereIn('status', ['upcoming', 'processing'])
            ->count();

        return response()->json([
            'id' => $pelanggans->id,
            'nama' => $pelanggans->nama,
            'email' => $pelanggans->users->email,
            'no_telp' => $pelanggans->no_telp,
            'alamat' => $pelanggans->alamat,
            'poin' => (int) $pelanggans->poin,
            'foto' => $pelanggans->foto,
            'count_reservasi' => (int) $countReservasi,
        ]);
    }
}
