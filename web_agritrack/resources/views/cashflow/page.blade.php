@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#064E3B] tracking-tight">CashFlow Kiwari Farm</h1>
    <p class="text-xs text-gray-400">Monitoring Kiwari Farm operational health and financial flows.</p>
</div>

{{-- Layout Card Summary (Tetap sama) --}}
<div class="flex flex-col gap-4 mb-6">
    {{-- Saldo Akhir --}}
    <div class="bg-[#064E3B] p-5 rounded-2xl flex flex-col justify-between relative overflow-hidden shadow-lg shadow-green-900/20 group">
        <div class="flex justify-between items-center mb-3">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-white/10 rounded-lg text-white">
                    <i class="fas fa-wallet text-sm"></i>
                </div>
                <p class="text-[10px] uppercase text-white/60 font-extrabold tracking-widest">Saldo Bersih</p>
            </div>
            <span class="text-[9px] font-black text-white/50 uppercase tracking-widest border border-white/10 px-3 py-1 rounded-full">Net Balance</span>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-black text-white tracking-tight">
                Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
            </h2>
        </div>
        <i class="fas fa-circle-check absolute -right-2 -bottom-2 text-6xl text-white/5"></i>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Debit --}}
        <a href="/cashflow/debit" class="bg-[#ECFDF5] p-4 rounded-xl border border-green-100 flex flex-col justify-between relative overflow-hidden hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-2">
                <div class="p-2 bg-white rounded-lg text-green-600 shadow-sm text-xs">
                    <i class="fas fa-arrow-trend-up"></i>
                </div>
                <span class="text-[9px] font-black text-green-600 uppercase tracking-widest bg-white px-2 py-0.5 rounded-md shadow-sm">Debit</span>
            </div>
            <div>
                <p class="text-[10px] uppercase text-green-700/60 font-bold tracking-wider">Pemasukan</p>
                <p class="text-lg font-extrabold text-green-900">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
            </div>
        </a>

        {{-- Kredit --}}
        <a href="/cashflow/kredit" class="bg-[#FEF2F2] p-4 rounded-xl border border-red-100 flex flex-col justify-between relative overflow-hidden hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-2">
                <div class="p-2 bg-white rounded-lg text-red-500 shadow-sm text-xs">
                    <i class="fas fa-arrow-trend-down"></i>
                </div>
                <span class="text-[9px] font-black text-red-600 uppercase tracking-widest bg-white px-2 py-0.5 rounded-md shadow-sm">Kredit</span>
            </div>
            <div>
                <p class="text-[10px] uppercase text-red-700/60 font-bold tracking-wider">Pengeluaran</p>
                <p class="text-lg font-extrabold text-red-900">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
            </div>
        </a>
    </div>
</div>

{{-- Tabel Transaksi dengan Fitur Search --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
        <h3 class="text-lg font-bold text-gray-800">Gabungan Transaksi</h3>
        
        {{-- Input Search Bar --}}
        {{-- Letakkan di bawah h1 atau di samping input search --}}
<div class="flex flex-col md:flex-row justify-between items-center gap-4">
    {{-- <h3 class="text-lg font-bold text-gray-800">Gabungan Transaksi</h3> --}}
    
    <div class="flex gap-2 w-full md:w-auto">
        {{-- Tombol Cetak PDF --}}
        <a href="{{ route('cashflow.pdf') }}" class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-xs font-bold transition shadow-sm">
            <i class="fas fa-file-pdf"></i>
            Cetak PDF
        </a>

        {{-- Input Search Bar --}}
        <div class="relative w-full md:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="fas fa-search text-gray-400 text-xs"></i>
            </span>
            <input type="text" id="cashflowSearch" 
                class="block w-full pl-10 pr-4 py-2 border border-gray-100 rounded-xl bg-[#F8FAFC] text-xs font-semibold focus:ring-2 focus:ring-[#065F46]/20 focus:border-[#065F46] outline-none transition" 
                placeholder="Cari transaksi...">
        </div>
    </div>
</div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left" id="cashflowTable">
            <thead>
                <tr class="bg-[#F1F5F9] text-[#64748B] text-[10px] uppercase font-extrabold tracking-widest">
                    <th class="px-6 py-4 text-center">ID</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Keterangan Transaksi</th>
                    <th class="px-6 py-4">Tipe</th>
                    <th class="px-6 py-4 text-right">Nominal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($cashflow as $item)
                <tr class="hover:bg-gray-50/50 transition row-item">
                    <td class="px-6 py-5 text-center text-gray-400 font-medium search-target">#{{ $item->id }}</td>
                    <td class="px-6 py-5 text-gray-600 font-semibold">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="px-6 py-5 font-bold text-[#065F46] search-target">{{ $item->nama }}</td>
                    <td class="px-6 py-5">
                        <span class="bg-{{ $item->color }}-100 text-{{ $item->color }}-600 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter search-target">
                            {{ $item->kategori }}
                        </span>
                    </td>
                    <td class="px-6 py-5 text-right font-black {{ $item->kategori == 'PENGELUARAN' ? 'text-red-500' : 'text-green-600' }}">
                        <span class="text-[10px] text-gray-400 mr-1">Rp</span>
                        {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Belum ada riwayat transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Script Search Engine --}}
<script>
    document.getElementById('cashflowSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#cashflowTable .row-item');
        let hasVisibleRows = false;

        rows.forEach(row => {
            // Mengambil teks dari kolom ID, Keterangan, dan Tipe (class search-target)
            let text = row.innerText.toLowerCase();
            if (text.includes(filter)) {
                row.style.display = "";
                hasVisibleRows = true;
            } else {
                row.style.display = "none";
            }
        });

        // Menampilkan baris "Data tidak ditemukan" jika hasil pencarian kosong
        let emptyMsg = document.getElementById('noResultsMsg');
        if (!hasVisibleRows && filter !== "") {
            if (!emptyMsg) {
                let tbody = document.querySelector('#cashflowTable tbody');
                let newRow = tbody.insertRow();
                newRow.id = "noResultsMsg";
                let cell = newRow.insertCell(0);
                cell.colSpan = 5;
                cell.className = "px-6 py-10 text-center text-gray-400 italic";
                cell.innerHTML = "Transaksi tidak ditemukan...";
            }
        } else if (emptyMsg) {
            emptyMsg.remove();
        }
    });
</script>
@endsection