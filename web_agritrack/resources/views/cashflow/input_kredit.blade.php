@extends('layouts.app')

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('admin.kredit') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-semibold p-2.5 rounded-xl text-sm flex items-center justify-center shadow-sm transition border border-gray-200 group" title="Kembali">
        <i class="fas fa-arrow-left text-gray-500 group-hover:text-gray-700"></i> 
    </a>
    <div>
        <h1 class="text-2xl font-bold text-[#064E3B] tracking-tight">Tambah Kredit</h1>
        <p class="text-xs text-gray-400">Catat informasi pengeluaran operasional kantor atau logistik baru Anda di bawah ini.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    {{-- Form Utama --}}
    <div class="lg:col-span-2 bg-white rounded-3xl shadow-md border border-gray-100 overflow-hidden transition-all duration-200 hover:shadow-lg">
        <div class="p-8">
            <div class="mb-8 border-b border-gray-50 pb-5">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-file-invoice-dollar text-[#065F46]"></i> Detail Transaksi Pengeluaran
                </h2>
                <p class="text-xs text-gray-400 mt-1">Pastikan informasi nominal pengeluaran dan kategori telah diinput dengan benar.</p>
            </div>

            {{-- Menampilkan Error Validasi jika ada --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-700 text-sm font-medium flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5 text-base flex-shrink-0"></i>
                    <div>
                        <span class="font-bold block mb-1">Periksa kembali bawaan input data:</span>
                        <ul class="list-disc list-inside space-y-0.5 text-xs text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('kredit.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Pengeluaran --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-extrabold uppercase text-gray-400 tracking-widest block">Nama Pengeluaran</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400">
                                <i class="fas fa-tag text-xs"></i>
                            </span>
                            <input type="text" name="nama" placeholder="Contoh: Pembelian Pupuk" value="{{ old('nama') }}" 
                                class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm font-semibold text-gray-700 outline-none focus:border-[#065F46] focus:ring-4 focus:ring-[#065F46]/10 shadow-sm transition" required>
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-extrabold uppercase text-gray-400 tracking-widest block">Kategori</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400">
                                <i class="fas fa-layer-group text-xs"></i>
                            </span>
                            <select name="jenis_pengeluaran" class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-10 py-3 text-sm font-semibold text-gray-700 outline-none focus:border-[#065F46] focus:ring-4 focus:ring-[#065F46]/10 shadow-sm transition appearance-none cursor-pointer" required>
                                <option value="tetap" {{ old('jenis_pengeluaran') == 'tetap' ? 'selected' : '' }}>Operasional Kantor (Tetap)</option>
                                <option value="tidak tetap" {{ old('jenis_pengeluaran') == 'tidak tetap' ? 'selected' : '' }}>Logistik (Tidak Tetap)</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 text-[10px] pointer-events-none"></i>
                        </div>
                    </div>

                    {{-- Tanggal Transaksi --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-extrabold uppercase text-gray-400 tracking-widest block">Tanggal Transaksi</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400">
                                <i class="fas fa-calendar-alt text-xs"></i>
                            </span>
                            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                                class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm font-semibold text-gray-700 outline-none focus:border-[#065F46] focus:ring-4 focus:ring-[#065F46]/10 shadow-sm transition" required>
                        </div>
                    </div>

                    {{-- Nominal Kredit --}}
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-extrabold uppercase text-gray-400 tracking-widest block">Nominal Kredit (IDR)</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-3 text-sm font-bold text-[#065F46] bg-green-50 px-2 py-0.5 rounded border border-green-100">Rp</span>
                            <input type="number" name="saldo_kredit" placeholder="0" value="{{ old('saldo_kredit') }}" 
                                class="w-full bg-white border border-gray-200 rounded-xl pl-16 pr-4 py-3 text-base font-bold text-gray-800 outline-none focus:border-[#065F46] focus:ring-4 focus:ring-[#065F46]/10 shadow-sm transition placeholder-gray-300" required>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-extrabold uppercase text-gray-400 tracking-widest block">Deskripsi / Catatan</label>
                    <textarea name="keterangan" rows="4" placeholder="Masukkan deskripsi detail pengeluaran di sini..."
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none resize-none focus:border-[#065F46] focus:ring-4 focus:ring-[#065F46]/10 shadow-sm transition placeholder-gray-300">{{ old('keterangan') }}</textarea>
                </div>

                {{-- Status Hidden --}}
                <input type="hidden" name="status" value="tunggu">

                {{-- Tombol Aksi --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.kredit') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-500 font-bold text-xs hover:bg-gray-50 shadow-sm transition">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#065F46] text-white font-bold text-xs flex items-center hover:bg-[#054d39] transition shadow-md shadow-green-900/10">
                        <i class="fas fa-save mr-2"></i> Simpan Data Kredit
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Sidebar Info & Aturan (Sisi Kanan) --}}
    <div class="space-y-6">
        <div class="bg-gradient-to-br from-[#065F46] to-[#044331] text-white p-6 rounded-3xl shadow-md border border-emerald-800">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-white/10 p-2 rounded-xl text-emerald-300">
                    <i class="fas fa-info-circle text-lg"></i>
                </div>
                <h3 class="font-bold text-sm tracking-wide">Pemberitahuan Sistem</h3>
            </div>
            <p class="text-xs text-emerald-100 leading-relaxed">
                Setiap data pengeluaran (kredit) yang diajukan akan masuk ke sistem dengan status peninjauan (tunggu) terlebih dahulu sebelum memotong ringkasan kas utama.
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-xs uppercase tracking-wider text-gray-400 mb-4">Aturan Pengisian</h3>
            <ul class="space-y-3 text-xs font-semibold text-gray-600">
                <li class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-500"></i> Nama pengeluaran harus jelas & spesifik
                </li>
                <li class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-500"></i> Pilih kategori yang sesuai (Tetap/Tidak Tetap)
                </li>
                <li class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-500"></i> Nominal kredit berupa angka bulat positif
                </li>
                <li class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-500"></i> Tanggal otomatis terisi hari ini
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection