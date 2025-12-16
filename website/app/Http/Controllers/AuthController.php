<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth/login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            Alert::success('Success', 'Login Berhasil');
            return redirect()->route('indexDashboard');
        }
        
        return back()->with('loginError', 'Login Gagal!');
    }

    public function create(){
        return view('auth/registrasi');
    }

    public function store(Request $request){
        $validated = $request->validate(
            [
                'email' => 'required|unique:users',
                'password' => 'required|string|confirmed',
                'password_confirmation' => 'required|string',
            ],
            [
                'email.unique' => 'Email telah terdaftar.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]
        );
        $user = User::create([
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password'],),
            'roles' => "pelanggan",
        ]);

        $pelanggan = [
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'id_user' => $user->id,
        ];

        Pelanggan::create($pelanggan);
        Alert::success('Success', 'Akun berhasil dibuat');
        return redirect()->route('indexAuth')->with($validated);
    }

    public function out()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('indexAuth');
    }
    
}
