@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-[#064E3B] tracking-tight">Data Debit</h1>
        <p class="text-sm text-gray-400">Manajemen catatan debit dan piutang operasional.</p>
    </div>
    
    <button class="bg-[#fbc565] hover:bg-[#f9b233] text-black font-bold py-2 px-6 rounded-lg text-sm flex items-center shadow-sm transition">
        <a href="{{ route('debit.create') }}" class="text-primary" style="text-decoration: none;">
            <i class="fas fa-plus mr-2"></i> Tambah Debit
        </a>
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">ID</th>
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Nama Debit</th>
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Keterangan</th>
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Jumlah (Saldo)</th>
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Tanggal</th>
                    {{-- <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Aksi</th> --}}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
            @forelse($debit as $item)
            <tr class="hover:bg-gray-50/50 transition">
                <td class="px-6 py-4 text-center text-sm font-bold text-gray-400">#{{ $item->id_debit }}</td>
                <td class="px-6 py-4">
                    <p class="text-sm font-bold text-gray-800">{{ $item->nama }}</p>
                    {{-- <p class="text-[10px] text-gray-400 italic">Kategori: {{ $item->jenis_pengeluaran }}</p> --}}
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm font-bold text-gray-800">{{ $item->keterangan }}</p>
                    {{-- <p class="text-[10px] text-gray-400 italic">Kategori: {{ $item->jenis_pengeluaran }}</p> --}}
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm font-bold text-red-500">Rp {{ number_format($item->saldo_debit, 0, ',', '.') }}</span>
                </td>
    
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">Data tidak ditemukan di database.</td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>
    
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase">
        <span>Total {{ count($debit) }} Data</span>
        <div class="flex gap-2">
            <button class="px-3 py-1 bg-white border border-gray-200 rounded hover:bg-gray-100">Prev</button>
            <button class="px-3 py-1 bg-white border border-gray-200 rounded hover:bg-gray-100">Next</button>
        </div>
    </div>
</div>
@endsection