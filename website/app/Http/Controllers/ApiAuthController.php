<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    public function token()
    {
        $user = Auth::user();

        if ($user) {
            return response()->json([
                'user' => $user->email,
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'token' => $token,
            'id_user' => $user->id,
            'id_pelanggan' => $user->pelanggans->id
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|string',
        ], [
            'email.unique' => 'Email telah terdaftar.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Proses selanjutnya jika validasi berhasil
        $validated = $validator->validated();

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'roles' => "pelanggan",
        ]);

        $pelanggan = Pelanggan::create([
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'id_user' => $user->id,
        ]);

        //$token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'id_user' => $user->id,
            'id_pelanggan' => $pelanggan->id,
            //'token' => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        $user = $request->user(); // Ambil user yang sedang login

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete(); // Hapus token aktif
            return response()->json([
                'message' => 'Successfully logged out',
            ], 201);
        }

        return response()->json([
            'message' => 'No authenticated user found',
        ], 401);
    }
}
