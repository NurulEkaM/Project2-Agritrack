@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('cashflow.page') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-semibold p-2.5 rounded-xl text-sm flex items-center justify-center shadow-sm transition border border-gray-200 group" title="Kembali ke Cashflow" style="text-decoration: none;">
            <i class="fas fa-arrow-left text-gray-500 group-hover:text-gray-700"></i> 
        </a>
        <div>
            <h1 class="text-2xl font-bold text-[#064E3B] tracking-tight">Data Debit</h1>
            <p class="text-sm text-gray-400">Manajemen catatan debit dan piutang operasional.</p>
        </div>
    </div>
    
    <div class="w-full md:w-auto">
        <a href="{{ route('debit.create') }}" class="inline-flex items-center justify-center bg-[#fbc565] hover:bg-[#f9b233] text-black font-bold py-2.5 px-6 rounded-xl text-sm shadow-sm transition w-full md:w-auto" style="text-decoration: none;">
            <i class="fas fa-plus mr-2"></i> Tambah Debit
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between bg-gray-50/50 gap-4">
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Tampilkan</span>
            <select id="jsPerPage" class="bg-white border border-gray-200 rounded-lg text-xs font-bold px-2.5 py-1.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#fbc565] cursor-pointer shadow-sm">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="all">Semua</option>
            </select>
            <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">data</span>
        </div>

        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fas fa-search text-gray-400 text-xs"></i>
            </span>
            <input type="text" id="jsSearchInput" placeholder="Cari nama atau keterangan..." class="w-full bg-white border border-gray-200 rounded-lg text-xs font-medium pl-9 pr-4 py-2 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#fbc565] shadow-sm transition">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/70 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center w-20">No</th>
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Nama Debit</th>
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Keterangan</th>
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Jumlah (Saldo)</th>
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Tanggal</th>
                </tr>
            </thead>
            <tbody id="jsTableBody" class="divide-y divide-gray-50">
                @forelse($debit as $item)
                <tr class="data-row hover:bg-gray-50/40 transition duration-150" 
                    data-timestamp="{{ \Carbon\Carbon::parse($item->tanggal)->timestamp }}"
                    data-search="{{ strtolower($item->nama . ' ' . $item->keterangan) }}">
                    <td class="jsRowIndex px-6 py-4 text-center text-sm font-bold text-gray-400"></td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-gray-800">{{ $item->nama }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-gray-600">{{ $item->keterangan }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-bold text-red-500">Rp {{ number_format($item->saldo_debit, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                    </td>
                </tr>
                @empty
                <tr class="jsFallbackEmpty">
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic bg-gray-50/10">
                        <i class="fas fa-folder-open text-gray-300 text-2xl mb-2 block"></i>
                        Data tidak ditemukan di database.
                    </td>
                </tr>
                @endforelse

                <tr id="jsNoResultsRow" style="display: none;">
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic bg-gray-50/10">
                        <i class="fas fa-search text-gray-300 text-2xl mb-2 block"></i>
                        Data yang dicari tidak ditemukan.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">
        <span id="jsPaginationInfo">Menampilkan 0 - 0 dari 0 Data</span>
        <div class="flex gap-2" id="jsPaginationButtons">
            <button id="jsPrevBtn" class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 shadow-sm transition hover:bg-gray-50 focus:outline-none">Prev</button>
            <button id="jsNextBtn" class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 shadow-sm transition hover:bg-gray-50 focus:outline-none">Next</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('jsTableBody');
    const selectPerPage = document.getElementById('jsPerPage');
    const searchInput = document.getElementById('jsSearchInput');
    const prevBtn = document.getElementById('jsPrevBtn');
    const nextBtn = document.getElementById('jsNextBtn');
    const paginationInfo = document.getElementById('jsPaginationInfo');
    const noResultsRow = document.getElementById('jsNoResultsRow');

    // 1. Ambil baris data asli awal
    let allRows = Array.from(tableBody.querySelectorAll('.data-row'));
    
    // 2. Lakukan SORTING dari data tanggal terbaru (Timestamp Terbesar ke Terkecil)
    allRows.sort((a, b) => {
        const timeA = parseInt(a.getAttribute('data-timestamp')) || 0;
        const timeB = parseInt(b.getAttribute('data-timestamp')) || 0;
        return timeB - timeA;
    });

    // 3. Masukkan kembali susunan data terurut ke dalam tabel HTML
    allRows.forEach(row => tableBody.appendChild(row));
    // Taruh baris "tidak ditemukan" di bagian paling bawah tak terlihat
    tableBody.appendChild(noResultsRow);

    // Variabel state aktif
    let filteredRows = [...allRows]; 
    let currentPage = 1;
    let rowsPerPage = parseInt(selectPerPage.value) || 10;

    function updateTable() {
        const totalRows = filteredRows.length;
        
        // Sembunyikan semua baris induk terlebih dahulu
        allRows.forEach(row => row.style.display = 'none');

        if (totalRows === 0) {
            noResultsRow.style.display = '';
            paginationInfo.textContent = "Menampilkan 0 - 0 dari 0 Data";
            prevBtn.disabled = true;
            nextBtn.disabled = true;
            prevBtn.className = 'px-3 py-1.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-300 cursor-not-allowed';
            nextBtn.className = 'px-3 py-1.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-300 cursor-not-allowed';
            return;
        } else {
            noResultsRow.style.display = 'none';
        }

        // Hitung batasan indeks halaman
        let start = (currentPage - 1) * rowsPerPage;
        let end = start + rowsPerPage;

        if (selectPerPage.value === 'all') {
            start = 0;
            end = totalRows;
        }

        // Tampilkan baris terpilih dan perbarui nomor urut (ID) agar berurutan dari #1
        filteredRows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';
                // Setel nomor ID berurutan berdasarkan urutan tanggal terbaru global (bukan index halaman)
                row.querySelector('.jsRowIndex').textContent = `#${index + 1}`;
            }
        });

        const displayEnd = end > totalRows ? totalRows : end;
        paginationInfo.textContent = `Menampilkan ${start + 1} - ${displayEnd} dari ${totalRows} Data`;

        // Pengaturan Tombol Navigasi Prev
        if (currentPage === 1 || selectPerPage.value === 'all') {
            prevBtn.disabled = true;
            prevBtn.className = 'px-3 py-1.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-300 cursor-not-allowed';
        } else {
            prevBtn.disabled = false;
            prevBtn.className = 'px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 shadow-sm transition hover:bg-gray-50 focus:outline-none';
        }

        // Pengaturan Tombol Navigasi Next
        if (end >= totalRows || selectPerPage.value === 'all') {
            nextBtn.disabled = true;
            nextBtn.className = 'px-3 py-1.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-300 cursor-not-allowed';
        } else {
            nextBtn.disabled = false;
            nextBtn.className = 'px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 shadow-sm transition hover:bg-gray-50 focus:outline-none';
        }
    }

    // EVENT LISTENER: Fitur Pencarian Real-time
    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        
        // Filter baris berdasarkan data pencarian nama & keterangan
        filteredRows = allRows.filter(row => {
            return row.getAttribute('data-search').includes(query);
        });

        currentPage = 1; // Reset halaman ke posisi 1 saat mencari data
        updateTable();
    });

    // EVENT LISTENER: Mengubah jumlah entri data per halaman
    selectPerPage.addEventListener('change', function () {
        rowsPerPage = this.value === 'all' ? filteredRows.length : parseInt(this.value);
        currentPage = 1; 
        updateTable();
    });

    // EVENT LISTENER: Klik Navigasi Prev
    prevBtn.addEventListener('click', function () {
        if (currentPage > 1) {
            currentPage--;
            updateTable();
        }
    });

    // EVENT LISTENER: Klik Navigasi Next
    nextBtn.addEventListener('click', function () {
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            updateTable();
        }
    });

    // Inisialisasi penampilan tabel saat pertama kali dibuka
    updateTable();
});
</script>
@endsection