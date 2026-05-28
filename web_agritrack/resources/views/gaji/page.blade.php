@extends('layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-3xl font-black text-[#064E3B] flex items-center gap-3">
            <i class="fas fa-chart-pie text-green-600"></i> Finance Overview
        </h1>
        <p class="text-gray-400 text-sm font-medium ml-9">Analisis mendalam biaya operasional sumber daya manusia.</p>
    </div>
    
    {{-- Tombol hanya muncul jika hari ini adalah jumat (Friday) --}}
    @if(now()->isFriday())
        <form action="{{ route('gaji.generate') }}" method="POST" onsubmit="return confirm('Hitung gaji otomatis untuk semua karyawan minggu ini?')">
            @csrf
            <button type="submit" class="bg-[#064E3B] hover:bg-green-800 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 shadow-lg transition-all active:scale-95">
                <i class="fas fa-calculator"></i>
                Hitung Gaji Otomatis
            </button>
        </form>
    @else
        <div class="bg-gray-100 px-6 py-3 rounded-2xl border border-gray-200">
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">
                <i class="fas fa-lock mr-1"></i> Tombol Aktif Setiap Jumat
            </p>
        </div>
    @endif
</div>

{{-- Alert Success/Error --}}
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
        {{ session('error') }}
    </div>
@endif

{{-- Bento Grid Layout (Tetap Sama Seperti Sebelumnya) --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10">
    {{-- ... Konten Box Bento Anda ... --}}
</div>

{{-- Tabel Transaksi --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    {{-- ... Header Tabel ... --}}

    <div class="overflow-x-auto">
        <table class="w-full text-left" id="gajiTable">
            <thead>
                <tr class="bg-[#F1F5F9] text-[#64748B] text-[10px] uppercase font-extrabold tracking-widest">
                    <th class="px-6 py-4 text-center">No</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Karyawan</th>
                    <th class="px-6 py-4">Keterangan</th>
                    <th class="px-6 py-4 text-center">Hadir</th> {{-- Tambahan Kolom --}}
                    <th class="px-6 py-4 text-right">Lembur (x10k)</th>
                    <th class="px-6 py-4 text-right">Total Gaji</th>
                    <th class="px-6 py-4 text-center">Update Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($gaji as $item)
                <tr class="hover:bg-gray-50/50 transition row-item">
                    <td class="px-6 py-5 text-center text-gray-400 font-medium">{{ $loop->iteration }}</td>
                    <td class="px-6 py-5 text-gray-600 font-semibold">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="px-6 py-5">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-800">{{ $item->user->nama ?? 'User Tidak Ditemukan' }}</span>
                            <span class="text-[10px] text-gray-400">ID: {{ $item->id_user }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-5 text-gray-500 italic">{{ $item->keterangan }}</td>
                    
                    {{-- Kolom Hadir --}}
                    <td class="px-6 py-5 text-center font-bold text-gray-700">
                        {{ $item->total_absen }} <span class="text-[10px] text-gray-400 font-normal">Hari</span>
                    </td>

                    <td class="px-6 py-5 text-right font-medium text-blue-600">
                        <span class="text-[10px] opacity-50 mr-1">Rp</span>{{ number_format($item->total_lembur * 10000, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-5 text-right font-black text-[#065F46]">
                        <span class="text-[10px] text-gray-400 mr-1">Rp</span>{{ number_format($item->total_gaji, 0, ',', '.') }}
                    </td>

                       <td class="px-6 py-5 text-center">
                        @if($item->status == 'minta_konfirmasi')
                            <button type="button" 
                                    onclick="event.preventDefault(); submitKonfirmasi('{{ $item->id_gaji }}')"
                                    class="bg-blue-600 text-white hover:bg-blue-700 border border-blue-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-tighter transition-all flex items-center justify-center gap-2 mx-auto shadow-md shadow-blue-200 active:scale-95 group">
                                <i class="fas fa-info-circle text-white group-hover:scale-110 transition-transform"></i>
                                <span>Minta Konfirmasi</span>
                                <i class="fas fa-chevron-right text-[8px] text-white/50 group-hover:translate-x-1 transition-transform"></i>
                            </button>

                            {{-- Form Tersembunyi untuk setiap baris --}}
                            <form id="form-konfirmasi-{{ $item->id_gaji }}" action="{{ route('gaji.konfirmasi', $item->id_gaji) }}" method="POST" style="display: none;">
                                @csrf
                                @method('PUT')
                            </form>
                        @else
                            @if($item->status == 'tunggu_konfirmasi')
                                <span class="text-yellow-600 font-bold text-[10px] uppercase tracking-wide">Tunggu Konfirmasi</span>    
                            @elseif($item->status == 'setuju')
                                <span class="text-green-600 font-bold text-[10px] uppercase tracking-wide">Terkonfirmasi</span>
                            @elseif($item->status == 'tidak_disetujui')
                                <span class="text-red-600 font-bold text-[10px] uppercase tracking-wide">Ditolak</span>
                            @else
                                <span class="text-gray-400 font-bold text-[10px] uppercase tracking-wide">Status Tidak Dikenal</span>
                            @endif
                        @endif
                    </td>

                </tr>
                @empty
                {{-- Empty State --}}
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

<script>
    function submitKonfirmasi(id) {
        if(confirm('Kirim permintaan konfirmasi untuk Gaji ID #' + id + '? \nStatus akan berubah menjadi "Tunggu Konfirmasi".')) {
            document.getElementById('form-konfirmasi-' + id).submit();
        }
    }
</script>