<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserControllers;
use App\Http\Controllers\AbsensiControllers;
use App\Http\Controllers\LoginControllers;
use App\Http\Controllers\GajiControllers;
use App\Http\Controllers\KreditControllers;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ProdukControllers;
use App\Http\Controllers\CashFlowControllers;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ==================== ROUTE LOGIN & USER ====================
Route::post('login', [LoginControllers::class, 'login']);
Route::get('users', [UserControllers::class, 'index']);
Route::get('users/{id}', [UserControllers::class, 'show']);
Route::post('addnew', [UserControllers::class, 'store']);
Route::put('usersupdate/{id}', [UserControllers::class, 'update']);
Route::delete('usersdelete/{id}', [UserControllers::class, 'destroy']);


// ==================== ROUTE ABSENSI (4 TAHAPAN) ====================
// 1. Ambil riwayat/status absensi user
Route::get('absensi', [AbsensiControllers::class, 'index']);

// 2. [TAHAP 1] Simpan Absen Masuk (disinkronkan dengan React Native)
Route::post('absensi', [AbsensiControllers::class, 'store']); 

// 3. [TAHAP 2, 3, & 4] Update Absen Pulang, Logbook, dan Lembur/Selesai
Route::post('absensi-pulang/{id}', [AbsensiControllers::class, 'updatePulang']);

// 4. Statistik mingguan karyawan
Route::get('/absensi/stats', [AbsensiControllers::class, 'getStatsMingguan']);


// ==================== ROUTE GAJI & KREDIT ====================
Route::get('gaji/{id_user}', [GajiControllers::class, 'index']);
Route::get('gaji/detail/{id_gaji}', [GajiControllers::class, 'show']);
Route::get('kredit', [KreditControllers::class, 'index']);
Route::put('kredit-update/{id}', [KreditControllers::class, 'updateStatus']);


// ==================== ROUTE PRODUK ====================
Route::get('Produk', [ProdukControllers::class, 'index']);
Route::post('add-produk', [ProdukControllers::class, 'store']);
Route::put('update-produk/{id}', [ProdukControllers::class, 'update']);
Route::delete('delete-produk/{id}', [ProdukControllers::class, 'destroy']);


// ==================== ROUTE CASHFLOW / LAPORAN ====================
Route::get('/owner/laporan-list', [CashFlowControllers::class, 'getListLaporan']);
Route::get('/mobile/cashflow/pdf', [CashFlowControllers::class, 'downloadPDF']);
Route::get('/owner/dashboard-stats', [CashFlowControllers::class, 'getMobileDashboardStats']);