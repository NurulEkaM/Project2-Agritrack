<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserControllers;
use App\Http\Controllers\AbsensiControllers;
use App\Http\Controllers\LoginControllers;
use App\Http\Controllers\GajiControllers;
use App\Http\Controllers\KreditControllers;
use App\Http\Controllers\KaryawanController;

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

// User Management Routes
Route::get('users', [UserControllers::class, 'index']);
Route::get('users/{id}', [UserControllers::class, 'show']);
Route::post('addnew', [UserControllers::class, 'store']);
// Route::put('usersupdate/{id}', [UserControllers::class, 'update']);
Route::delete('usersdelete/{id}', [UserControllers::class, 'destroy']);
// Pastikan baris ini sudah aktif di routes/api.php Anda
Route::put('usersupdate/{id}', [UserControllers::class, 'update']);

Route::post('login', [LoginControllers::class, 'login']);

Route::get('absensi', [AbsensiControllers::class, 'index']);
Route::post('add-absensi', [AbsensiControllers::class, 'store']);
// Tambahkan baris route PUT ini di bagian bawah file routes/api.php kamu
Route::put('absensi-pulang/{id}', [AbsensiControllers::class, 'updatePulang']);

Route::get('gaji/{id_user}', [GajiControllers::class, 'index']);
// Route untuk mengambil rincian detail gaji berdasarkan id_gaji
Route::get('gaji/detail/{id_gaji}', [GajiControllers::class, 'show']);

Route::get('kredit', [KreditControllers::class, 'index']);
Route::put('kredit-update/{id}', [KreditControllers::class, 'updateStatus']);

