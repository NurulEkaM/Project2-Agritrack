@extends('layouts.app')

@section('content')
<div class="mb-8 flex items-center">
    {{-- Tombol Kembali sesuai route di Controller --}}
    <a href="{{ route('admin.debit') }}" class="flex items-center text-[#064E3B] font-bold text-lg hover:opacity-70 transition">
        <i class="fas fa-arrow-left mr-3"></i> Tambah Debit
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-[#064E3B]">Detail Transaksi Pemasukan</h2>
                <p class="text-xs text-gray-400">Tambahkan informasi pemasukan dana atau debit kas di bawah ini.</p>
            </div>

            {{-- Menampilkan Error Validasi jika ada --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-xl text-red-600 text-sm font-semibold">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('debit.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Pemasukan --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Nama Pemasukan</label>
                        <input type="text" name="nama" placeholder="Contoh: Penjualan Hasil Panen" value="{{ old('nama') }}" 
                            class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none focus:ring-2 focus:ring-[#065F46]/20 transition" required>
                    </div>

                    {{-- Tanggal Transaksi --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                            class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none focus:ring-2 focus:ring-[#065F46]/20 transition" required>
                    </div>

                    {{-- Total Pemasukan (Sesuai validasi di controller) --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Total Pemasukan (IDR)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400 text-sm">Rp</span>
                            <input type="number" name="total_pemasukan" placeholder="0" value="{{ old('total_pemasukan') }}" 
                                class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl pl-12 pr-4 py-3 text-sm font-semibold text-gray-700 outline-none focus:ring-2 focus:ring-[#065F46]/20 transition" required>
                        </div>
                    </div>

                    {{-- Saldo Debit (Sesuai validasi di controller) --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Saldo Debit (IDR)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400 text-sm">Rp</span>
                            <input type="number" name="saldo_debit" placeholder="0" value="{{ old('saldo_debit') }}" 
                                class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl pl-12 pr-4 py-3 text-sm font-semibold text-gray-700 outline-none focus:ring-2 focus:ring-[#065F46]/20 transition" required>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Deskripsi / Catatan</label>
                    <textarea name="keterangan" rows="4" placeholder="Masukkan deskripsi atau sumber pemasukan..."
                        class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none resize-none focus:ring-2 focus:ring-[#065F46]/20 transition">{{ old('keterangan') }}</textarea>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-50">
                    <a href="{{ route('admin.debit') }}" class="px-8 py-2.5 rounded-full border border-gray-200 text-gray-400 font-bold text-xs hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="px-8 py-2.5 rounded-full bg-[#065F46] text-white font-bold text-xs flex items-center hover:bg-[#054d39] transition shadow-lg shadow-green-900/20">
                        <i class="fas fa-save mr-2"></i> Simpan Data Debit
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection