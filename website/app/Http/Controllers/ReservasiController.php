<?php

namespace App\Http\Controllers;

use App\Events\MessageCreated;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Reservasi;
use App\Models\SettingSistem;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    public function index()
    {
        $layanans = Layanan::all();
        foreach ($layanans as $layanan) {
            $layanan->formatted_waktu = $this->formatTime($layanan->durasi);
        }
        return view('pages.reservasi.create.index', compact('layanans'));
    }

    public function formatTime($duration)
    {
        $time = Carbon::parse($duration);
        if ($time->hour > 0) {
            return $time->hour . " jam " . $time->minute . " menit";
        } elseif ($time->minute > 0) {
            return $time->minute . " menit";
        } else {
            return $time->second . " detik";
        }
    }

    public function create1($id)
    {
        $layanans = Layanan::findOrFail($id);
        $layanans->formatted_waktu = $this->formatTime($layanans->durasi);

        $setting = SettingSistem::first();
        $batasWaktu = $setting->jam_operasional_tutup;
        $tanggal = Carbon::today()->toDateString();

        // Ambil tanggal dan jam saat ini
        $currentDate = now()->toDateString();
        $currentTime = now()->format('H:i:s');

        // Jika tanggal_pemesanan sama dengan tanggal hari ini dan waktu melebihi batas waktu
        if ($tanggal === $currentDate && $currentTime > $batasWaktu) {
            $karyawans = collect();
            // Jika batas waktu terlewat, kembalikan tampilan kosong atau pesan
            return view('pages.reservasi.create.create1', compact('karyawans', 'layanans', 'tanggal'));
        }

        // Ambil karyawan yang tidak memiliki pemesanan yang melebihi batas jam_berakhir
        $karyawans = Karyawan::select('id', 'nama', 'foto')
            ->whereDoesntHave('reservasis', function ($query) use ($tanggal, $batasWaktu) {
                $query->whereDate('tanggal_pemesanan', $tanggal)
                    // Pastikan jam_berakhir tidak melebihi batas waktu
                    ->where('jam_berakhir', '>', $batasWaktu);
            })
            ->distinct()
            ->get();

        // Kirim data ke tampilan menggunakan compact
        return view('pages.reservasi.create.create1', compact('karyawans', 'layanans', 'tanggal'));
    }

    public function store1(Request $request)
    {
        // Menyimpan karyawan_id dan layanan_id ke dalam session
        session()->put('step1.id_karyawan', $request->input('karyawan_id'));
        session()->put('step1.id_layanan', $request->input('layanan_id'));

        // Redirect ke halaman berikutnya atau halaman lain setelah memilih karyawan dan layanan
        return redirect()->route('create2Reservasi'); // Ganti dengan route yang sesuai
    }

    public function create2(Request $request)
    {
        $id_layanan = $request->session()->get('step1')['id_layanan'];
        $id_karyawan = $request->session()->get('step1')['id_karyawan'];

        $layanans = Layanan::findOrFail($id_layanan);
        $layanans->formatted_waktu = $this->formatTime($layanans->durasi);

        $karyawan = Karyawan::findOrFail($id_karyawan);

        $tanggal = Carbon::today()->toDateString();

        $setting = SettingSistem::first();
        $jamBuka = Carbon::createFromFormat('H:i:s', $setting->jam_operasional_buka);

        if (Carbon::now()->greaterThan($jamBuka)) {
            $tokoBuka = Carbon::now();
            $tokoBuka->minute = ceil($tokoBuka->minute / 30) * 30;
            if ($tokoBuka->minute === 60) {
                $tokoBuka->addHour()->minute = 0;
            }
            $tokoBuka->second = 0;
        } else {
            $tokoBuka = $jamBuka;
        }

        //$tokoBuka = Carbon::createFromFormat('H:i:s', $setting->jam_operasional_buka);
        $tokoTutup = Carbon::createFromFormat('H:i:s', $setting->jam_operasional_tutup);

        // Data reservasi
        $reservations = Reservasi::where('tanggal_pemesanan', $tanggal)
            ->where('id_karyawan', $id_karyawan)
            ->whereIn('status', ['upcoming', 'processing'])
            ->get(['jam_awal', 'jam_berakhir'])
            ->map(function ($item) {
                return [
                    'jam_awal' => Carbon::createFromFormat('H:i:s', $item->jam_awal),
                    'jam_berakhir' => Carbon::createFromFormat('H:i:s', $item->jam_berakhir),
                ];
            });

        // Durasi layanan yang dipilih (dalam menit)
        $layanan = Layanan::select('durasi')->find($id_layanan);
        $serviceDuration = Carbon::parse($layanan->durasi)->hour * 60 + Carbon::parse($layanan->durasi)->minute;

        // Cari slot kosong
        $availableSlots = [];
        $startTime = $tokoBuka;

        // Tambahkan dummy akhir untuk mempermudah perhitungan slot terakhir
        $reservations = $reservations->sortBy('jam_awal');
        $reservations->push(['jam_awal' => $tokoTutup, 'jam_berakhir' => $tokoTutup]);

        foreach ($reservations as $reservation) {
            $reservedStart = $reservation['jam_awal'];
            $reservedEnd = $reservation['jam_berakhir'];

            // Periksa apakah ada ruang antara waktu saat ini dan awal reservasi
            while ($startTime->addMinutes($serviceDuration)->lte($reservedStart)) {
                $availableSlots[] = [
                    'jam_awal' => $startTime->copy()->subMinutes($serviceDuration)->format('H:i:s'),
                    'jam_berakhir' => $startTime->format('H:i:s'),
                ];
            }

            // Set waktu mulai ke waktu selesai reservasi jika berada dalam slot
            $startTime = max($startTime, $reservedEnd);
        }

        // Return view dengan compact
        return view('pages.reservasi.create.create2', compact('availableSlots', 'layanans', 'karyawan', 'tanggal'));
    }

    public function store2(Request $request)
    {
        // Menyimpan karyawan_id dan layanan_id ke dalam session
        session()->put('step2.id_karyawan', $request->input('karyawan_id'));
        session()->put('step2.id_layanan', $request->input('layanan_id'));
        session()->put('step2.availableSlots', $request->input('availableSlots'));

        // Redirect ke halaman berikutnya atau halaman lain setelah memilih karyawan dan layanan
        return redirect()->route('create3Reservasi'); // Ganti dengan route yang sesuai
    }

    public function create3(Request $request)
    {
        $id_layanan = $request->session()->get('step2')['id_layanan'];
        $id_karyawan = $request->session()->get('step2')['id_karyawan'];
        $slot = json_decode($request->session()->get('step2')['availableSlots'], true);

        // Ambil data layanan dan karyawan berdasarkan ID yang disimpan di session
        $layanan = Layanan::findOrFail($id_layanan);
        $karyawan = Karyawan::findOrFail($id_karyawan);

        $layanan->formatted_waktu = $this->formatTime($layanan->durasi);

        return view('pages.reservasi.create.create3', compact('karyawan', 'layanan', 'slot'));
    }

    public function store3(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
        ]);

        $tanggal = Carbon::today()->toDateString();
        $layanan = Layanan::find($request->id_layanan);

        // Konversi durasi layanan dari format H:i:s ke total menit
        $durasiArray = explode(':', $layanan->durasi);
        $durasiMenit = ($durasiArray[0] * 60) + $durasiArray[1];

        // Hitung jam berakhir dengan menambahkan durasi dalam menit
        $jam_berakhir = date("H:i:s", strtotime("+$durasiMenit minutes", strtotime($request->jam_awal)));

        $conflict = Reservasi::where('tanggal_pemesanan', $tanggal)
            ->where('id_karyawan', $request->id_karyawan)
            ->whereIn('status', ['upcoming', 'processing'])
            ->where(function ($query) use ($request, $jam_berakhir) {
                // Memastikan tidak ada tumpang tindih waktu
                $query->where(function ($q) use ($request) {
                    // Jam awal dari reservasi baru lebih besar atau sama dengan jam_berakhir dari reservasi lama
                    $q->where('jam_awal', '<', $request->jam_awal)
                        ->where('jam_berakhir', '>', $request->jam_awal);
                })
                    ->orWhere(function ($q) use ($jam_berakhir) {
                        // Jam berakhir dari reservasi baru lebih kecil atau sama dengan jam_awal dari reservasi lama
                        $q->where('jam_awal', '<', $jam_berakhir)
                            ->where('jam_berakhir', '>', $jam_berakhir);
                    });
            })
            ->exists();

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu yang dipilih bertabrakan dengan reservasi lain.',
            ], 400);
        }

        Reservasi::create([
            'nama' => $request->nama,
            'tanggal_pemesanan' => $tanggal,
            'jam_awal' => $request->jam_awal,
            'jam_berakhir' => $jam_berakhir,
            'biaya' => $layanan->harga,
            'id_layanan' => $request->id_layanan,
            'id_user' => auth()->user()->id,
            'id_karyawan' => $request->id_karyawan,
            'status' => 'upcoming',
        ]);
        $data = [
            "id_karyawan" => $request->id_karyawan,
            "jam_berakhir" => $jam_berakhir,
        ];
        MessageCreated::dispatch($data);

        if (auth()->user()->roles == 'karyawan') {
            return redirect()->route('indexReservasiKaryawan');
        } elseif (auth()->user()->roles == 'pelanggan') {
            return redirect()->route('indexDashboard');
        }
    }

    public function indexKaryawan(Request $request)
    {
        // Ambil tanggal dari input atau gunakan tanggal hari ini jika tidak ada input
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());
        $currentTime = Carbon::now()->toTimeString();

        $id_karyawan = auth()->user()->karyawans->id;

        // Ambil data berdasarkan tanggal yang dipilih
        $reservasis = Reservasi::with([
            'users.pelanggans' => function ($query) {
                $query->withTrashed();
            },
            'layanans' => function ($query) {
                $query->withTrashed();
            },
            'karyawans' => function ($query) {
                $query->withTrashed();
            },
            'users' => function ($query) {
                $query->withTrashed();
            },
        ])->whereDate('tanggal_pemesanan', '=', $tanggal)->where('id_karyawan', $id_karyawan)->orderBy('tanggal_pemesanan', 'asc')->orderBy('jam_awal', 'asc')->get();

        $currentReservation = Reservasi::whereDate('tanggal_pemesanan', '=', $tanggal)
            ->where('id_karyawan', $id_karyawan)
            ->whereIn('status', ['upcoming', 'processing'])
            ->whereTime('jam_awal', '<=', $currentTime)
            ->whereTime('jam_berakhir', '>=', $currentTime)
            ->first();

        return view('pages.reservasi.indexKaryawan', compact('reservasis', 'tanggal', 'currentReservation'));
    }

    public function indexAdmin(Request $request)
    {
        // Ambil tanggal dari input atau gunakan tanggal hari ini jika tidak ada input
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());
        $currentTime = Carbon::now()->toTimeString();

        // Ambil data berdasarkan tanggal yang dipilih
        $reservasis = Reservasi::with([
            'users.pelanggans' => function ($query) {
                $query->withTrashed();
            },
            'layanans' => function ($query) {
                $query->withTrashed();
            },
            'karyawans' => function ($query) {
                $query->withTrashed();
            },
            'users' => function ($query) {
                $query->withTrashed();
            },
        ])->whereDate('tanggal_pemesanan', '=', $tanggal)->orderBy('tanggal_pemesanan', 'asc')->get();

        return view('pages.reservasi.indexAdmin', compact('reservasis', 'tanggal'));
    }

    public function updateStatus(Request $request)
    {
        $reservasi = Reservasi::find($request->id);
        if ($reservasi) {
            if ($request->status == 'finished') {
                $pelanggan = Pelanggan::where('id_user', $reservasi->id_user)->first();

                if ($pelanggan) {
                    $pelanggan->poin = $pelanggan->poin + $reservasi->poin_didapatkan;
                    $pelanggan->save();
                }
            }

            $reservasi->status = $request->status;
            $reservasi->save();
            return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui.']);
        }

        return response()->json(['success' => false, 'message' => 'Reservasi tidak ditemukan.'], 404);
    }
}
