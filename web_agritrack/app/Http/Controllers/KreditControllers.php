<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kredit;    
use Illuminate\Support\Facades\DB;


class KreditControllers extends Controller
{
    // INDEX - Mengambil semua data
    public function index()
    {
        $kredit = Kredit::all();
        return response()->json([
            'results' => $kredit
        ], 200);
    }

    // Di KreditController.php
    public function indexWeb()
    {
        $kredit = Kredit::all();
        return view('cashflow.kredit', compact('kredit'));
    }

    // UPDATE STATUS - Mengubah status dari 'tunggu' menjadi 'setuju' atau 'tidak disetuju'
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:setuju,tidak disetuju,tunggu'
        ]);

        $kredit = Kredit::find($id);

        if (!$kredit) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        try {
            DB::beginTransaction();

            // 1. Update status di tabel Kredit
            $kredit->status = $request->status;
            $kredit->save();

            // 2. Jika kredit ini terhubung dengan Gaji (memiliki id_gaji)
            if ($kredit->id_gaji) {
                // Konversi status kredit ke status gaji
                // Kredit: 'setuju' -> Gaji: 'setuju'
                // Kredit: 'tidak disetuju' -> Gaji: 'tidak_disetujui'
                $statusGaji = $request->status;
                if ($request->status == 'tidak disetuju') {
                    $statusGaji = 'tidak_disetujui';
                } else if ($request->status == 'setuju') {
                    $statusGaji = 'setuju';
                }

                DB::table('gaji')
                    ->where('id_gaji', $kredit->id_gaji)
                    ->update(['status' => $statusGaji]);
            }

            DB::commit();
            return response()->json([
                'message' => 'Status kredit dan gaji berhasil diperbarui',
                'data' => $kredit
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal memperbarui status: ' . $e->getMessage()], 500);
        }
    }

    // Tambahkan ini di dalam class KreditControllers

    public function edit($id)
    {
        $kredit = Kredit::findOrFail($id);
        return view('cashflow.edit_kredit', compact('kredit'));
    }

    // Hapus fungsi updateWeb yang lama, dan gunakan versi ini saja:
public function updateWeb(Request $request, $id)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'saldo_kredit' => 'required|numeric',
        'jenis_pengeluaran' => 'required',
        'tanggal' => 'required|date',
        'keterangan' => 'nullable|string',
    ]);

    $kredit = Kredit::findOrFail($id);
    
    $kredit->update([
        'nama' => $request->nama,
        'saldo_kredit' => $request->saldo_kredit,
        'jenis_pengeluaran' => $request->jenis_pengeluaran,
        'tanggal' => $request->tanggal,
        'keterangan' => $request->keterangan ?? '-',
    ]);

    return redirect()->route('admin.kredit')->with('success', 'Data berhasil diperbarui');
}

    // Tambahkan di dalam class KreditControllers

// Menampilkan halaman input
    public function create()
    {
        return view('cashflow.input_kredit');
    }

    // Menyimpan data dari form ke database
    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'saldo_kredit' => 'required|numeric',
        'jenis_pengeluaran' => 'required|string',
        'tanggal' => 'required|date',
        // 'status' dihapus dari validasi karena kita buat otomatis
        'keterangan' => 'nullable|string', // Tambahkan ini agar tidak error 1364
    ]);

    Kredit::create([
        'nama' => $request->nama,
        'saldo_kredit' => $request->saldo_kredit,
        'jenis_pengeluaran' => $request->jenis_pengeluaran,
        'tanggal' => $request->tanggal,
        'keterangan' => $request->keterangan ?? '-', // Jika kosong, diisi tanda strip
        'status' => 'tunggu', // Otomatis diset ke 'tunggu'
    ]);

    return redirect()->route('admin.kredit')->with('success', 'Data kredit berhasil ditambahkan');
}

public function destroy($id)
{
    // Cari data berdasarkan ID
    $kredit = Kredit::findOrFail($id);

    // Keamanan tambahan: pastikan hanya menghapus yang statusnya 'tunggu' atau 'tidak disetuju'
    if ($kredit->status == 'setuju') {
        return redirect()->route('admin.kredit')->with('error', 'Data yang sudah disetujui tidak dapat dihapus.');
    }

    // Hapus data
    $kredit->delete();

    // Kembalikan ke halaman daftar dengan pesan sukses
    return redirect()->route('admin.kredit')->with('success', 'Data kredit berhasil dihapus.');
}


    
}