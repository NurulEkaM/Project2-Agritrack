<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Debit;
use App\Models\Kredit;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Laporan;

class CashFlowControllers extends Controller
{
    // public function indexCashflow()
    // {
    //     // 1. Query Tabel Debit (Tanpa kolom status di DB, jadi kita buat dummy 'setuju')
    //     $debitQuery = DB::table('debit')
    //         ->select(
    //             'id_debit as id', 
    //             'tanggal', 
    //             'nama', 
    //             DB::raw("'PEMASUKAN' as kategori"), 
    //             'saldo_debit as nominal', 
    //             DB::raw("'setuju' as status"), // Karena tidak ada kolom status, kita anggap semua setuju
    //             DB::raw("'green' as color")
    //         );

    //     // 2. Query Tabel Kredit (Hanya yang statusnya 'setuju')
    //     $cashflow = DB::table('kredit')
    //         ->select(
    //             'id_kredit as id', 
    //             'tanggal', 
    //             'nama', 
    //             DB::raw("'PENGELUARAN' as kategori"), 
    //             'saldo_kredit as nominal', 
    //             'status', 
    //             DB::raw("'red' as color")
    //         )
    //         ->where('status', 'setuju') // FILTER: Hanya tampilkan yang disetujui
    //         ->unionAll($debitQuery)
    //         ->orderBy('tanggal', 'desc')
    //         ->get();

    //     // 3. Hitung Total untuk Card Ringkasan
    //     // Total Pengeluaran hanya dari yang disetujui
    //     $totalPengeluaran = DB::table('kredit')
    //         ->where('status', 'setuju')
    //         ->sum('saldo_kredit');

    //     // Total Pemasukan dari semua data di tabel debit
    //     $totalPemasukan = DB::table('debit')
    //         ->sum('saldo_debit');

    //     return view('cashflow.page', compact('cashflow', 'totalPengeluaran', 'totalPemasukan'));
    // }

    private function getCashflowData()
    {
        $debitQuery = DB::table('debit')
            ->select(
                'id_debit as id', 
                'tanggal', 
                'nama', 
                DB::raw("'PEMASUKAN' as kategori"), 
                'saldo_debit as nominal', 
                DB::raw("'setuju' as status"),
                DB::raw("'green' as color")
            );

        $cashflow = DB::table('kredit')
            ->select(
                'id_kredit as id', 
                'tanggal', 
                'nama', 
                DB::raw("'PENGELUARAN' as kategori"), 
                'saldo_kredit as nominal', 
                'status', 
                DB::raw("'red' as color")
            )
            ->where('status', 'setuju')
            ->unionAll($debitQuery)
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalPengeluaran = DB::table('kredit')->where('status', 'setuju')->sum('saldo_kredit');
        $totalPemasukan = DB::table('debit')->sum('saldo_debit');

        return compact('cashflow', 'totalPengeluaran', 'totalPemasukan');
    }

    public function indexCashflow()
    {
        $data = $this->getCashflowData();
        return view('cashflow.page', $data);
    }

    public function downloadPDF()
{
    $data = $this->getCashflowData();
    
    // DEBUG: Jika ini muncul di browser saat diklik, berarti data aman.
    // Jika tetap putih, berarti masalah ada di render PDF-nya.
    // dd($data); 

    $data['tanggal_cetak'] = now()->format('d F Y');
    
    Laporan::create([
        'judul' => 'Laporan Cashflow ' . now()->format('M Y'),
        'file_path' => 'Laporan-Cashflow-Kiwari-Farm.pdf',
        'tanggal_buat' => now(),
    ]);

    $pdf = Pdf::loadView('cashflow.pdf', $data);
    
    // Gunakan setPaper untuk memastikan ukuran halaman terdefinisi
    return $pdf->setPaper('a4', 'portrait')->download('Laporan-Cashflow-Kiwari-Farm.pdf');
}
// Tambahkan fungsi API untuk Mobile mengambil daftar laporan
// Tambahkan di dalam class CashFlowControllers
public function getListLaporan()
{
    try {
        // Mengambil data dari tabel laporans (pastikan Model Laporan sudah dibuat)
        $laporan = Laporan::orderBy('created_at', 'desc')->get();
        
        return response()->json($laporan);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}