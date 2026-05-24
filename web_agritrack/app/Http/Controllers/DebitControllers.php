<?php

namespace App\Http\Controllers;
use App\Models\Debit;

use Illuminate\Http\Request;

class DebitControllers extends Controller
{
    public function indexWeb()
    {
        $debit = Debit::all();
        return view('cashflow.debit', compact('debit'));
    }

     public function create()
    {
        return view('cashflow.input_debit');
    }

    // Menyimpan data dari form ke database
    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'tanggal' => 'required|date',
        'total_pemasukan' => 'required|numeric',
        'saldo_debit' => 'required|numeric',
        'keterangan' => 'nullable|string', // Tambahkan ini agar tidak error 1364
    ]);

    Debit::create([
        'nama' => $request->nama,
        'total_pemasukan' => $request->total_pemasukan,
        'saldo_debit' => $request->saldo_debit,
        'tanggal' => $request->tanggal,
        'keterangan' => $request->keterangan ?? '-', // Jika kosong, diisi tanda strip
        // 'status' => 'tunggu', // Otomatis diset ke 'tunggu'
    ]);

    return redirect()->route('admin.debit')->with('success', 'Data debit berhasil ditambahkan');
}

public function destroy($id)
{
    // Cari data berdasarkan ID
    $debit = Debit::findOrFail($id);

    // Keamanan tambahan: pastikan hanya menghapus yang statusnya 'tunggu' atau 'tidak disetuju'
    if ($debit->status == 'setuju') {
        return redirect()->route('admin.debit')->with('error', 'Data yang sudah disetujui tidak dapat dihapus.');
    }

    // Hapus data
    $debit->delete();

    // Kembalikan ke halaman daftar dengan pesan sukses
    return redirect()->route('admin.debit')->with('success', 'Data debit berhasil dihapus.');
}



}
