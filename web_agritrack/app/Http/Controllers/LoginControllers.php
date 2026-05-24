<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginControllers extends Controller
{
    public function login(Request $request) 
    {
        $username = $request->username;
        $password = $request->password; // Password teks biasa dari Mobile
        
        if (RateLimiter::tooManyAttempts('login-attempt:'.$username, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak percobaan. Coba lagi dalam 60 detik.'
            ], 429);
        }

        $user = User::where('username', $username)->first();

        // --- UBAH BAGIAN INI ---
        // Hash::check membandingkan teks biasa ($password) dengan hash di DB ($user->password)
        if ($user && Hash::check($password, $user->password)) {
            RateLimiter::clear('login-attempt:'.$username);
            
            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil!',
                'user'    => [
                    'id_user' => $user->id_user,
                    'nama'    => $user->nama,
                    'role'    => $user->role,
                    'gaji'    => $user->gaji,
                    'jabatan' => $user->jabatan,
                    'alamat'  => $user->alamat,
                    'no_hp'   => $user->no_hp,
                    'username' => $user->username,
                    'password' => $user->password, // Opsional: Hati-hati mengekspos hash password di response, pastikan ini aman untuk kebutuhan Anda
                ]
            ], 200);
        }
        // -----------------------

        RateLimiter::hit('login-attempt:'.$username);
        return response()->json(['success' => false, 'message' => 'Kredensial salah'], 401);
    }

    // public function logout(Request $request) 
    // {
    //     // Implementasi logout jika diperlukan, misalnya dengan menghapus token atau sesi
    //     return response()->json(['success' => true, 'message' => 'Logout Berhasil!'], 200);
    // }

    public function loginWeb(Request $request) 
    {
        // 1. Validasi Input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        // 2. Coba Login menggunakan Guard Web Laravel
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 3. Cek Role (Contoh: Admin atau Owner)
            $user = Auth::user();
            
            if ($user->role === 'admin' || $user->role === 'owner') {
                return redirect()->intended('dashboard'); // Arahkan ke dashboard
            }

            // Jika role tidak diizinkan akses web
            Auth::logout();
            return back()->withErrors(['username' => 'Anda tidak memiliki akses administrator.']);
        }

        // 4. Jika Gagal
        return back()->withErrors([
            'username' => 'Kredensial yang Anda masukkan tidak cocok dengan data kami.',
        ])->withInput($request->only('username'));
    }

    public function logout(Request $request) 
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
