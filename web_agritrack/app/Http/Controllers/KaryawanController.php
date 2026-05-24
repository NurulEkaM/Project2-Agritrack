<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
   public function index()
{
    try {
        $karyawan = User::all();
        return response()->json([
            'results' => $karyawan
        ], 200);
    } catch (\Exception $e) {
        // Jika error, kirim pesan JSON, bukan HTML error
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}
public function show($id)
{
    try {
        $karyawan = User::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }
        return response()->json($karyawan, 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}