<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Reservasi;
use App\Models\SettingSistem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiKaryawanController extends Controller
{
    public function getData(Request $request)
    {
        $request->validate([
            'tanggal_pemesanan' => 'required|date',
        ]);
    
        $setting = SettingSistem::first();
        $batasWaktu = $setting->jam_operasional_tutup;
    
        // Ambil tanggal dan jam saat ini
        $currentDate = now()->toDateString();
        $currentTime = now()->format('H:i:s');
    
        // Jika tanggal_pemesanan sama dengan tanggal hari ini dan waktu melebihi batas waktu
        if ($request->tanggal_pemesanan === $currentDate && $currentTime > $batasWaktu) {
            // Kembalikan data kosong
            return response()->json([]);
        }
    
        // Ambil karyawan yang tidak memiliki pemesanan yang melebihi batas jam_berakhir
        $karyawans = Karyawan::select('id', 'nama', 'foto')
            ->whereDoesntHave('reservasis', function ($query) use ($request, $batasWaktu) {
                $query->whereDate('tanggal_pemesanan', $request->tanggal_pemesanan)
                    // Pastikan jam_berakhir tidak melebihi batas waktu
                    ->where('jam_berakhir', '>', $batasWaktu);
            })
            ->distinct()
            ->get();
    
        return response()->json($karyawans);
    }    

    public function getDataById($id)
    {

        $karyawan = Karyawan::select('id', 'nama')
            ->find($id);

        return response()->json($karyawan);
    }

    public function countKaryawanTransactions(){
        $today = Carbon::today();

        $karyawans = Karyawan::all();

        $data = $karyawans->map(function($karyawan) use ($today) {
            $total_reservasi = Reservasi::where('id_karyawan', $karyawan->id)
                ->whereDate('tanggal_pemesanan', $today)
                ->count();

            return [
                'id' => $karyawan->id,
                'nama' => $karyawan->nama,
                'total_reservasi' => $total_reservasi
            ];
        });

        return response()->json($data);
    }
}
