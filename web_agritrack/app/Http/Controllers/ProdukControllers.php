<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

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
        'nama_produk' => 'required|string',
        'harga_satuan' => 'required|numeric',
        'stok' => 'required|integer',
        'deskripsi' => 'nullable|string',
    ]);

    $produk = Produk::create($validated);

    return response()->json([
        'message' => 'Produk berhasil ditambahkan',
        'data' => $produk
    ], 201);
}

public function update(Request $request, $id)
{
    $produk = Produk::findOrFail($id);
    $produk->update($request->all());
    return response()->json(['message' => 'Produk berhasil diupdate']);
}

public function destroy($id)
{
    $produk = Produk::findOrFail($id);
    $produk->delete();
    return response()->json(['message' => 'Produk berhasil dihapus']);
}
}
