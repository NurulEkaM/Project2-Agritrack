<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class ProdukControllers extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        return response()->json($produk);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk'  => 'required|string',
            'harga_satuan' => 'required|numeric',
            'stok'         => 'required|integer',
            'deskripsi'    => 'nullable|string',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi gambar
        ]);

        if ($request->hasFile('gambar')) {
            // Simpan ke folder public/products
            $path = $request->file('gambar')->store('products', 'public');
            $validated['gambar'] = $path;
        }

        $produk = Produk::create($validated);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'data' => $produk
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $path = $request->file('gambar')->store('products', 'public');
            $data['gambar'] = $path;
        }

        $produk->update($data);
        return response()->json(['message' => 'Produk berhasil diupdate']);
    }

public function destroy($id)
{
    $produk = Produk::findOrFail($id);
    $produk->delete();
    return response()->json(['message' => 'Produk berhasil dihapus']);
}
}
