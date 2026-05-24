@extends('layouts.app')

@section('content')
<div class="mb-8 flex items-center">
    <a href="{{ route('admin.kredit') }}" class="flex items-center text-[#064E3B] font-bold text-lg hover:opacity-70 transition">
        <i class="fas fa-arrow-left mr-3"></i> Edit Pengeluaran
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-[#064E3B]">Detail Transaksi</h2>
                <p class="text-xs text-gray-400">Perbarui informasi pengeluaran operasional kantor Anda di bawah ini.</p>
            </div>

            <form action="{{ route('admin.kredit.update', $kredit->id_kredit) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Nama Pengeluaran</label>
                        <input type="text" name="nama" value="{{ old('nama', $kredit->nama) }}" 
                            class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none focus:ring-2 focus:ring-[#065F46]/20 transition" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Kategori</label>
                        <div class="relative">
                            <select name="jenis_pengeluaran" class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none appearance-none cursor-pointer" required>
                                <option value="tetap" {{ $kredit->jenis_pengeluaran == 'tetap' ? 'selected' : '' }}>Operasional Kantor (Tetap)</option>
                                <option value="tidak tetap" {{ $kredit->jenis_pengeluaran == 'tidak tetap' ? 'selected' : '' }}>Logistik (Tidak Tetap)</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 text-[10px]"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $kredit->tanggal) }}" 
                            class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Nominal (IDR)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400 text-sm">Rp</span>
                            <input type="number" name="saldo_kredit" value="{{ old('saldo_kredit', $kredit->saldo_kredit) }}" 
                                class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl pl-12 pr-4 py-3 text-sm font-semibold text-gray-700 outline-none" required>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Deskripsi / Catatan</label>
                    <textarea name="keterangan" rows="4" 
                        class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none resize-none">{{ old('keterangan', $kredit->keterangan) }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-gray-50">
                    <a href="{{ route('admin.kredit') }}" class="px-8 py-2.5 rounded-full border border-gray-200 text-gray-400 font-bold text-xs hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="px-8 py-2.5 rounded-full bg-[#065F46] text-white font-bold text-xs flex items-center hover:bg-[#054d39] transition">
                         Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-[#064E3B] rounded-[2rem] p-8 text-white shadow-xl shadow-green-900/20 relative overflow-hidden h-[400px] flex flex-col justify-between">
            <i class="fas fa-file-invoice absolute -right-4 -top-4 text-8xl opacity-10 rotate-12"></i>
            
            <div>
                <p class="text-[10px] font-bold opacity-60 uppercase tracking-widest mb-4">Preview Ringkasan</p>
                <h3 class="text-2xl font-bold leading-tight mb-2">{{ $kredit->nama }}</h3>
                <p class="text-[10px] opacity-60 uppercase">Total Pengeluaran</p>
                <h4 class="text-3xl font-black mt-2">Rp {{ number_format($kredit->saldo_kredit, 0, ',', '.') }}</h4>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-end border-t border-white/10 pt-4">
                    <div>
                        <p class="text-[8px] opacity-50 uppercase font-bold tracking-tighter">Kategori</p>
                        <p class="text-xs font-bold">{{ ucfirst($kredit->jenis_pengeluaran) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[8px] opacity-50 uppercase font-bold tracking-tighter">Tanggal</p>
                        <p class="text-xs font-bold">{{ \Carbon\Carbon::parse($kredit->tanggal)->format('d M Y') }}</p>
                    </div>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-black uppercase px-3 py-1 rounded-full bg-white/10 border border-white/20">
                        {{ $kredit->status }}
                    </span>
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection