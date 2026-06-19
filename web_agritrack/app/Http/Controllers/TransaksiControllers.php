<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;

class TransaksiControllers extends Controller
{
    public function index()
    {
        $produks = Produk::all();
        $riwayat = Transaksi::with('details.produk')->orderBy('created_at', 'desc')->get();
        return view('transaksi.page', compact('produks', 'riwayat'));
    }

    public function store(Request $request)
    {
        // 1. Validasi input ditambahkan field baru
        $request->validate([
            'nama_pembeli'  => 'required|string|max:255',
            'lokasi'        => 'required|string|max:255',
            'no_tlp'        => 'required|string|max:20',
            'jenis_pesanan' => 'required|in:wa,datang_langsung,online_shop',
            'no_resi'       => 'nullable|string|max:100',
            'tanggal_pesan' => 'required|date',
            'items'         => 'required|array',
        ]);

        $filteredItems = collect($request->items)->filter(function ($item) {
            return !empty($item['id_produk']);
        });

        if ($filteredItems->isEmpty()) {
            return redirect()->back()->with('error', 'Pilih minimal satu produk.');
        }

        try {
            DB::beginTransaction();

            // 2. Simpan Transaksi Utama dengan field baru
            $transaksi = new Transaksi();
            $transaksi->kode_transaksi = 'TRX-' . strtoupper(uniqid());
            $transaksi->nama_pembeli = $request->nama_pembeli;
            $transaksi->lokasi = $request->lokasi;
            $transaksi->no_tlp = $request->no_tlp;
            $transaksi->jenis_pesanan = $request->jenis_pesanan;
            $transaksi->no_resi = $request->no_resi;
            $transaksi->tanggal_pesan = $request->tanggal_pesan;
            $transaksi->total_harga = 0; 
            $transaksi->save();

            $totalBayar = 0;

            // 3. Simpan Detail Transaksi & Update Stok
            foreach ($filteredItems as $item) {
                $produk = Produk::findOrFail($item['id_produk']);

                if ($produk->stok < $item['qty']) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi (Tersisa: {$produk->stok}).");
                }

                $subtotal = $produk->harga_satuan * $item['qty'];

                DetailTransaksi::create([
                    'transaksi_id'   => $transaksi->id_transaksi,
                    'produk_id'      => $produk->id_produk,
                    'jumlah'         => $item['qty'],
                    'harga_subtotal' => $subtotal
                ]);

                $produk->decrement('stok', $item['qty']);
                $totalBayar += $subtotal;
            }

            $transaksi->update(['total_harga' => $totalBayar]);

            // 4. Catat ke Tabel Debit
            DB::table('Debit')->insert([
                'id_penjualan'    => $transaksi->id_transaksi,
                'nama'            => 'Penjualan Produk',
                'saldo_debit'     => $totalBayar,
                'tanggal'         => now(),
                'keterangan'      => "Pemasukan dari {$request->nama_pembeli} ({$request->lokasi}) - Jenis: {$request->jenis_pesanan}" . ($request->jenis_pesanan === 'online_shop' ? " - No. Resi: {$request->no_resi}" : "") . " - Kode: {$transaksi->kode_transaksi}",
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', "Transaksi {$transaksi->kode_transaksi} berhasil disimpan!");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->delete();
            return redirect()->back()->with('success', 'Data transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }

    public function printPdf(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        $jenis = $request->input('jenis_pesanan', 'semua');

        $query = Transaksi::with('details.produk');

        // Filter berdasarkan bulan dan tahun
        $query->whereMonth('tanggal_pesan', $bulan)
            ->whereYear('tanggal_pesan', $tahun);

        if ($jenis !== 'semua') {
            $query->where('jenis_pesanan', $jenis);
        }

        $transaksi = $query->orderBy('tanggal_pesan', 'desc')->get();

        // DEBUGGING: Aktifkan baris di bawah ini untuk melihat apakah data ditemukan
        // dd($transaksi->toArray()); 

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('transaksi.pdf', compact('transaksi', 'bulan', 'tahun', 'jenis'));
        return $pdf->stream('Laporan_Transaksi.pdf');
    }
}