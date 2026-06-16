<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kiwari Farm</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[#ebf1ee] flex items-center justify-center p-4 md:p-6">

    <!-- Card Container -->
    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row min-h-[550px]">
        
        <!-- Sisi Kiri: Visual/Brand Identity -->
        <div class="w-full md:w-1/2 bg-[#4ca744] p-10 md:p-12 flex flex-col justify-between relative overflow-hidden text-white">
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-20 -right-10 w-60 h-60 bg-emerald-300/20 rounded-full blur-3xl"></div>

            <!-- Logo / Brand Name -->
            <div class="flex items-center gap-2 relative z-10">
                <div class="bg-white text-[#4ca744] p-2 rounded-lg shadow-md">
                    <i class="fas fa-seedling text-xl"></i>
                </div>
                <span class="font-extrabold text-lg tracking-wider uppercase">Kiwari Farm</span>
            </div>

            <!-- Pesan Sambutan -->
            <div class="my-auto pt-12 pb-6 relative z-10">
                <h1 class="text-3xl md:text-4xl font-extrabold mb-4 leading-tight">Selamat Datang Kembali!</h1>
                <p class="text-sm text-emerald-100/90 leading-relaxed max-w-sm">
                    Silahkan masuk untuk mengelola data sistem operasional, monitoring mitra tani, dan distribusi hasil panen.
                </p>
            </div>

            <!-- Footer Hak Cipta -->
            <div class="text-[11px] text-emerald-200/70 relative z-10">
                &copy; 2026 Kiwari Farm. All rights reserved.
            </div>
        </div>

        <!-- Sisi Kanan: Form Login -->
        <div class="w-full md:w-1/2 p-10 md:p-14 flex flex-col justify-center bg-white">
            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">LOGIN</h2>
                <p class="text-xs text-gray-400 mt-1">Masukkan kredensial akun Anda secara benar.</p>
            </div>
            
            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Notifikasi Error -->
                @if($errors->any())
                    <div class="bg-red-50 text-red-600 text-xs p-3.5 rounded-xl border border-red-100 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-sm flex-shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Input Username -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Username</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-gray-400">
                            <i class="fas fa-user text-sm"></i>
                        </span>
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username" required
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#4ca744] focus:ring-2 focus:ring-[#4ca744]/20 focus:bg-white outline-none text-sm transition-all duration-200 placeholder-gray-400 text-gray-700">
                    </div>
                </div>
                
                <!-- Input Password (Dengan Fitur Intip Password) -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Password</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-gray-400">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <!-- ID passwordInput ditambahkan disini -->
                        <input type="password" id="passwordInput" name="password" placeholder="••••••••" required
                            class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#4ca744] focus:ring-2 focus:ring-[#4ca744]/20 focus:bg-white outline-none text-sm transition-all duration-200 placeholder-gray-400 text-gray-700">
                        
                        <!-- Tombol Mata (Toggle Password) -->
                        <button type="button" id="togglePassword" class="absolute right-4 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                            <i class="fas fa-eye text-sm" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Opsi Tambahan (Remember Me) -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#4ca744] focus:ring-[#4ca744]/30 border-gray-300 accent-[#4ca744]">
                        <span class="text-xs font-medium text-gray-500">Ingat saya</span>
                    </label>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-4">
                    <button type="submit" 
                        class="w-full bg-[#fbc565] hover:bg-[#f9b233] active:scale-[0.98] text-gray-900 font-extrabold py-3.5 px-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs tracking-widest uppercase flex items-center justify-center gap-2">
                        <span>Masuk Sistem</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Script JavaScript untuk Toggle Show/Hide Password -->
    <script>
        const passwordInput = document.getElementById('passwordInput');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            // Cek tipe input saat ini
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Ubah ikon mata (fa-eye <-> fa-eye-slash)
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>