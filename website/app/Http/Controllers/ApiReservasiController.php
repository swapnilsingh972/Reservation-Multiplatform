<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Reservasi;
use App\Models\SettingSistem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiReservasiController extends Controller
{
    public function getAvailableSlots(Request $request)
    {
        // Jam buka dan tutup toko
        $setting = SettingSistem::first();
        $jamBuka = Carbon::createFromFormat('H:i:s', $setting->jam_operasional_buka);

        if (Carbon::today()->toDateString() === $request->tanggal_pemesanan && Carbon::now()->greaterThan($jamBuka)) {
            $tokoBuka = Carbon::now();
            $tokoBuka->minute = ceil($tokoBuka->minute / 30) * 30;
            if ($tokoBuka->minute === 60) {
                $tokoBuka->addHour()->minute = 0;
            }
            $tokoBuka->second = 0;
        } else {
            $tokoBuka = $jamBuka;
        }
        $tokoTutup = Carbon::createFromFormat('H:i:s', $setting->jam_operasional_tutup);

        // Data reservasi (contoh)
        $reservations = Reservasi::where('tanggal_pemesanan', $request->tanggal_pemesanan)
            ->where('id_karyawan', $request->id_karyawan)
            ->whereIn('status', ['upcoming', 'processing'])
            ->get(['jam_awal', 'jam_berakhir'])
            ->map(function ($item) {
                return [
                    'jam_awal' => $item->jam_awal, // Kolom dari database sudah dalam format string
                    'jam_berakhir' => $item->jam_berakhir,
                ];
            })->toArray();

        // Durasi layanan yang dipilih (dalam menit)
        $layanan = Layanan::select('durasi')->find($request->id_layanan);
        $serviceDuration = Carbon::parse($layanan->durasi)->hour * 60 + Carbon::parse($layanan->durasi)->minute;

        // Konversi reservasi ke waktu
        $reservations = collect($reservations)->map(function ($reservation) {
            return [
                'jam_awal' => Carbon::createFromFormat('H:i:s', $reservation['jam_awal']),
                'jam_berakhir' => Carbon::createFromFormat('H:i:s', $reservation['jam_berakhir']),
            ];
        });

        // Cari slot kosong
        $availableSlots = $this->findAvailableSlots($tokoBuka, $tokoTutup, $reservations, $serviceDuration);

        return response()->json($availableSlots);
    }

    private function findAvailableSlots($tokoBuka, $tokoTutup, $reservations, $serviceDuration)
    {
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

        return $availableSlots;
    }

    public function getData($user_id)
    {
        $reservasi = Reservasi::with([
            'layanans' => function ($query) {
                $query->withTrashed();
            },
            'karyawans' => function ($query) {
                $query->withTrashed();
            }
        ])->where('id_user', $user_id)->whereIn('status', ['verification', 'upcoming', 'processing'])->whereRaw("STR_TO_DATE(CONCAT(tanggal_pemesanan, ' ', jam_berakhir), '%Y-%m-%d %H:%i:%s') >= ?", [now()])->orderByRaw("status = 'processing' DESC, created_at DESC")->get();

        $data = [];
        foreach ($reservasi as $r) {
            $data[] = [
                'id' => $r->id,
                'tanggal_pemesanan' => $r->tanggal_pemesanan,
                'jam_awal' => $r->jam_awal,
                'karyawan' => $r->karyawans->nama,
                'layanan' => $r->layanans->nama,
                'status' => $r->status,
            ];
        }

        return response()->json($data);
    }

    public function getDataId($id)
    {
        $reservasi = Reservasi::with([
            'layanans' => function ($query) {
                $query->withTrashed();
            },
            'karyawans' => function ($query) {
                $query->withTrashed();
            }
        ])->findOrFail($id);
        $data = [
            'id' => $id,
            'nama' => $reservasi->nama,
            'tanggal_pemesanan' => $reservasi->tanggal_pemesanan,
            'jam_awal' => $reservasi->jam_awal,
            'jam_berakhir' => $reservasi->jam_berakhir,
            'poin_digunakan' => $reservasi->poin_digunakan,
            'biaya' => $reservasi->biaya,
            'karyawan' => $reservasi->karyawans->nama,
            'layanan' => $reservasi->layanans->nama,
            'status' => $reservasi->status,
        ];
        return response()->json($data);
    }

    public function getDataHistory($user_id)
    {
        $reservasi = Reservasi::with([
            'layanans' => function ($query) {
                $query->withTrashed();
            },
            'karyawans' => function ($query) {
                $query->withTrashed();
            }
        ])->where('id_user', $user_id)->orderBy('created_at', 'desc')->get();

        $data = [];
        foreach ($reservasi as $r) {
            $data[] = [
                'id' => $r->id,
                'tanggal_pemesanan' => $r->tanggal_pemesanan,
                'jam_awal' => $r->jam_awal,
                'karyawan' => $r->karyawans->nama,
                'layanan' => $r->layanans->nama,
                'status' => $r->status,
            ];
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $layanan = Layanan::find($request->id_layanan);

        // Konversi durasi layanan dari format H:i:s ke total menit
        $durasiArray = explode(':', $layanan->durasi);
        $durasiMenit = ($durasiArray[0] * 60) + $durasiArray[1];

        // Hitung jam berakhir dengan menambahkan durasi dalam menit
        $jam_berakhir = date("H:i:s", strtotime("+$durasiMenit minutes", strtotime($request->jam_awal)));

        $conflict = Reservasi::where('tanggal_pemesanan', $request->tanggal_pemesanan)
            ->where('id_karyawan', $request->id_karyawan)
            ->whereIn('status', ['upcoming', 'processing'])
            ->where(function ($query) use ($request, $jam_berakhir) {
                $query->where(function ($q) use ($request, $jam_berakhir) {
                    // Memastikan tidak ada tumpang tindih waktu
                    // Jika jam_awal baru berada di antara jam_awal lama dan jam_berakhir lama
                    $q->where('jam_awal', '<', $jam_berakhir)
                        ->where('jam_berakhir', '>', $request->jam_awal);
                })
                    ->orWhere(function ($q) use ($request, $jam_berakhir) {
                        // Jika jam_berakhir baru berada di antara jam_awal lama dan jam_berakhir lama
                        $q->where('jam_awal', '<', $request->jam_awal)
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

        // Simpan reservasi baru
        $reservasi = Reservasi::create([
            'nama' => $request->nama,
            'tanggal_pemesanan' => $request->tanggal_pemesanan,
            'jam_awal' => $request->jam_awal,
            'jam_berakhir' => $jam_berakhir,
            'poin_didapatkan' => $request->poin_didapatkan,
            'poin_digunakan' => $request->poin_digunakan,
            'biaya' => $request->biaya,
            'id_layanan' => $request->id_layanan,
            'id_user' => $request->id_user,
            'id_karyawan' => $request->id_karyawan,
            'status' => 'verification',
        ]);

        $pelanggan = Pelanggan::where('id_user', $request->id_user)->first();

        if ($pelanggan) {
            $pelanggan->poin = $request->poin_total;
            $pelanggan->save();
        }

        return response()->json([
            'success' => true,
            'reservationId' => $reservasi->id
        ], 201);
    }

    public function getDataVerification($id){
        $reservasi = Reservasi::with([
            'layanans' => function ($query) {
                $query->withTrashed();
            },
            'karyawans' => function ($query) {
                $query->withTrashed();
            }
        ])->findOrFail($id);

        $data = [
            'id' => $id,
            'foto_payment' => $reservasi->foto_payment,
            'created_at' => $reservasi->created_at,
        ];
        return response()->json($data);
    }

    public function storeVerification(Request $request, $id)
    {
        $request->validate([
            'imagePayment' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('imagePayment')) {
            try {

                $file = $request->file('imagePayment');
                $tujuan_upload = 'img/DataPayment';
                $filegambar = time() . "_" . $file->getClientOriginalName();
                $file->move($tujuan_upload, $filegambar);

                $reservasi = Reservasi::with([
                    'layanans' => function ($query) {
                        $query->withTrashed();
                    },
                    'karyawans' => function ($query) {
                        $query->withTrashed();
                    }
                ])->findOrFail($id);

                $last_upload = 'img/DataPayment/' . $reservasi->foto_payment;
                if (File::exists($last_upload)) {
                    File::delete($last_upload);
                }

                $reservasi->foto_payment = $filegambar;
                $reservasi->save();

                return response()->json([
                    'message' => 'Image uploaded successfully'
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Failed to upload image'], 500);
            }
        }

        return response()->json(['message' => 'No image found'], 400);
    }

    public function cancel($id)
    {
        $reservasi = Reservasi::find($id);
        $currentTime = Carbon::now();
        $reservasi->status = 'canceled';
        $reservasi->cancelled_at = $currentTime;
        $reservasi->save();

        return response()->json(['message' => 'Pesanan berhasil dibatalkan.']);
    }
}
