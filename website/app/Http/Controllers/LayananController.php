<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = Layanan::all();

        // Manipulasi waktu untuk setiap layanan
        foreach ($layanans as $layanan) {
            // Parse string waktu menggunakan Carbon
            $time = Carbon::parse($layanan->durasi);

            // Logika untuk mengubah format waktu
            if ($time->hour > 0) {
                // Jika lebih dari atau sama dengan satu jam, tampilkan dalam format jam
                $layanan->formatted_waktu = $time->hour . " jam " . $time->minute . " menit";
            } elseif ($time->minute > 0) {
                // Jika lebih dari atau sama dengan satu menit, tampilkan dalam format menit
                $layanan->formatted_waktu = $time->minute . " menit";
            } else {
                // Jika kurang dari satu menit, tampilkan dalam format detik
                $layanan->formatted_waktu = $time->second . " detik";
            }
        }

        return view('pages.layanan.jenisLayanan.index', compact('layanans'));
    }

    public function create()
    {
        return view('pages.layanan.jenisLayanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'durasi' => ['required',  function ($attribute, $value, $fail) {
                if (strtotime($value) === strtotime('00:00')) {
                    $fail('Durasi tidak boleh diisi dengan 00:00');
                }
            },],
        ], [
            'durasi.required' => 'Durasi layanan wajib diisi.',
        ]);

        $poin = $request->has('poin') ? 'aktif' : 'non';

        $layanan = [
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'durasi' => $request->durasi,
            'poin_aktif' => $poin,
            'harga' => $request->harga,
        ];

        if ($request->hasFile('imageLayanan')) {
            $tujuan_upload = 'img/DataLayanan';
            $file = $request->file('imageLayanan');
            $filegambar = time() . "_" . $file->getClientOriginalName();
            $file->move($tujuan_upload, $filegambar);
            $layanan['foto'] = $filegambar;
        }

        Layanan::create($layanan);
        Alert::success('Success', 'Berhasil Menambahkan Data');
        return redirect()->route('indexLayanan');
    }

    public function edit($id)
    {
        $layanan = Layanan::where('id', $id)->first();

        return view('pages.layanan.jenisLayanan.edit', compact('layanan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'durasi' => ['required',  function ($attribute, $value, $fail) {
                if (strtotime($value) === strtotime('00:00')) {
                    $fail('Durasi tidak boleh diisi dengan 00:00');
                }
            },],
        ], [
            'durasi.required' => 'Durasi layanan wajib diisi.',
        ]);
        $layanan = Layanan::findOrFail($id);
        $poin = $request->has('poin') ? 'aktif' : 'non';

        if ($request->hasFile('imageLayanan')) {
            $tujuan_upload = 'img/DataLayanan';
            $file = $request->file('imageLayanan');
            $filegambar = time() . "_" . $file->getClientOriginalName();

            if ($layanan->foto !== "default_foto.jpg") {
                $last_upload = 'img/DataLayanan/' . $layanan->foto;
                if (File::exists($last_upload)) {
                    File::delete($last_upload);
                }
            }

            $layanan->foto = $filegambar;
            $file->move($tujuan_upload, $filegambar);
        }

        $layanan->nama = $request->nama;
        $layanan->deskripsi = $request->deskripsi;
        $layanan->durasi = $request->durasi;
        $layanan->poin_aktif = $poin;
        $layanan->harga = $request->harga;
        $layanan->save();

        Alert::success('Success', 'Berhasil Memperbarui Data');
        return redirect()->route('indexLayanan');
    }

    public function delete(Layanan $id)
    {
        if ($id->foto !== "default_foto.jpg") {
            $last_upload = 'img/DataLayanan/' . $id->foto;
            if (File::exists($last_upload)) {
                File::delete($last_upload);
            }
        }
        $id->delete();
        Alert::success('Success', 'Data layanan berhasil dihapus.');
        return redirect()->back();
    }
}
