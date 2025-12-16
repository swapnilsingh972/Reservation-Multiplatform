<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiKaryawanController;
use App\Http\Controllers\ApiLayananController;
use App\Http\Controllers\ApiPelangganController;
use App\Http\Controllers\ApiReservasiController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//-------------------------------- AUTH ----------------------------------------------------
Route::prefix('auth')->group(function () {
    Route::post('/', [ApiAuthController::class, 'authenticate']);
    Route::post('/register', [ApiAuthController::class, 'store']);
    Route::get('/token', [ApiAuthController::class, 'token'])->middleware('auth:sanctum');
    Route::post('/logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');
});

//-------------------------------- PELANGGAN -------------------------------------------------
Route::prefix('pelanggan')->middleware('auth:sanctum')->group(function () {
    Route::get('/{id}', [ApiPelangganController::class, 'getData']);
});

//-------------------------------- LAYANAN -------------------------------------------------
Route::prefix('layanan')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ApiLayananController::class, 'getData']);
    Route::get('/{id}', [ApiLayananController::class, 'getDataById']);
});

//-------------------------------- KARYAWAN -------------------------------------------------
Route::prefix('karyawan')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ApiKaryawanController::class, 'getData']);
    Route::get('/transactions', [ApiKaryawanController::class, 'countKaryawanTransactions']);
    Route::get('/{id}', [ApiKaryawanController::class, 'getDataById']);
});

//-------------------------------- RESERVASI -------------------------------------------------
Route::prefix('reservasi')->middleware('auth:sanctum')->group(function () {
    Route::get('/available', [ApiReservasiController::class, 'getAvailableSlots']);
    Route::get('/{user_id}', [ApiReservasiController::class, 'getData']);
    Route::get('/ticket/{id}', [ApiReservasiController::class, 'getDataId']);
    Route::get('/history/{user_id}', [ApiReservasiController::class, 'getDataHistory']);
    Route::post('/store', [ApiReservasiController::class, 'store']);
    Route::get('/verification/{id}', [ApiReservasiController::class, 'getDataVerification']);
    Route::post('/verification/{id}', [ApiReservasiController::class, 'storeVerification']);
    Route::put('/cancel/{id}', [ApiReservasiController::class, 'cancel']);
});
