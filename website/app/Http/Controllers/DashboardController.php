<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $role = auth()->user()->roles;

            switch ($role) {
                case 'admin':
                    $myData = Admin::all()->where('id_user', '=', auth()->user()->id)->first();
                    $reservasi = Reservasi::where('tanggal_pemesanan', today()->toDateString())->whereIn('status', ['upcoming', 'processing'])->count();
                    $pendapatan = Reservasi::whereYear('tanggal_pemesanan', now()->year)
                        ->whereIn('status', ['finished'])
                        ->whereMonth('tanggal_pemesanan', now()->month)
                        ->sum('biaya');
                    $pelanggan = Pelanggan::count();
                    $datas = [
                        'myData' => $myData,
                        'countReservasi' => $reservasi,
                        'pendapatan' => $pendapatan,
                        'countPelanggan' => $pelanggan,
                    ];
                    break;
                case 'karyawan':
                    $id_karyawan = auth()->user()->karyawans->id;
                    $myData = Karyawan::all()->where('id_user', auth()->user()->id)->first();
                    $reservasi = Reservasi::where('tanggal_pemesanan', today()->toDateString())->where('id_karyawan', $id_karyawan)->whereIn('status', ['upcoming', 'processing'])->count();
                    $datas = [
                        'myData' => $myData,
                        'countReservasi' => $reservasi,
                    ];
                    break;
                case 'pelanggan':
                    $id_user = auth()->user()->id;
                    $myData = Pelanggan::all()->where('id_user', auth()->user()->id)->first();
                    $reservasi = Reservasi::where('tanggal_pemesanan', '>=', today()->toDateString())
                        ->where('id_user', $id_user)
                        ->count();
                    $datas = [
                        'myData' => $myData,
                        'countReservasi' => $reservasi,
                    ];
                    break;
            }
        }
        return view('pages.dashboard.dashboard', $datas);
    }
}
