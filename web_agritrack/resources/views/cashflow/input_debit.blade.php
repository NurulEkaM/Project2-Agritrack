@extends('layouts.app')

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('admin.debit') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-semibold p-2.5 rounded-xl text-sm flex items-center justify-center shadow-sm transition border border-gray-200 group" title="Kembali">
        <i class="fas fa-arrow-left text-gray-500 group-hover:text-gray-700"></i> 
    </a>
    <div>
        <h1 class="text-2xl font-bold text-[#064E3B] tracking-tight">Tambah Debit</h1>
        <p class="text-xs text-gray-400">Catat transaksi masuk atau manajemen piutang operasional baru.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    <div class="lg:col-span-2 bg-white rounded-3xl shadow-md border border-gray-100 overflow-hidden transition-all duration-200 hover:shadow-lg">
        <div class="p-8">
            <div class="mb-8 border-b border-gray-50 pb-5">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-file-invoice-dollar text-[#065F46]"></i> Detail Transaksi Pemasukan
                </h2>
                <p class="text-xs text-gray-400 mt-1">Pastikan informasi nominal saldo dan tanggal telah diinput dengan benar.</p>
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

            <form action="{{ route('debit.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Pemasukan --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-extrabold uppercase text-gray-400 tracking-widest block">Nama Pemasukan</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400">
                                <i class="fas fa-tag text-xs"></i>
                            </span>
                            <input type="text" name="nama" placeholder="Contoh: Penjualan Hasil Panen" value="{{ old('nama') }}" 
                                class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm font-semibold text-gray-700 outline-none focus:border-[#065F46] focus:ring-4 focus:ring-[#065F46]/10 shadow-sm transition" required>
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

                    {{-- Saldo Debit --}}
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-extrabold uppercase text-gray-400 tracking-widest block">Saldo Debit (IDR)</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-3 text-sm font-bold text-[#065F46] bg-green-50 px-2 py-0.5 rounded border border-green-100">Rp</span>
                            <input type="number" name="saldo_debit" placeholder="0" value="{{ old('saldo_debit') }}" 
                                class="w-full bg-white border border-gray-200 rounded-xl pl-16 pr-4 py-3 text-base font-bold text-gray-800 outline-none focus:border-[#065F46] focus:ring-4 focus:ring-[#065F46]/10 shadow-sm transition placeholder-gray-300" required>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-extrabold uppercase text-gray-400 tracking-widest block">Deskripsi / Catatan</label>
                    <textarea name="keterangan" rows="4" placeholder="Masukkan deskripsi detail atau info sumber pemasukan di sini..."
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none resize-none focus:border-[#065F46] focus:ring-4 focus:ring-[#065F46]/10 shadow-sm transition placeholder-gray-300">{{ old('keterangan') }}</textarea>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.debit') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-500 font-bold text-xs hover:bg-gray-50 shadow-sm transition">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#065F46] text-white font-bold text-xs flex items-center hover:bg-[#054d39] transition shadow-md shadow-green-900/10">
                        <i class="fas fa-save mr-2"></i> Simpan Data Debit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-gradient-to-br from-[#065F46] to-[#044331] text-white p-6 rounded-3xl shadow-md border border-emerald-800">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-white/10 p-2 rounded-xl text-emerald-300">
                    <i class="fas fa-info-circle text-lg"></i>
                </div>
                <h3 class="font-bold text-sm tracking-wide">Pemberitahuan Sistem</h3>
            </div>
            <p class="text-xs text-emerald-100 leading-relaxed">
                Setiap data debit yang disimpan akan langsung tercatat dan memperbarui grafik ringkasan keuangan utama pada halaman sistem manajemen kas operasional secara otomatis.
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-xs uppercase tracking-wider text-gray-400 mb-4">Aturan Pengisian</h3>
            <ul class="space-y-3 text-xs font-semibold text-gray-600">
                <li class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-500"></i> Nama pemasukan harus jelas
                </li>
                <li class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-500"></i> Nominal saldo berupa angka bulat
                </li>
                <li class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-500"></i> Tanggal otomatis terisi hari ini
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection