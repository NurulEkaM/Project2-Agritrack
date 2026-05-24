@extends('layouts.app')

@section('content')
<div class="mb-8 flex items-center">
    <a href="{{ route('absensi.page') }}" class="flex items-center text-[#064E3B] font-bold text-lg hover:opacity-70 transition">
        <i class="fas fa-arrow-left mr-3"></i> Edit Absensi Pegawai
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    {{-- SISI KIRI: FORM EDIT DATA --}}
    <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-[#064E3B]">Detail Absensi</h2>
                <p class="text-xs text-gray-400">Perbarui informasi lokasi kerja atau status kehadiran pegawai di bawah ini.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-xl text-red-600 text-sm font-semibold">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('absensi.update', $absensi->id_absensi) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Input Pilihan Lokasi --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Lokasi Kerja</label>
                        <div class="relative">
                            <select name="lokasi" class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none appearance-none cursor-pointer focus:ring-2 focus:ring-[#065F46]/20 transition" required>
                                <option value="kebun_lanud" {{ old('lokasi', $absensi->lokasi) == 'kebun_lanud' ? 'selected' : '' }}>Kebun Lanud (LANUD)</option>
                                <option value="kebun_sadang" {{ old('lokasi', $absensi->lokasi) == 'kebun_sadang' ? 'selected' : '' }}>Kebun Sadang (SADANG)</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 text-[10px]"></i>
                        </div>
                    </div>

                    {{-- Input Pilihan Status --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Status Kehadiran</label>
                        <div class="relative">
                            <select name="status" class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 outline-none appearance-none cursor-pointer focus:ring-2 focus:ring-[#065F46]/20 transition" required>
                                <option value="absen_datang" {{ old('status', $absensi->status) == 'absen_datang' ? 'selected' : '' }}>Proses (Absen Datang)</option>
                                <option value="absen_pulang" {{ old('status', $absensi->status) == 'absen_pulang' ? 'selected' : '' }}>Hadir (Absen Pulang)</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 text-[10px]"></i>
                        </div>
                    </div>
                </div>

                {{-- Informasi Catatan / Kegiatan (Read-Only agar tetap sesuai UI asli) --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Deskripsi / Logbook Kegiatan</label>
                    <textarea rows="4" class="w-full bg-[#F8FAFC] border border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-400 outline-none resize-none" readonly>{{ $absensi->kegiatan ?? 'Tidak ada catatan kegiatan.' }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-gray-50">
                    <a href="/absensi/page" class="px-8 py-2.5 rounded-full border border-gray-200 text-gray-400 font-bold text-xs hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="px-8 py-2.5 rounded-full bg-[#065F46] text-white font-bold text-xs flex items-center hover:bg-[#054d39] transition">
                         Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SISI KANAN: CARD PREVIEW HIJAU (Sama seperti UI Pengeluaran) --}}
    <div class="lg:col-span-1">
        <div class="bg-[#064E3B] rounded-[2rem] p-8 text-white shadow-xl shadow-green-900/20 relative overflow-hidden h-[400px] flex flex-col justify-between">
            <i class="fas fa-user-check absolute -right-4 -top-4 text-8xl opacity-10 rotate-12"></i>
            
            <div>
                <p class="text-[10px] font-bold opacity-60 uppercase tracking-widest mb-4">Preview Ringkasan</p>
                <h3 class="text-2xl font-bold leading-tight mb-2">Pegawai Kiwari Farm</h3>
                <p class="text-[10px] opacity-60 uppercase">ID USER / DATA</p>
                <h4 class="text-3xl font-black mt-2">#{{ $absensi->id_user }} <span class="text-sm font-normal opacity-60">/ Absen #{{ $absensi->id_absensi }}</span></h4>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-end border-t border-white/10 pt-4">
                    <div>
                        <p class="text-[8px] opacity-50 uppercase font-bold tracking-tighter">Lokasi Saat Ini</p>
                        <p class="text-xs font-bold">
                            {{ $absensi->lokasi == 'kebun_lanud' ? 'LANUD' : 'SADANG' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-[8px] opacity-50 uppercase font-bold tracking-tighter">Waktu Datang</p>
                        <p class="text-xs font-bold">{{ \Carbon\Carbon::parse($absensi->tanggal_datang)->format('d M Y H:i') }} WIB</p>
                    </div>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-black uppercase px-3 py-1 rounded-full bg-white/10 border border-white/20 tracking-wider">
                        {{ $absensi->status == 'absen_pulang' ? 'Hadir (Selesai)' : 'Proses' }}
                    </span>
                    <i class="fas {{ $absensi->status == 'absen_pulang' ? 'fa-check-circle text-green-400' : 'fa-clock text-orange-400' }} text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection