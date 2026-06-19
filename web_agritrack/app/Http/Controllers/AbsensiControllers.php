<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class AbsensiControllers extends Controller
{
    // 1. INPUT ABSENSI DATANG (TAHAP 1)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user'        => 'required|integer',
            'status'         => 'required|in:absen_datang,lembur_datang,tidak_hadir',
            'lokasi'         => 'required|in:pulo,sindang',
            'tanggal_datang' => 'required|date', 
            'image'          => 'nullable|string', 
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        try {
            date_default_timezone_set('Asia/Jakarta');
            
            $waktuLocked = $request->tanggal_datang; 
            $hariIni = Carbon::parse($waktuLocked)->format('Y-m-d');

            // Cek Double Absen
            $sudahAbsen = DB::table('absensi')
                ->where('id_user', $request->id_user)
                ->whereDate('tanggal_datang', $hariIni)
                ->exists();

            if ($sudahAbsen) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal: Anda sudah absen pada tanggal ' . $hariIni
                ], 422); 
            }

            // Proses Foto Selfie
            $fileName = null;
            if ($request->filled('image')) {
                $image = $request->image;
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $fileName = 'selfie_' . time() . '_' . $request->id_user . '.png';
                Storage::disk('public')->put('absensi/' . $fileName, base64_decode($image));
            }

            // Simpan data & tangkap ID item barunya
            $insertedId = DB::table('absensi')->insertGetId([
                'id_user'        => $request->id_user,
                'tanggal_datang' => $waktuLocked,
                'status'         => $request->status,
                'kegiatan'       => $request->kegiatan ?? 'Absen Mobile',
                'lokasi'         => $request->lokasi,
                'image'          => $fileName ? 'absensi/' . $fileName : null,
                'total_lembur'   => 0
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Absensi Berhasil!',
                'id_absensi' => $insertedId
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Eror: ' . $e->getMessage()], 500);
        }
    }

    // 2. UPDATE ABSENSI PULANG + LOGBOOK & STATUS LEMBUR/SELESAI (TAHAP 2, 3, & 4)
    // 2. UPDATE ABSENSI PULANG + LOGBOOK & STATUS LEMBUR/SELESAI (TAHAP 2, 3, & 4)
    public function updatePulang(Request $request, $id)
    {
        // Ubah tanggal_pulang menjadi nullable agar bisa menyimpan NULL saat mulai lembur
        $validator = Validator::make($request->all(), [
            'kegiatan'       => 'required|string',
            'tanggal_pulang' => 'nullable|date_format:Y-m-d H:i:s', 
            'status'         => 'required|in:absen_pulang,lembur,selesai', 
            'total_lembur'   => 'required|integer|min:0',
            'uang_lembur'    => 'nullable|numeric|min:0' 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false, 
                'message' => 'Validasi data gagal.', 
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            date_default_timezone_set('Asia/Jakarta');
            
            $absensi = DB::table('absensi')->where('id_absensi', $id)->first();
            if (!$absensi) {
                return response()->json(['success' => false, 'message' => 'Data absensi tidak ditemukan.'], 404);
            }

            // Update database dengan status akhir & logbook dari aplikasi mobile
            DB::table('absensi')
                ->where('id_absensi', $id)
                ->update([
                    'status'         => $request->status,         
                    'kegiatan'       => $request->kegiatan,       
                    'tanggal_pulang' => $request->tanggal_pulang, // Akan terisi null atau format datetime asli
                    'total_lembur'   => $request->total_lembur,   
                ]);

            return response()->json([
                'success' => true, 
                'message' => 'Berhasil memperbarui rangkaian tahapan status absensi pulang!',
                'status_terkunci' => $request->status
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }
    // 3. GET RIWAYAT
    public function index(Request $request)
    {
        $id_user = $request->query('id_user');
        $absensi = Absensi::where('id_user', $id_user)->orderBy('tanggal_datang', 'desc')->get();
        return response()->json(['success' => true, 'results' => $absensi], 200);
    }

    public function indexWeb()
    {
        $absensi = Absensi::with('user')->orderBy('id_absensi', 'desc')->get();
        return view('absensi.page', compact('absensi'));
    }

    public function getStatsMingguan(Request $request)
    {
        $id_user = $request->query('id_user');
        
        $startOfWeek = Carbon::now()->startOfWeek(); 
        $endOfWeek = Carbon::now()->endOfWeek();

        $jumlahHadir = DB::table('absensi')
            ->where('id_user', $id_user)
            ->whereBetween('tanggal_datang', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['absen_pulang', 'lembur', 'selesai', 'lembur_datang'])
            ->count();

        $hariKerja = 5; 
        $persentase = ($jumlahHadir / $hariKerja) * 100;

        return response()->json([
            'success' => true,
            'hadir' => $jumlahHadir,
            'total_hari' => $hariKerja,
            'persentase' => round($persentase),
        ]);
    }

    // 4. UPDATE ABSENSI VIA WEB (FITUR EDIT ADMIN)
    public function updateWeb(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_datang' => 'required|date',
            'tanggal_pulang' => 'nullable|date',
            'lokasi'         => 'required|in:pulo,sindang',
            'status'         => 'required|in:absen_datang,absen_pulang,lembur,selesai,lembur_datang,tidak_hadir',
            'kegiatan'       => 'nullable|string',
            'total_lembur'   => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::table('absensi')
                ->where('id_absensi', $id) 
                ->update([
                    'tanggal_datang' => $request->tanggal_datang,
                    'tanggal_pulang' => $request->tanggal_pulang,
                    'lokasi'         => $request->lokasi,
                    'status'         => $request->status,
                    'kegiatan'       => $request->kegiatan ?? 'Diubah oleh Admin',
                    'total_lembur'   => $request->total_lembur,
                ]);

            return redirect()->back()->with('success', 'Data absensi berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    // 5. CETAK PDF
public function cetakPdf(Request $request)
{
    $bulan = $request->bulan ? (int)$request->bulan : (int)date('m'); 
    $tahun = $request->tahun ? (int)$request->tahun : (int)date('Y');
    $id_user = $request->id_user; 

    // Cek nama kolom yang benar di tabel users Anda!
    // Jika di tabel users kolomnya bernama 'id', maka kode Anda sudah benar.
    // Jika kolomnya bernama 'id_user', ubah bagian 'users.id_user' menjadi 'users.id_user'.
    
    $query = DB::table('absensi')
        ->join('users', 'absensi.id_user', '=', 'users.id_user') // <-- PERIKSA BAGIAN INI
        ->select('absensi.*', 'users.nama as nama_pegawai');

    if ($id_user) {
        $query->where('absensi.id_user', $id_user);
    }

    $query->whereMonth('absensi.tanggal_datang', $bulan)
          ->whereYear('absensi.tanggal_datang', $tahun);

    $data = $query->orderBy('absensi.tanggal_datang', 'asc')->get();

    // Sisa kode sama...
    $namaPegawai = $id_user ? (\App\Models\User::find($id_user)->nama ?? 'Pegawai') : 'Semua Pegawai';
    $totalLemburKeseluruhan = $data->sum('total_lembur');
    $totalPendapatanLembur = $totalLemburKeseluruhan * 10000;

    $pdf = PDF::loadView('absensi.pdf', compact('data', 'bulan', 'tahun', 'namaPegawai', 'totalLemburKeseluruhan', 'totalPendapatanLembur'));
    return $pdf->stream('Laporan_Absensi_' . $namaPegawai . '.pdf');
}
}