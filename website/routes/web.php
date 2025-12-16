<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\SettingSistemController;
use App\Models\Reservasi;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//-------------------------------- AUTH ----------------------------------------------------
Route::prefix('/')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('indexAuth');
    Route::get('/create', [AuthController::class, 'create'])->name('registerAuth');
    Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticateAuth');
    Route::post('/store', [AuthController::class, 'store'])->name('storeAuth');
    Route::post('/out-auth', [AuthController::class, 'out'])->name('outAuth');
});

//-------------------------------- DASHBOARD -----------------------------------------------
Route::get('/dashboard', [DashboardController::class, 'index'])->name('indexDashboard');

//-------------------------------- MANAJEMEN USER ------------------------------------------
Route::prefix('pengguna')->group(function () {
    Route::get('/', [PenggunaController::class, 'index'])->name('indexPengguna');
    Route::get('/update/{id}', [PenggunaController::class, 'update'])->name('updatePengguna');
});

//-------------------------------- MANAJEMEN KARYAWAN --------------------------------------
Route::prefix('karyawan')->group(function () {
    Route::get('/', [KaryawanController::class, 'index'])->name('indexKaryawan');
    Route::get('/create', [KaryawanController::class, 'create'])->name('createKaryawan');
    Route::get('/edit/{id}', [KaryawanController::class, 'edit'])->name('editKaryawan');
    Route::post('/store', [KaryawanController::class, 'store'])->name('storeKaryawan');
    Route::post('/update/{id}', [KaryawanController::class, 'update'])->name('updateKaryawan');
    Route::delete('/delete/{id}', [KaryawanController::class, 'delete'])->name('deleteKaryawan');
});

//-------------------------------- MANAJEMEN PELANGGAN --------------------------------------
Route::prefix('pelanggan')->group(function () {
    Route::get('/', [PelangganController::class, 'index'])->name('indexPelanggan');
    Route::get('/create', [PelangganController::class, 'create'])->name('createPelanggan');
    Route::get('/edit/{id}', [PelangganController::class, 'edit'])->name('editPelanggan');
    Route::post('/store', [PelangganController::class, 'store'])->name('storePelanggan');
    Route::post('/update/{id}', [PelangganController::class, 'update'])->name('updatePelanggan');
    Route::delete('/delete/{id}', [PelangganController::class, 'delete'])->name('deletePelanggan');
});

//-------------------------------- LAYANAN --------------------------------------------------
Route::prefix('layanan')->group(function () {
    Route::get('/', [LayananController::class, 'index'])->name('indexLayanan');
    Route::get('/create', [LayananController::class, 'create'])->name('createLayanan');
    Route::get('/edit/{id}', [LayananController::class, 'edit'])->name('editLayanan');
    Route::post('/store', [LayananController::class, 'store'])->name('storeLayanan');
    Route::post('/update/{id}', [LayananController::class, 'update'])->name('updateLayanan');
    Route::delete('/delete/{id}', [LayananController::class, 'delete'])->name('deleteLayanan');
});

//-------------------------------- SETTING SISTEM ---------------------------------------------
Route::prefix('sistem')->group(function () {
    Route::get('/', [SettingSistemController::class, 'index'])->name('indexSistem');
    Route::post('/update', [SettingSistemController::class, 'update'])->name('updateSistem');
});

//karyawan
//-------------------------------- RESERVASI --------------------------------------------------
Route::prefix('indexreservasi')->group(function () {
    Route::get('/karyawan', [ReservasiController::class, 'indexKaryawan'])->name('indexReservasiKaryawan');
    Route::get('/admin', [ReservasiController::class, 'indexAdmin'])->name('indexReservasiAdmin');
});

//-------------------------------- RESERVASI --------------------------------------------------
Route::prefix('reservasi')->group(function () {
    Route::get('/', [ReservasiController::class, 'index'])->name('indexReservasi');
    Route::get('/create1/{id}', [ReservasiController::class, 'create1'])->name('create1Reservasi');
    Route::post('/store1', [ReservasiController::class, 'store1'])->name('store1Reservasi');
    Route::get('/create2', [ReservasiController::class, 'create2'])->name('create2Reservasi');
    Route::post('/store2', [ReservasiController::class, 'store2'])->name('store2Reservasi');
    Route::get('/create3', [ReservasiController::class, 'create3'])->name('create3Reservasi');
    Route::post('/store3', [ReservasiController::class, 'store3'])->name('store3Reservasi');
    Route::post('/update-status', [ReservasiController::class, 'updateStatus'])->name('reservasi.updateStatus');
});
