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
    // 1. Query untuk data Pemasukan (Debit)
    $debitQuery = DB::table('debit')
        ->select(
            'id_debit as id', 
            'tanggal', 
            'nama', 
            DB::raw("'PEMASUKAN' as kategori"), 
            DB::raw("null as sifat"), // Ditambahkan null agar jumlah kolom union seimbang
            'saldo_debit as nominal', 
            DB::raw("'setuju' as status"),
            DB::raw("'green' as color")
        );

    // 2. Query untuk data Pengeluaran (Kredit)
    $cashflow = DB::table('kredit')
        ->select(
            'id_kredit as id', 
            'tanggal', 
            'nama', 
            DB::raw("'PENGELUARAN' as kategori"), // Set kategori utama sebagai PENGELUARAN
            'jenis_pengeluaran as sifat',       // Mengambil nilai 'tetap' / 'tidak tetap' dimasukkan ke alias 'sifat'
            'saldo_kredit as nominal', 
            'status', 
            DB::raw("'red' as color")
        )
        ->where('status', 'setuju')
        ->unionAll($debitQuery)
        ->orderBy('tanggal', 'desc')
        ->get();

    // Total Pengeluaran hanya dari yang disetujui dan pada bulan ini
    $totalPengeluaran = DB::table('kredit')
        ->where('status', 'setuju')
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->sum('saldo_kredit');

    // Total Pemasukan pada bulan ini
    $totalPemasukan = DB::table('debit')
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->sum('saldo_debit');

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

public function indexDashboard()
{
    // Jangan lupa import DB di bagian atas controller jika belum: 
    // use Illuminate\Support\Facades\DB;

    // 1. Total Pemasukan bulan ini
    $totalPemasukan = DB::table('debit')
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->sum('saldo_debit');

    // 2. Total Pengeluaran bulan ini (Kredit yang disetuju)
    $totalPengeluaran = DB::table('kredit')
        ->where('status', 'setuju')
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->sum('saldo_kredit');

    // 3. Absensi hari ini
    $absensiHariIni = DB::table('absensi')
        ->whereDate('tanggal_datang', now()->toDateString())
        ->count();

    // 4. Jumlah Transaksi bulan ini
    $jumlahTransaksi = DB::table('transaksi')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

    // 5. Data Chart: 6 Bulan Terakhir Berurutan Maju (Jan -> Feb -> ... -> Bulan Ini)
    $chartData = [];
    for ($i = 5; $i >= 0; $i--) {
        // Mengurangi bulan dari bulan sekarang secara dinamis
        $monthDate = now()->startOfMonth()->subMonths($i);
        
        $pemasukan = DB::table('debit')
            ->whereMonth('tanggal', $monthDate->month)
            ->whereYear('tanggal', $monthDate->year)
            ->sum('saldo_debit');

        $pengeluaran = DB::table('kredit')
            ->where('status', 'setuju')
            ->whereMonth('tanggal', $monthDate->month)
            ->whereYear('tanggal', $monthDate->year)
            ->sum('saldo_kredit');

        $chartData[] = [
            'm' => $monthDate->translatedFormat('F'), // Menggunakan nama bulan lengkap (e.g., Juni)
            'pemasukan' => (int) $pemasukan,
            'pengeluaran' => (int) $pengeluaran,
        ];
    }

    // 6. Recent Activity (Diperbaiki menggunakan DB::table() dari subquery agar tidak error SQL union)
    $pemasukanQuery = DB::table('debit')
        ->select('tanggal', 'nama', 'saldo_debit as nominal', DB::raw("'pemasukan' as tipe"));

    $recentActivity = DB::table('kredit')
        ->select('tanggal', 'nama', 'saldo_kredit as nominal', DB::raw("'pengeluaran' as tipe"))
        ->unionAll($pemasukanQuery)
        ->orderBy('tanggal', 'desc')
        ->limit(5)
        ->get();

    return view('dashboard', compact(
        'totalPemasukan', 
        'totalPengeluaran', 
        'absensiHariIni', 
        'jumlahTransaksi', 
        'chartData', 
        'recentActivity'
    ));
}

public function getMobileDashboardStats()
{
    try {
        $totalPemasukan = DB::table('debit')->sum('saldo_debit');
        $jumlahKaryawan = DB::table('users')->count();
        $laporanBaru = DB::table('kredit')->where('status', 'tunggu')->count();
        
        $chartData = [];
        // Loop 6 bulan terakhir (sama dengan logika dashboard web)
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->startOfMonth()->subMonths($i);
            
            $pemasukan = DB::table('debit')
                ->whereMonth('tanggal', $monthDate->month)
                ->whereYear('tanggal', $monthDate->year)
                ->sum('saldo_debit');

            $pengeluaran = DB::table('kredit')
                ->where('status', 'setuju')
                ->whereMonth('tanggal', $monthDate->month)
                ->whereYear('tanggal', $monthDate->year)
                ->sum('saldo_kredit');
            
            $chartData[] = [
                'day' => $monthDate->format('M'), // Jan, Feb, Mar, dst
                'pemasukan' => (float)$pemasukan,
                'pengeluaran' => (float)$pengeluaran
            ];
        }

        return response()->json([
            'total_pendapatan' => $totalPemasukan,
            'pekerja_aktif' => $jumlahKaryawan,
            'laporan_baru' => $laporanBaru,
            'chart_data' => $chartData,
        ], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}