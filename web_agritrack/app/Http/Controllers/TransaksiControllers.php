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
        // 1. Filter items agar hanya memproses yang produk_id-nya diisi
        $filteredItems = collect($request->items)->filter(function ($item) {
            return !empty($item['id_produk']);
        });

        if ($filteredItems->isEmpty()) {
            return redirect()->back()->with('error', 'Pilih minimal satu produk.');
        }

        try {
            DB::beginTransaction();

            $transaksi = new Transaksi();
            $transaksi->kode_transaksi = 'TRX-' . strtoupper(uniqid());
            $transaksi->total_harga = 0;
            $transaksi->save();

            $totalBayar = 0;

            foreach ($filteredItems as $item) {
                $produk = Produk::findOrFail($item['id_produk']);

                if ($produk->stok < $item['qty']) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi.");
                }

                $subtotal = $produk->harga_satuan * $item['qty'];

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id_transaksi,
                    'produk_id'    => $produk->id_produk,
                    'jumlah'       => $item['qty'],
                    'harga_subtotal' => $subtotal
                ]);

                $produk->stok -= $item['qty'];
                $produk->save();

                $totalBayar += $subtotal;
            }

            $transaksi->update(['total_harga' => $totalBayar]);

             DB::table('Debit')->insert([
                'id_penjualan' => $transaksi->id_transaksi,
                'nama' => 'Penjualan Produk',
                'total_pemasukan' => $totalBayar,
                'saldo_debit' => $totalBayar,
                'tanggal' => now(),
                'keterangan' => 'Pemasukan dari transaksi ' . $transaksi->kode_transaksi,
                // 'status' => 'setuju',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        return redirect()->back()->with('success', 'Data transaksi berhasil dihapus.');
    }
}