@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

{{-- Header --}}
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-black text-[#064E3B] flex items-center gap-3">
            <i class="fas fa-chart-pie text-green-600"></i> Finance Overview
        </h1>
        <p class="text-gray-400 text-sm font-medium ml-9">Analisis mendalam biaya operasional sumber daya manusia.</p>
    </div>
    
    @if(now()->isFriday())
        <form action="{{ route('gaji.generate') }}" method="POST" onsubmit="return confirm('Hitung gaji otomatis untuk semua karyawan minggu ini?')">
            @csrf
            <button type="submit" class="bg-[#064E3B] hover:bg-green-800 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 shadow-lg transition-all active:scale-95">
                <i class="fas fa-calculator"></i> Hitung Gaji Otomatis
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

{{-- Alerts --}}
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">{{ session('error') }}</div>
@endif

{{-- Main Card --}}
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    {{-- Toolbar --}}
    <div class="p-6 flex flex-col md:flex-row items-center justify-between gap-4 bg-slate-50/50 border-b border-slate-100">
        <div class="flex items-center gap-3">
            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Tampilkan:</span>
            <select id="dataLengthSelect" class="bg-white border border-slate-200 text-slate-600 text-xs font-bold px-4 py-2.5 rounded-xl outline-none cursor-pointer">
                <option value="10">10 Data</option>
                <option value="25">25 Data</option>
                <option value="50">50 Data</option>
                <option value="-1">Semua</option>
            </select>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="document.getElementById('modalPdf').classList.remove('hidden')" 
                class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase transition-all shadow-sm flex items-center">
                <i class="fas fa-file-pdf mr-2"></i> Cetak PDF
            </button>
            <div class="relative w-full md:w-72">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="gajiSearch" placeholder="Cari data..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold outline-none">
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="p-6 overflow-x-auto">
        <table class="w-full text-left" id="gajiTable">
            <thead>
                <tr class="text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100 bg-slate-50">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Karyawan</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Hadir</th>
                    <th class="px-6 py-4">Gaji Pokok</th>
                    <th class="px-6 py-4">Lembur</th>
                    <th class="px-6 py-4">Total Gaji</th>
                    <th class="px-6 py-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm">
                @foreach($gaji as $item)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-5 text-slate-400 font-bold">{{ $loop->iteration }}</td>
                    <td class="px-6 py-5">
                        <div class="font-bold text-slate-800">{{ $item->user->nama ?? 'N/A' }}</div>
                        <div class="text-[10px] text-slate-400 font-mono">ID: {{ $item->id_user }}</div>
                    </td>
                    <td class="px-6 py-5 text-slate-600 font-semibold">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="px-6 py-5 font-bold text-slate-700">{{ $item->total_absen }} Hari</td>
                    <td class="px-6 py-5 text-slate-700 font-black">Rp {{ number_format($item->user->gaji ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-5 text-sky-600 font-medium">Rp {{ number_format($item->total_lembur * 10000, 0, ',', '.') }}</td>
                    <td class="px-6 py-5 text-emerald-700 font-black">Rp {{ number_format($item->total_gaji, 0, ',', '.') }}</td>
                    <td class="px-6 py-5 text-center">
                        @if($item->status == 'minta_konfirmasi')
                            {{-- Form Tersembunyi untuk Konfirmasi (Method PUT) --}}
                            <form id="form-konfirmasi-{{ $item->id_gaji }}" action="{{ url('/gaji/konfirmasi', $item->id_gaji) }}" method="POST" class="hidden">
                                @csrf
                                @method('PUT')
                            </form>
                            <button onclick="if(confirm('Kirim permintaan konfirmasi untuk Gaji ID #{{ $item->id_gaji }}?')) document.getElementById('form-konfirmasi-{{ $item->id_gaji }}').submit();" 
                                class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded-lg text-[10px] font-bold hover:bg-yellow-500 transition-all">
                               MINTA KONFIRMASI
                            </button>
                        @else
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase 
                                {{ $item->status == 'setuju' ? 'bg-emerald-100 text-emerald-700' : 
                                ($item->status == 'tunggu_konfirmasi' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700') }}">
                                
                                {{ $item->status == 'setuju' ? 'Disetujui' : 
                                ($item->status == 'tunggu_konfirmasi' ? 'Tunggu Konfirmasi' : 'Ditolak') }}
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        const table = $('#gajiTable').DataTable({
            dom: 't<"flex justify-between items-center mt-4"ip>',
            pageLength: 10,
            language: { info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data" }
        });

        $('#gajiSearch').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('#dataLengthSelect').on('change', function() {
            table.page.len(this.value).draw();
        });
    });
</script>
@endsection