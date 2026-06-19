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

    {{-- Grid Tombol Navigasi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="/cashflow/debit" class="group relative flex items-center justify-between bg-gradient-to-r from-emerald-50 to-emerald-100/60 hover:from-emerald-100 hover:to-emerald-200/80 active:scale-[0.99] border border-emerald-200/60 p-4 rounded-xl shadow-sm hover:shadow transition-all duration-200" style="text-decoration: none;">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-white text-emerald-600 rounded-xl shadow-sm text-sm group-hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-arrow-trend-up"></i>
                </div>
                <div>
                    <p class="text-[10px] uppercase text-emerald-700/60 font-extrabold tracking-wider mb-0.5">Pemasukan</p>
                    <p class="text-lg font-black text-emerald-900 leading-none">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 text-emerald-600 font-bold text-xs bg-white/80 group-hover:bg-emerald-600 group-hover:text-white px-3 py-1.5 rounded-lg shadow-sm border border-emerald-200/40 transition-all duration-200">
                <span>Detail</span>
                <i class="fas fa-chevron-right text-[10px]"></i>
            </div>
        </a>

        <a href="/cashflow/kredit" class="group relative flex items-center justify-between bg-gradient-to-r from-red-50 to-red-100/60 hover:from-red-100 hover:to-red-200/80 active:scale-[0.99] border border-red-200/60 p-4 rounded-xl shadow-sm hover:shadow transition-all duration-200" style="text-decoration: none;">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-white text-red-500 rounded-xl shadow-sm text-sm group-hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-arrow-trend-down"></i>
                </div>
                <div>
                    <p class="text-[10px] uppercase text-red-700/60 font-extrabold tracking-wider mb-0.5">Pengeluaran</p>
                    <p class="text-lg font-black text-red-900 leading-none">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 text-red-600 font-bold text-xs bg-white/80 group-hover:bg-red-500 group-hover:text-white px-3 py-1.5 rounded-lg shadow-sm border border-red-200/40 transition-all duration-200">
                <span>Detail</span>
                <i class="fas fa-chevron-right text-[10px]"></i>
            </div>
        </a>
    </div>
</div>

{{-- Tabel Transaksi --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3">
            <h3 class="text-lg font-bold text-gray-800">Gabungan Transaksi</h3>
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
            {{-- Tombol Pemicu Modal --}}
            <button type="button" onclick="toggleModal()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-xs font-bold transition">
                <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
            </button>
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-search text-gray-400 text-xs"></i></span>
                <input type="text" id="cashflowSearch" class="block w-full pl-10 pr-4 py-2 border border-gray-100 rounded-xl bg-[#F8FAFC] text-xs font-semibold outline-none" placeholder="Cari transaksi...">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="cashflowTable">
            <thead>
                <tr class="bg-[#F1F5F9] text-[#64748B] text-[10px] uppercase font-extrabold tracking-widest border-b border-gray-200">
                    <th rowspan="2" class="px-4 py-3 text-center border-r">NO</th>
                    <th rowspan="2" class="px-4 py-3 border-r">Tanggal</th>
                    <th rowspan="2" class="px-6 py-3 border-r">Keterangan</th>
                    <th rowspan="2" class="px-4 py-3 text-center border-r">Tipe</th>
                    <th rowspan="2" class="px-6 py-3 text-right border-r">Debit</th>
                    <th colspan="2" class="py-2 text-center border-r bg-[#E2E8F0]">Kredit (Keluar)</th>
                </tr>
                <tr class="bg-[#F1F5F9] text-[#64748B] text-[10px] uppercase font-extrabold border-b">
                    <th class="px-4 py-2 text-right border-r bg-[#FFFBEB] text-amber-700">Tetap</th>
                    <th class="px-4 py-2 text-right text-red-700 bg-[#FEF2F2]">Tidak Tetap</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($cashflow as $item)
                <tr class="hover:bg-gray-50/50 transition row-item">
                    <td class="px-4 py-4 text-center text-gray-400 border-r">#{{ $loop->iteration }}</td>
                    <td class="px-4 py-4 text-gray-600 border-r">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-bold text-gray-800 border-r search-target">{{ $item->nama }}</td>
                    <td class="px-4 py-4 text-center border-r"><span class="bg-gray-100 px-2 py-1 rounded text-[9px] font-black uppercase">{{ $item->kategori }}</span></td>
                    <td class="px-6 py-4 text-right font-black text-green-600 border-r bg-green-50/30">
                        {{ $item->kategori != 'PENGELUARAN' ? 'Rp ' . number_format($item->nominal, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-4 py-4 text-right font-black text-amber-600 border-r bg-amber-50/30">
                        {{ ($item->kategori == 'PENGELUARAN' && ($item->sifat == 'tetap')) ? 'Rp ' . number_format($item->nominal, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-4 py-4 text-right font-black text-red-500 bg-red-50/30">
                        {{ ($item->kategori == 'PENGELUARAN' && ($item->sifat != 'tetap')) ? 'Rp ' . number_format($item->nominal, 0, ',', '.') : '-' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400 italic">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Filter PDF --}}
<div id="modalFilter" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white p-6 rounded-2xl w-80 shadow-2xl">
        <h3 class="font-bold text-gray-800 mb-4">Pilih Periode Laporan</h3>
        <form action="{{ route('cashflow.pdf') }}" method="GET">
            <div class="mb-4">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Bulan</label>
                <select name="bulan" class="w-full border rounded-lg p-2 text-sm">
                    @for ($m=1; $m<=12; $m++)
                        <option value="{{ $m }}" {{ request('bulan', now()->month) == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="mb-6">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tahun</label>
                <select name="tahun" class="w-full border rounded-lg p-2 text-sm">
                    @for ($y=now()->year; $y>=2020; $y--)
                        <option value="{{ $y }}" {{ request('tahun', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="toggleModal()" class="w-1/2 bg-gray-100 py-2 rounded-lg text-sm font-bold">Batal</button>
                <button type="submit" class="w-1/2 bg-red-500 text-white py-2 rounded-lg text-sm font-bold">Cetak</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Modal Toggle
    function toggleModal() {
        document.getElementById('modalFilter').classList.toggle('hidden');
    }

    // Engine Search & Limit
    const searchInput = document.getElementById('cashflowSearch');
    const entriesLimit = document.getElementById('entriesLimit');
    
    function updateTableDisplay() {
        let filter = searchInput.value.toLowerCase();
        let limit = entriesLimit.value === 'all' ? Infinity : parseInt(entriesLimit.value);
        let rows = document.querySelectorAll('#cashflowTable .row-item');
        let visibleCount = 0;

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = (text.includes(filter) && visibleCount < limit) ? "" : "none";
            if (text.includes(filter)) visibleCount++;
        });
    }

    searchInput.addEventListener('keyup', updateTableDisplay);
    entriesLimit.addEventListener('change', updateTableDisplay);
</script>
@endsection