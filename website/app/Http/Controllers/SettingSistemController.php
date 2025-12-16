<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingSistem;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class SettingSistemController extends Controller
{
    public function index()
    {
        $sistems = SettingSistem::first();
        return view('pages.pengaturan.sistem.index', compact('sistems'));
    }

    public function update(Request $request)
    {
        $sistems = SettingSistem::first();

        if ($request->hasFile('imageLogo')) {
            $tujuan_upload = 'img/DataLogo';
            $file = $request->file('imageLogo');
            $filegambar = time() . "_" . $file->getClientOriginalName();

            if ($sistems->logo !== "logo_default.png") {
                $last_upload = 'img/DataPelanggan/' . $sistems->logo;
                if (File::exists($last_upload)) {
                    File::delete($last_upload);
                }
            }

            $sistems->logo = $filegambar;
            $file->move($tujuan_upload, $filegambar);
        }

        $sistems->nama = $request->nama;
        $sistems->no_telp = $request->no_telp;
        $sistems->alamat = $request->alamat;
        $sistems->jam_operasional_buka = $request->jam_operasional_buka;
        $sistems->jam_operasional_tutup = $request->jam_operasional_tutup;

        $sistems->save();
        Alert::success('Success', 'Berhasil Memperbarui Data');
        return redirect()->route('indexSistem');
    }
}
