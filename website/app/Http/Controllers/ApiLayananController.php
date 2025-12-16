<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Layanan;
use Illuminate\Http\Request;

class ApiLayananController extends Controller
{
    public function getData()
    {
        $layanans = Layanan::all();
        $data = []; // Untuk menampung data yang sudah diformat

        foreach ($layanans as $layanan) {
            // Parse string waktu menggunakan Carbon
            $time = Carbon::parse($layanan->durasi);

            // Logika untuk mengubah format waktu
            if ($time->hour > 0) {
                $layanan->formatted_waktu = $time->hour . " jam " . $time->minute . " menit";
            } elseif ($time->minute > 0) {
                $layanan->formatted_waktu = $time->minute . " menit";
            } else {
                $layanan->formatted_waktu = $time->second . " detik";
            }

            // Tambahkan data yang diformat ke array $data
            $data[] = [
                'id' => $layanan->id,
                'nama' => $layanan->nama,
                'deskripsi' => $layanan->deskripsi,
                'durasi' => $layanan->durasi,
                'point_aktif' => $layanan->poin_aktif,
                'harga' => (int) $layanan->harga,
                'foto' => $layanan->foto,
                'formatted_waktu' => $layanan->formatted_waktu,
            ];
        }

        // Kembalikan seluruh data sebagai JSON
        return response()->json($data);
    }

    public function getDataById($id)
    {
        $layanan = Layanan::select('id', 'nama', 'harga', 'poin_aktif')
            ->find($id);

        // Inisialisasi variabel point
        $poin = null;

        // Jika point_aktif bernilai 'aktif', kalkulasi point sebagai 1% dari harga
        if ($layanan && $layanan->poin_aktif === 'aktif') {
            $poin = $layanan->harga * 0.01;
        } else {
            $poin = 0;
        }

        // Kembalikan respons JSON dengan tambahan field point
        return response()->json([
            'id' => $layanan->id,
            'nama' => $layanan->nama,
            'harga' => (int) $layanan->harga,
            'poin' => (int) $poin
        ]);
    }
}
