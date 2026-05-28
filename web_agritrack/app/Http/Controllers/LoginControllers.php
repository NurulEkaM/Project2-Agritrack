<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginControllers extends Controller
{
    /**
     * API Login untuk Mobile (React Native)
     */
    public function login(Request $request) 
    {
        $username = $request->username;
        $password = $request->password; 
        
        // Proteksi Brute Force
        if (RateLimiter::tooManyAttempts('login-attempt:'.$username, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak percobaan. Coba lagi dalam 60 detik.'
            ], 429);
        }

        $user = User::where('username', $username)->first();

        // Cek user dan verifikasi password hash
        if ($user && Hash::check($password, $user->password)) {
            RateLimiter::clear('login-attempt:'.$username);
            
            // --- BAGIAN PENTING: Membuat Token untuk Mobile ---
            // Token inilah yang akan digunakan React Native untuk akses PDF
            $token = $user->createToken('mobile-token')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil!',
                'token'   => $token, // Token dikirim ke aplikasi
                'user'    => [
                    'id_user'  => $user->id_user,
                    'nama'     => $user->nama,
                    'role'     => $user->role,
                    'gaji'     => $user->gaji,
                    'jabatan'  => $user->jabatan,
                    'alamat'   => $user->alamat,
                    'no_hp'    => $user->no_hp,
                    'username' => $user->username,
                ]
            ], 200);
        }

        RateLimiter::hit('login-attempt:'.$username);
        return response()->json(['success' => false, 'message' => 'Kredensial salah'], 401);
    }

    /**
     * Login untuk Website (Blade)
     */
    public function loginWeb(Request $request) 
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            if ($user->role === 'admin' || $user->role === 'owner') {
                return redirect()->intended('dashboard'); 
            }

            Auth::logout();
            return back()->withErrors(['username' => 'Anda tidak memiliki akses administrator.']);
        }

        return back()->withErrors([
            'username' => 'Kredensial yang Anda masukkan tidak cocok dengan data kami.',
        ])->withInput($request->only('username'));
    }

    /**
     * Logout untuk Website
     */
    public function logout(Request $request) 
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}