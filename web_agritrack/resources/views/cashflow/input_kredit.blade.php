@extends('layouts.app')

@section('content')
<div class="mb-8 flex items-center">
    <a href="{{ route('admin.kredit') }}" class="flex items-center text-[#064E3B] font-bold text-lg hover:opacity-70 transition">
        <i class="fas fa-arrow-left mr-3"></i> Tambah Kredit
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-[#064E3B]">Detail Transaksi</h2>
                <p class="text-xs text-gray-400">Tambahkan informasi pengeluaran operasional kantor Anda di bawah ini.</p>
            </div>

            <form action="{{ route('kredit.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Nama Pengeluaran</label>
                        <input type="text" name="nama" placeholder="Contoh: Pembelian ATK" value="{{ old('nama') }}" 
                            class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none focus:ring-2 focus:ring-[#065F46]/20 transition" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Kategori</label>
                        <div class="relative">
                            <select name="jenis_pengeluaran" class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none appearance-none cursor-pointer" required>
                                <option value="tetap" {{ old('jenis_pengeluaran') == 'tetap' ? 'selected' : '' }}>Operasional Kantor (Tetap)</option>
                                <option value="tidak tetap" {{ old('jenis_pengeluaran') == 'tidak tetap' ? 'selected' : '' }}>Logistik (Tidak Tetap)</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 text-[10px]"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                            class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Nominal (IDR)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400 text-sm">Rp</span>
                            <input type="number" name="saldo_kredit" placeholder="0" value="{{ old('saldo_kredit') }}" 
                                class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl pl-12 pr-4 py-3 text-sm font-semibold text-gray-700 outline-none" required>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Deskripsi / Catatan</label>
                    <textarea name="keterangan" rows="4" placeholder="Masukkan deskripsi pengeluaran..."
                        class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none resize-none">{{ old('keterangan') }}</textarea>
                </div>

                <input type="hidden" name="status" value="tunggu">

                <div class="flex justify-end gap-3 pt-6 border-t border-gray-50">
                    <a href="{{ route('admin.kredit') }}" class="px-8 py-2.5 rounded-full border border-gray-200 text-gray-400 font-bold text-xs hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="px-8 py-2.5 rounded-full bg-[#065F46] text-white font-bold text-xs flex items-center hover:bg-[#054d39] transition">
                        <i class="fas fa-plus mr-2"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection