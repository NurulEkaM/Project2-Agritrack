<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginControllers;
use App\Http\Controllers\KreditControllers;
use App\Http\Controllers\AbsensiControllers;
use App\Http\Controllers\DebitControllers;
use App\Http\Controllers\CashFlowControllers;
use App\Http\Controllers\GajiControllers;   
use App\Http\Controllers\TransaksiControllers;

Route::get('/', function () {
    return view('welcome');
});

// 1. Route untuk MENAMPILKAN halaman login (Ini yang tadinya error)
Route::get('/login', function () {
    return view('login'); // Pastikan nama filenya adalah login.blade.php
})->name('login');
Route::post('/login', [LoginControllers::class, 'loginWeb'])->name('login.post');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');


Route::post('/logout', [LoginControllers::class, 'logout'])->name('logout');

Route::get('/cashflow/page', [CashFlowControllers::class, 'indexCashflow'])->name('cashflow.page');
Route::get('/cashflow/pdf', [CashFlowControllers::class, 'downloadPDF'])->name('cashflow.pdf');

Route::get('/cashflow/kredit', [KreditControllers::class, 'indexWeb'])->name('admin.kredit');
Route::get('/cashflow/kredit/{id}/edit', [KreditControllers::class, 'edit'])->name('admin.kredit.edit');
Route::put('/cashflow/kredit/{id}', [KreditControllers::class, 'updateWeb'])->name('admin.kredit.update');

Route::get('/kredit/create', [KreditControllers::class, 'create'])->name('kredit.create');
Route::post('/kredit/store', [KreditControllers::class, 'store'])->name('kredit.store');
// Tambahkan baris ini
Route::delete('/admin/kredit/{id}', [KreditControllers::class, 'destroy'])->name('admin.kredit.destroy');
Route::put('/admin/kredit/update/{id}', [KreditControllers::class, 'updateWeb'])->name('admin.kredit.update');

// --- TAMBAHKAN ROUTE BARU INI DI BAWAH ---
// ==================== ROUTE ABSENSI WEB ====================

Route::get('/absensi/page', [AbsensiControllers::class, 'indexWeb'])->name('absensi.page');
Route::get('/absensi/{id}/edit', [AbsensiControllers::class, 'edit'])->name('absensi.edit');
Route::put('/absensi/{id}', [AbsensiControllers::class, 'update'])->name('absensi.update');

Route::get('/cashflow/debit', [DebitControllers::class, 'indexWeb'])->name('admin.debit');
Route::get('/debit/create', [DebitControllers::class, 'create'])->name('debit.create');
Route::post('/debit/store', [DebitControllers::class, 'store'])->name('debit.store');
// Tambahkan baris ini
Route::delete('/admin/debit/{id}', [DebitControllers::class, 'destroy'])->name('admin.debit.destroy');
Route::put('/admin/debit/update/{id}', [DebitControllers::class, 'updateWeb'])->name('admin.debit.update');

Route::get('/gaji/page', [GajiControllers::class, 'indexWeb'])->name('gaji.page');
Route::post('/gaji/generate', [GajiControllers::class, 'generateGaji'])->name('gaji.generate');
// Route untuk mengubah status menjadi tunggu_konfirmasi
Route::put('/gaji/konfirmasi/{id}', [GajiControllers::class, 'konfirmasiAdmin'])->name('gaji.konfirmasi');

Route::get('transaksi/page', [TransaksiControllers::class, 'index'])->name('transaksi.page');
Route::post('transaksi/page', [TransaksiControllers::class, 'store'])->name('transaksi.store');
