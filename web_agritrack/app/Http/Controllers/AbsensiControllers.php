<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AbsensiControllers extends Controller
{
    // 1. INPUT ABSENSI DATANG
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user'        => 'required|integer',
            'status'         => 'required|in:absen_datang,lembur_datang,tidak_hadir',
            'lokasi'         => 'required|in:kebun_lanud,kebun_sadang',
            'tanggal_datang' => 'required|date', // Waktu yang dikunci dari mobile
            'image'          => 'nullable|string', 
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        try {
            date_default_timezone_set('Asia/Jakarta');
            
            // Ambil waktu yang dikirim dari Mobile
            $waktuLocked = $request->tanggal_datang; 
            // Ambil tanggal saja untuk pengecekan double absen
            $hariIni = Carbon::parse($waktuLocked)->format('Y-m-d');

            // 1. Cek Double Absen (Berdasarkan tanggal dari waktu yang dikunci)
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

            // 2. Proses Foto
            $fileName = null;
            if ($request->filled('image')) {
                $image = $request->image;
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $fileName = 'selfie_' . time() . '_' . $request->id_user . '.png';
                Storage::disk('public')->put('absensi/' . $fileName, base64_decode($image));
            }

            // 3. Simpan ke Database
            DB::table('absensi')->insert([
                'id_user'        => $request->id_user,
                'tanggal_datang' => $waktuLocked, // Menggunakan waktu saat user buka page
                'status'         => $request->status,
                'kegiatan'       => $request->kegiatan ?? 'Absen Mobile',
                'lokasi'         => $request->lokasi,
                'image'          => $fileName ? 'absensi/' . $fileName : null,
                'total_lembur'   => 0
            ]);

            return response()->json(['success' => true, 'message' => 'Absensi Berhasil!'], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Eror: ' . $e->getMessage()], 500);
        }
    }

    // 2. UPDATE ABSENSI PULANG + LEMBUR OTOMATIS
    public function updatePulang(Request $request, $id)
{
    // 1. Tambahkan tanggal_pulang ke validasi
    $validator = Validator::make($request->all(), [
        'kegiatan'       => 'required|string',
        'tanggal_pulang' => 'required|date_format:Y-m-d H:i:s' // Menerima waktu dari mobile
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'message' => 'Validasi gagal.'], 422);
    }

    try {
        date_default_timezone_set('Asia/Jakarta');
        
        // Gunakan waktu yang dikirim dari Mobile
        $waktuPulang = new \DateTime($request->tanggal_pulang);
        $waktuPulangStr = $waktuPulang->format('Y-m-d H:i:s');

        $absensi = DB::table('absensi')->where('id_absensi', $id)->first();
        if (!$absensi) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }

        // --- LOGIKA HITUNG LEMBUR BERDASARKAN WAKTU KUNCI ---
        $totalLembur = 0;
        $batasLembur = new \DateTime($waktuPulang->format('Y-m-d') . ' 17:00:00');

        if ($waktuPulang > $batasLembur) {
            $diff = $waktuPulang->diff($batasLembur);
            $totalLembur = $diff->h + ($diff->days * 24);
        }

        // 3. Update database
        DB::table('absensi')
            ->where('id_absensi', $id)
            ->update([
                'status'         => 'absen_pulang',
                'kegiatan'       => $request->kegiatan,
                'tanggal_pulang' => $waktuPulangStr, // Pakai waktu dari Mobile
                'total_lembur'   => $totalLembur,
            ]);

        return response()->json([
            'success' => true, 
            'message' => 'Berhasil melakukan absen pulang kerja!',
            'waktu_pulang' => $waktuPulangStr,
            'lembur' => $totalLembur
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
        $absensi = Absensi::all();
        return view('absensi.page', compact('absensi'));
    }
}