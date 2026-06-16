@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#064E3B] tracking-tight">CashFlow Kiwari Farm</h1>
    <p class="text-xs text-gray-400">Monitoring Kiwari Farm operational health and financial flows.</p>
</div>

{{-- Layout Card Summary --}}
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
            <span class="text-[9px] font-black text-white bg-white/10 uppercase tracking-widest px-3 py-1 rounded-full">
                {{ now()->locale('id')->translatedFormat('F Y') }}
            </span>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-black text-white tracking-tight">
                Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
            </h2>
        </div>
        <i class="fas fa-circle-check absolute -right-2 -bottom-2 text-6xl text-white/5"></i>
    </div>

    {{-- Grid Tombol Navigasi (Pemasukan & Pengeluaran) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Button Debit (Pemasukan) --}}
        <a href="/cashflow/debit" class="group relative flex items-center justify-between bg-gradient-to-r from-emerald-50 to-emerald-100/60 hover:from-emerald-100 hover:to-emerald-200/80 active:scale-[0.99] border border-emerald-200/60 p-4 rounded-xl shadow-sm hover:shadow transition-all duration-200" style="text-decoration: none;">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-white text-emerald-600 rounded-xl shadow-sm text-sm group-hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-arrow-trend-up"></i>
                </div>
                <div>
                    <p class="text-[10px] uppercase text-emerald-700/60 font-extrabold tracking-wider mb-0.5">Pemasukan</p>
                    <p class="text-lg font-black text-emerald-900 leading-none">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                    <p class="text-[9px] font-bold text-emerald-600/70 uppercase tracking-wider mt-1.5">
                        {{ now()->locale('id')->translatedFormat('F Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-2 text-emerald-600 font-bold text-xs bg-white/80 group-hover:bg-emerald-600 group-hover:text-white px-3 py-1.5 rounded-lg shadow-sm border border-emerald-200/40 transition-all duration-200">
                <span>Detail</span>
                <i class="fas fa-chevron-right text-[10px] transform group-hover:translate-x-0.5 transition-transform"></i>
            </div>
        </a>

        {{-- Button Kredit (Pengeluaran) --}}
        <a href="/cashflow/kredit" class="group relative flex items-center justify-between bg-gradient-to-r from-red-50 to-red-100/60 hover:from-red-100 hover:to-red-200/80 active:scale-[0.99] border border-red-200/60 p-4 rounded-xl shadow-sm hover:shadow transition-all duration-200" style="text-decoration: none;">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-white text-red-500 rounded-xl shadow-sm text-sm group-hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-arrow-trend-down"></i>
                </div>
                <div>
                    <p class="text-[10px] uppercase text-red-700/60 font-extrabold tracking-wider mb-0.5">Pengeluaran</p>
                    <p class="text-lg font-black text-red-900 leading-none">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    <p class="text-[9px] font-bold text-red-500/70 uppercase tracking-wider mt-1.5">
                        {{ now()->locale('id')->translatedFormat('F Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-2 text-red-600 font-bold text-xs bg-white/80 group-hover:bg-red-500 group-hover:text-white px-3 py-1.5 rounded-lg shadow-sm border border-red-200/40 transition-all duration-200">
                <span>Detail</span>
                <i class="fas fa-chevron-right text-[10px] transform group-hover:translate-x-0.5 transition-transform"></i>
            </div>
        </a>
    </div>
</div>

{{-- Tabel Transaksi dengan Fitur Search dan Select Limit --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-start">
            <h3 class="text-lg font-bold text-gray-800">Gabungan Transaksi</h3>
            
            {{-- Select Fitur Batasi Data --}}
            <div class="flex items-center gap-2 bg-[#F8FAFC] px-3 py-1.5 rounded-xl border border-gray-100">
                <label for="entriesLimit" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Tampilkan:</label>
                <select id="entriesLimit" class="bg-transparent text-xs font-bold text-gray-700 outline-none cursor-pointer">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="all">Semua</option>
                </select>
            </div>
        </div>
        
        <div class="flex gap-2 w-full md:w-auto">
            {{-- Tombol Cetak PDF --}}
            <a href="{{ route('cashflow.pdf') }}" class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-xs font-bold transition shadow-sm" style="text-decoration: none;">
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

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="cashflowTable">
            <thead>
                <tr class="bg-[#F1F5F9] text-[#64748B] text-[10px] uppercase font-extrabold tracking-widest border-b border-gray-200">
                    <th rowspan="2" class="px-4 py-3 text-center border-r border-gray-200 w-16">NO</th>
                    <th rowspan="2" class="px-4 py-3 border-r border-gray-200 w-28">Tanggal</th>
                    <th rowspan="2" class="px-6 py-3 border-r border-gray-200">Keterangan Transaksi</th>
                    <th rowspan="2" class="px-4 py-3 text-center border-r border-gray-200 w-24">Tipe</th>
                    <th rowspan="2" class="px-6 py-3 text-right border-r border-gray-200 w-40">Debit (Masuk)</th>
                    <th colspan="2" class="py-2 text-center border-r border-gray-200 bg-[#E2E8F0]">Kredit (Keluar)</th>
                </tr>
                <tr class="bg-[#F1F5F9] text-[#64748B] text-[10px] uppercase font-extrabold tracking-widest border-b border-gray-200">
                    <th class="px-4 py-2 text-right border-r border-gray-200 bg-[#FFFBEB] text-amber-700 w-36">Tetap</th>
                    <th class="px-4 py-2 text-right text-red-700 bg-[#FEF2F2] w-36">Tidak Tetap</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($cashflow as $item)
                <tr class="hover:bg-gray-50/50 transition row-item">
                    <td class="px-4 py-4 text-center text-gray-400 font-medium border-r border-gray-50 search-target">
                        #{{ $loop->iteration }}
                    </td>
                    <td class="px-4 py-4 text-gray-600 font-semibold border-r border-gray-50">
                        {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d M Y') }}
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-800 border-r border-gray-50 search-target">
                        {{ $item->nama }}
                    </td>
                    <td class="px-4 py-4 text-center border-r border-gray-50">
                        <span class="bg-{{ $item->color }}-100 text-{{ $item->color }}-600 px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-tighter search-target">
                            {{ $item->kategori }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-black text-green-600 border-r border-gray-50 bg-[#F0FDF4]/30">
                        @if($item->kategori != 'PENGELUARAN')
                            <span class="text-[10px] text-gray-400 mr-0.5">Rp</span> {{ number_format($item->nominal, 0, ',', '.') }}
                        @else
                            <span class="text-gray-300 font-normal">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-right font-black text-amber-600 border-r border-gray-50 bg-[#FFFDF5]/50">
                        @if($item->kategori == 'PENGELUARAN' && (isset($item->sifat) && $item->sifat == 'tetap'))
                            <span class="text-[10px] text-gray-400 mr-0.5">Rp</span> {{ number_format($item->nominal, 0, ',', '.') }}
                        @else
                            <span class="text-gray-300 font-normal">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-right font-black text-red-500 bg-[#FEF2F2]/30">
                        @if($item->kategori == 'PENGELUARAN' && (!isset($item->sifat) || $item->sifat == 'tidak tetap'))
                            <span class="text-[10px] text-gray-400 mr-0.5">Rp</span> {{ number_format($item->nominal, 0, ',', '.') }}
                        @else
                            <span class="text-gray-300 font-normal">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic">Belum ada riwayat transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Engine Sinkronisasi Fitur Search & Batas Tampilan Data --}}
<script>
    const searchInput = document.getElementById('cashflowSearch');
    const entriesLimit = document.getElementById('entriesLimit');

    function updateTableDisplay() {
        let filter = searchInput.value.toLowerCase();
        let limit = entriesLimit.value === 'all' ? Infinity : parseInt(entriesLimit.value);
        let rows = document.querySelectorAll('#cashflowTable .row-item');
        
        let visibleCount = 0;
        let hasVisibleRows = false;

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            let matchesSearch = text.includes(filter);

            if (matchesSearch) {
                if (visibleCount < limit) {
                    row.style.display = "";
                    visibleCount++;
                    hasVisibleRows = true;
                } else {
                    row.style.display = "none";
                }
            } else {
                row.style.display = "none";
            }
        });

        let emptyMsg = document.getElementById('noResultsMsg');
        if (!hasVisibleRows && filter !== "") {
            if (!emptyMsg) {
                let tbody = document.querySelector('#cashflowTable tbody');
                let newRow = tbody.insertRow();
                newRow.id = "noResultsMsg";
                let cell = newRow.insertCell(0);
                cell.colSpan = 7; 
                cell.className = "px-6 py-10 text-center text-gray-400 italic";
                cell.innerHTML = "Transaksi tidak ditemukan...";
            }
        } else if (emptyMsg) {
            emptyMsg.remove();
        }
    }

    searchInput.addEventListener('keyup', updateTableDisplay);
    entriesLimit.addEventListener('change', updateTableDisplay);
    document.addEventListener('DOMContentLoaded', updateTableDisplay);
</script>
@endsection
