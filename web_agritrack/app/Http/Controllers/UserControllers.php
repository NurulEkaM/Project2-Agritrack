<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserStoreRequest;
class UserControllers extends Controller
{
    public function index()
    {
        $users = User::all();

        // Return Json Response
        return response()->json([
            'results' => $users
        ], 200);
    }

    public function store(UserStoreRequest $request)
    {
        try {
            // Create User dengan kolom sesuai gambar database
            User::create([
                'nama'     => $request->nama,
                'jabatan'  => $request->jabatan,
                'alamat'   => $request->alamat,
                'no_hp'    => $request->no_hp,
                'role'     => $request->role,
                'gaji'     => $request->gaji,
                'username' => $request->username,
                'password' => bcrypt($request->password) // Menggunakan bcrypt agar password terenkripsi aman
            ]);

            // Return Json Response
            return response()->json([
                'message' => "User successfully created."
            ], 200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!",
                'error'   => $e->getMessage() // Opsional: hapus baris ini di tahap produksi jika tidak ingin mengekspos error database
            ], 500);
        }
    }

    public function show($id)
    {
        // Mencari detail user berdasarkan primary key adat (id_user)
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User Not Found.'
            ], 404);
        }

        // Return Json Response
        return response()->json([
            'user' => $user
        ], 200);
    }

    public function update(Request $request, $id)
{
    try {
        // Cari user berdasarkan ID
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User ID ' . $id . ' tidak ditemukan di database.'
            ], 404);
        }

        // Update field
        // Gunakan $request->input() agar lebih aman
        $user->nama     = $request->nama;
        $user->jabatan  = $request->jabatan;
        $user->alamat   = $request->alamat;
        $user->no_hp    = $request->no_hp;
        $user->role     = $request->role;
        $user->gaji     = $request->gaji;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return response()->json([
            'message' => "User updated successfully."
        ], 200);

    } catch (\Exception $e) {
        // Ini akan memastikan jika ada error (seperti kolom database tidak cocok), 
        // yang dikirim adalah JSON, bukan halaman HTML error.
        return response()->json([
            'message' => "Terjadi kesalahan pada server.",
            'error' => $e->getMessage()
        ], 500);
    }
}   
    public function destroy($id)
    {
        // Detail
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User Not Found.'
            ], 404);
        }

        // Delete User
        $user->delete();

        // Return Json Response
        return response()->json([
            'message' => "User successfully deleted."
        ], 200);
    }
}