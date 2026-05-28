<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gaji;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class GajiControllers extends Controller
{
    public function index(Request $request)
    {
        // Ambil id_user dari parameter URL
        $id_user = $request->route('id_user');

        // Query database berdasarkan kolom id_user
        $gaji = Gaji::where('id_user', $id_user)->get();

        // Kembalikan JSON yang bersih
        return response()->json([
            'results' => $gaji
        ], 200);
    }

    public function show($id_gaji)
    {
        // 1. Ambil data gaji sekaligus JOIN dengan data user terkait
        $gaji = Gaji::with('user')->find($id_gaji);

        if (!$gaji || !$gaji->user) {
            return response()->json([
                'success' => false,
                'message' => 'Data gaji atau user tidak ditemukan'
            ], 404);
        }

        // 2. Ambil nilai dasar dari tabel 'users' kolom 'gaji'
        $gajiBersihDasarUser = $gaji->user->gaji; // Contoh Karyawan1: 9.827.535

        // 3. TERAPKAN RUMUS ANDA
        // Gaji Bersih dikali 5 untuk hitungan mingguan (menjadi Gaji Pokok di UI)
        // $totalGajiMingguan = $gajiBersihDasarUser * 5; 

        // Lembur dikali 10.000 per jam
        $jamLembur = $gaji->total_lembur; // Dari tabel gaji
        $tarifLemburPerJam = 10000;
        $totalUangLembur = $jamLembur * $tarifLemburPerJam;

        // Akumulasi Akhir yang tampil di Banner Hijau
        // $gajiBersihAkhir = $totalGajiMingguan + $totalUangLembur;
        $gajiBersihAkhir = $gajiBersihDasarUser + $totalUangLembur; // Gaji Pokok Harian + Total Uang Lembur

        // 4. Olah Periode Tanggal
        $tanggalGaji = Carbon::parse($gaji->tanggal);
        $periodeMulai = $tanggalGaji->copy()->subDays(6)->translatedFormat('d M');
        $periodeSelesai = $tanggalGaji->translatedFormat('d M Y');
        $tanggalDibayar = $tanggalGaji->translatedFormat('l, d M Y');

        return response()->json([
            'success' => true,
            'result' => [
                'id_gaji'           => $gaji->id_gaji,
                'periode_mulai'     => $periodeMulai,
                'periode_selesai'   => $periodeSelesai,
                'tanggal_dibayar'   => $tanggalDibayar,
                'total_gaji_pokok'  => $gajiBersihDasarUser, // Gaji Pokok Harian
                // 'total_gaji_pokok'  => $totalGajiMingguan, // Hasil * 5
                'jam_lembur'        => $jamLembur,
                'tarif_lembur'      => $tarifLemburPerJam,  // 10000
                'total_lembur'      => $totalUangLembur,   // jam * 10000
                'gaji_bersih_akhir' => $gajiBersihAkhir,    // Hasil penjumlahan total
                'keterangan'        => $gaji->keterangan
            ]
        ], 200);
    }

    public function indexWeb()
    {
        $gaji = Gaji::with('user')->get();
        

        return view('gaji.page', compact('gaji'));
    }

    public function generateGaji()
    {
        try {
            DB::beginTransaction();

            // 1. Tentukan rentang waktu (Senin - Jumat minggu ini)
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = Carbon::now()->startOfWeek(Carbon::FRIDAY);
            $now = Carbon::now();

            // 2. Ambil semua user yang memiliki data absen di rentang tersebut
            $users = User::whereHas('absensi', function($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('tanggal_datang', [$startOfWeek, $endOfWeek->endOfDay()]);
            })->get();

            if ($users->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data absensi untuk diproses minggu ini.');
            }

            foreach ($users as $user) {
                // Hitung Total Absen (Kehadiran Senin-Jumat)
                $totalHadir = DB::table('absensi')
                    ->where('id_user', $user->id_user)
                    ->whereBetween('tanggal_datang', [$startOfWeek, $endOfWeek->endOfDay()])
                    ->whereIn('status', ['absen_pulang', 'absen_datang']) // Pastikan status hadir
                    ->count();

                // Hitung Total Lembur (Sum dari kolom total_lembur di tabel absensi)
                $sumJamLembur = DB::table('absensi')
                    ->where('id_user', $user->id_user)
                    ->whereBetween('tanggal_datang', [$startOfWeek, $endOfWeek->endOfDay()])
                    ->sum('total_lembur');

                // Hitung Nominal
                $nominalAbsen = $totalHadir * ($user->gaji ?? 0);
                $nominalLembur = $sumJamLembur * 10000;
                $grandTotalGaji = $nominalAbsen + $nominalLembur;

                // Keterangan: Nama Bulan + Minggu Ke-
                $mingguKe = ceil($now->day / 7);
                $keterangan = "Gaji " . $now->translatedFormat('F');

                // 3. Simpan ke Tabel Gaji
                // Gunakan updateOrInsert agar tidak double input jika tombol diklik ulang di hari yang sama
                DB::table('gaji')->updateOrInsert(
                    [
                        'id_user' => $user->id_user,
                        'tanggal' => $now->format('Y-m-d'),
                    ],
                    [
                        'total_gaji'   => $grandTotalGaji,
                        'total_lembur' => $sumJamLembur,
                        'total_absen'  => $totalHadir,
                        'keterangan'   => $keterangan,
                        'status'       => 'minta_konfirmasi',
                        // 'created_at'   => now(),
                        // 'updated_at'   => now(),
                    ]
                );
            }

            DB::commit();
            return redirect()->route('gaji.page')->with('success', 'Berhasil menghitung gaji untuk ' . $users->count() . ' karyawan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal hitung gaji: ' . $e->getMessage());
        }
    }

    public function konfirmasiAdmin($id)
    {
        try {
            DB::beginTransaction(); // Memulai transaksi database

            // 1. Cari data gaji berdasarkan ID
            $gaji = Gaji::where('id_gaji', $id)->first();

            if (!$gaji) {
                return redirect()->back()->with('error', 'Data gaji tidak ditemukan.');
            }

            // Pastikan hanya yang berstatus 'minta_konfirmasi' yang diproses
            if ($gaji->status !== 'minta_konfirmasi') {
                return redirect()->back()->with('error', 'Status gaji ini sudah diproses sebelumnya.');
            }

            // 2. Update status pada tabel Gaji menjadi 'tunggu_konfirmasi'
            DB::table('gaji')
                ->where('id_gaji', $id)
                ->update(['status' => 'tunggu_konfirmasi']);

            // 3. Masukkan data ke tabel Kredit
            // Ambil nama user untuk keterangan tambahan jika diperlukan, 
            // atau gunakan format yang Anda minta (Pengeluaran Gaji)
            DB::table('kredit')->insert([
                'nama'              => 'Pengeluaran Gaji',
                'tanggal'           => now()->format('Y-m-d'),
                'id_gaji'           => $gaji->id_gaji, // Menyimpan referensi ID Gaji
                'jenis_pengeluaran' => 'tetap',
                'saldo_kredit'      => $gaji->total_gaji, // Mengambil total_gaji dari tabel gaji
                'status'            => 'tunggu',
                'keterangan'        => 'Otomatis dibuat dari konfirmasi gaji ID #' . $id,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            DB::commit(); // Simpan permanen perubahan jika semua berhasil
            return redirect()->route('gaji.page')->with('success', 'Berhasil mengonfirmasi gaji dan mencatat ke pengeluaran (kredit).');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika terjadi error
            return redirect()->back()->with('error', 'Gagal memproses data: ' . $e->getMessage());
        }
    }

}
