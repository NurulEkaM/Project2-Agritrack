@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-[#064E3B] tracking-tight">Absensi Pegawai Kiwari Farm</h1>
    <p class="text-sm text-gray-400">Monitoring data kehadiran berdasarkan log database project_agritrack.</p>
</div>

{{-- Statistik Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-green-50 rounded-xl text-green-600">
                <i class="fas fa-users"></i>
            </div>
            <span class="text-xs font-bold text-green-500">Total</span>
        </div>
        <div>
            <p class="text-[11px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">Total Record Absensi</p>
            <p class="text-2xl font-extrabold text-gray-800 tracking-tight group-hover:text-[#065F46] transition-colors">
                {{ $absensi->count() }} <span class="text-sm font-normal text-gray-400">Data</span>
            </p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                <i class="fas fa-plane-departure"></i>
            </div>
        </div>
        <div>
            <p class="text-[11px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">Kebun Lanud</p>
            <p class="text-2xl font-extrabold text-gray-800 tracking-tight group-hover:text-[#065F46] transition-colors">
                {{ $absensi->where('lokasi', 'kebun_lanud')->count() }} <span class="text-sm font-normal text-gray-400">Record</span>
            </p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                <i class="fas fa-mountain"></i>
            </div>
        </div>
        <div>
            <p class="text-[11px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">Kebun Sadang</p>
            <p class="text-2xl font-extrabold text-gray-800 tracking-tight group-hover:text-[#065F46] transition-colors">
                {{ $absensi->where('lokasi', 'kebun_sadang')->count() }} <span class="text-sm font-normal text-gray-400">Record</span>
            </p>
        </div>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-4 border-b border-gray-50">
        <h3 class="text-lg font-bold text-gray-800">Riwayat Tabel Absensi</h3>
        
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400 text-xs"></i>
                </span>
                <input type="text" id="absensiSearch" 
                    class="block w-full pl-10 pr-4 py-2 border border-gray-100 rounded-xl bg-[#F8FAFC] text-xs font-semibold focus:ring-2 focus:ring-[#065F46]/20 focus:border-[#065F46] outline-none transition" 
                    placeholder="Cari absensi (lokasi/kegiatan/status)...">
            </div>

            <button class="bg-[#F8FAFC] text-xs font-bold text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-100 border border-gray-200/50 whitespace-nowrap">
                <i class="fas fa-file-export mr-2"></i> Export
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-100 text-green-700 text-sm font-semibold rounded-xl">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left" id="absensiTable">
            <thead>
                <tr class="bg-[#F1F5F9] text-[#64748B] text-[10px] uppercase font-extrabold tracking-widest">
                    <th class="px-6 py-4 text-center">No</th>
                    <th class="px-6 py-4">Waktu Datang</th>
                    <th class="px-6 py-4">Waktu Pulang</th>
                    <th class="px-6 py-4">Lokasi</th>
                    <th class="px-6 py-4 text-center">Bukti Selfie</th>
                    <th class="px-6 py-4">Kegiatan</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Lembur</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($absensi as $row)
                <tr class="hover:bg-gray-50/50 transition row-item">
                    <td class="px-6 py-5 text-center text-gray-400 font-medium">#{{ $loop->iteration }}</td>
                    <td class="px-6 py-5 text-gray-600 font-semibold">{{ $row->tanggal_datang }}</td>
                    <td class="px-6 py-5 text-gray-600 font-semibold">
                        {{ $row->tanggal_pulang ?? '-' }}
                    </td>
                    <td class="px-6 py-5 uppercase">
                        @if($row->lokasi == 'kebun_lanud')
                            <span class="text-blue-600 font-bold"><i class="fas fa-plane mr-1 text-[10px]"></i> LANUD</span>
                        @else
                            <span class="text-indigo-600 font-bold"><i class="fas fa-seedling mr-1 text-[10px]"></i> SADANG</span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-center">
                        @if($row->image)
                            <div class="relative inline-block">
                                <img src="{{ asset('storage/' . $row->image) }}" 
                                     onclick="openPhotoModal(this.src)"
                                     alt="Selfie" 
                                     class="w-10 h-10 rounded-lg object-cover border-2 border-white shadow-sm hover:border-[#065F46] transition-all cursor-zoom-in">
                            </div>
                        @else
                            <span class="text-gray-300 text-[10px] italic">No Photo</span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-gray-500 italic max-w-[200px] truncate">
                        {{ $row->kegiatan ?? 'n/a' }}
                    </td>
                    <td class="px-6 py-5 text-center">
                        @if($row->status == 'absen_pulang')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">
                                Hadir
                            </span>
                        @elseif($row->status == 'absen_datang')
                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">
                                proses kerja
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">
                                Tidak Hadir
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-center font-bold text-gray-700">
                        {{ $row->total_lembur }} Jam
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="8" class="px-6 py-10 text-center text-gray-400 italic">Tidak ada data absensi ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-6 border-t border-gray-50">
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
            Database: project_agritrack | Table: absensi
        </p>
    </div>
</div>

{{-- MODAL FULL FOTO --}}
<div id="photoModal" class="fixed inset-0 z-[999] hidden flex items-center justify-center bg-black/90 p-4 transition-opacity duration-300">
    <button onclick="closePhotoModal()" class="absolute top-6 right-6 text-white text-3xl hover:text-gray-300 transition">
        <i class="fas fa-times"></i>
    </button>
    <img id="modalImg" src="" class="max-w-full max-h-[90vh] rounded-2xl shadow-2xl border-4 border-white/10">
</div>

<script>
    // FUNGSI MODAL FOTO
    function openPhotoModal(src) {
        const modal = document.getElementById('photoModal');
        const modalImg = document.getElementById('modalImg');
        modalImg.src = src;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePhotoModal() {
        const modal = document.getElementById('photoModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal on click outside image
    document.getElementById('photoModal').addEventListener('click', function(e) {
        if (e.target === this) closePhotoModal();
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") closePhotoModal();
    });

    // FUNGSI SEARCH
    document.getElementById('absensiSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#absensiTable .row-item');
        let hasVisibleRows = false;

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            if (text.includes(filter)) {
                row.style.display = "";
                hasVisibleRows = true;
            } else {
                row.style.display = "none";
            }
        });

        let emptyMsg = document.getElementById('noResultsMsg');
        if (!hasVisibleRows && filter !== "") {
            if (!emptyMsg) {
                let tbody = document.querySelector('#absensiTable tbody');
                let newRow = tbody.insertRow();
                newRow.id = "noResultsMsg";
                let cell = newRow.insertCell(0);
                cell.colSpan = 8;
                cell.className = "px-6 py-10 text-center text-gray-400 italic";
                cell.innerHTML = "Data absensi tidak ditemukan...";
            }
        } else if (emptyMsg) {
            emptyMsg.remove();
        }
    });
</script>
@endsection

{{-- Script untuk konfirmasi gaji --}}