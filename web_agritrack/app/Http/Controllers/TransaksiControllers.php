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
        // Memuat relasi details dan produk agar tidak N+1 query
        $riwayat = Transaksi::with('details.produk')->orderBy('created_at', 'desc')->get();
        return view('transaksi.page', compact('produks', 'riwayat'));
    }

    public function store(Request $request)
    {
        // Validasi input identitas dan produk
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'lokasi'       => 'required|string|max:255',
            'no_tlp'       => 'required|string|max:20',
            'items'        => 'required|array',
        ]);

        $filteredItems = collect($request->items)->filter(function ($item) {
            return !empty($item['id_produk']);
        });

        if ($filteredItems->isEmpty()) {
            return redirect()->back()->with('error', 'Pilih minimal satu produk.');
        }

        try {
            DB::beginTransaction();

            // 1. Simpan Transaksi Utama
            $transaksi = new Transaksi();
            $transaksi->kode_transaksi = 'TRX-' . strtoupper(uniqid());
            $transaksi->nama_pembeli = $request->nama_pembeli;
            $transaksi->lokasi = $request->lokasi;
            $transaksi->no_tlp = $request->no_tlp;
            $transaksi->total_harga = 0; // Sementara 0, akan diupdate setelah loop
            $transaksi->save();

            $totalBayar = 0;

            // 2. Simpan Detail Transaksi & Update Stok
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

                // Kurangi stok produk
                $produk->decrement('stok', $item['qty']);
                $totalBayar += $subtotal;
            }

            // 3. Update Total Harga di Transaksi
            $transaksi->update(['total_harga' => $totalBayar]);

            // 4. Catat ke Tabel Debit (Laporan Keuangan)
            DB::table('Debit')->insert([
                'id_penjualan'    => $transaksi->id_transaksi,
                'nama'            => 'Penjualan Produk',
                'total_pemasukan' => $totalBayar,
                'saldo_debit'     => $totalBayar,
                'tanggal'         => now(),
                'keterangan'      => "Pemasukan dari {$request->nama_pembeli} ({$request->lokasi}) - Kode: {$transaksi->kode_transaksi}",
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
            // Tambahkan logika pengembalian stok jika diperlukan sebelum delete
            $transaksi->delete();
            return redirect()->back()->with('success', 'Data transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}