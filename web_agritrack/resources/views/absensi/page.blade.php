@extends('layouts.app')

@section('content')
{{-- Judul Halaman --}}
<div class="mb-8">
    <h1 class="text-3xl font-bold text-[#064E3B] tracking-tight">Catatan Kehadiran Pegawai</h1>
    <p class="text-sm text-gray-500">Halaman khusus untuk melihat, memantau, dan mengubah data absensi harian Kiwari Farm.</p>
</div>

{{-- Kotak Ringkasan Informasi (Statistik) --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    {{-- Total Absensi --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-green-50 rounded-xl text-green-600">
                <i class="fas fa-users text-lg"></i>
            </div>
            <span class="text-xs font-bold bg-green-100 text-green-700 px-2.5 py-0.5 rounded-full">Semua</span>
        </div>
        <div>
            <p class="text-[11px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">Total Absen Masuk</p>
            <p class="text-2xl font-extrabold text-gray-800 tracking-tight group-hover:text-[#065F46] transition-colors">
                {{ $absensi->count() }} <span class="text-sm font-normal text-gray-400">Kali Absen</span>
            </p>
        </div>
    </div>

    {{-- Kebun Lanud --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                <i class="fas fa-plane-departure text-lg"></i>
            </div>
            <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full">Wilayah 1</span>
        </div>
        <div>
            <p class="text-[11px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">Kehadiran di Kebun Lanud</p>
            <p class="text-2xl font-extrabold text-gray-800 tracking-tight group-hover:text-[#065F46] transition-colors">
                {{ $absensi->where('lokasi', 'pulo')->count() }} <span class="text-sm font-normal text-gray-400">Kegiatan</span>
            </p>
        </div>
    </div>

    {{-- Kebun sindang --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                <i class="fas fa-mountain text-lg"></i>
            </div>
            <span class="text-xs font-bold bg-indigo-100 text-indigo-700 px-2.5 py-0.5 rounded-full">Wilayah 2</span>
        </div>
        <div>
            <p class="text-[11px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">Kehadiran di Kebun Sindang</p>
            <p class="text-2xl font-extrabold text-gray-800 tracking-tight group-hover:text-[#065F46] transition-colors">
                {{ $absensi->where('lokasi', 'sindang')->count() }} <span class="text-sm font-normal text-gray-400">Kegiatan</span>
            </p>
        </div>
    </div>
</div>

{{-- Tabel Data --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    {{-- Bagian Atas Tabel (Pencarian Otomatis & Filter Jumlah Data) --}}
    <div class="p-6 flex flex-col lg:flex-row justify-between items-center gap-4 border-b border-gray-50">
        {{-- Dropdown Pilih Jumlah Data --}}
        <div class="flex items-center gap-3 w-full lg:w-auto">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Tampilkan:</label>
            <select id="dataLengthSelect" class="px-3 py-2 border border-gray-200 rounded-xl text-xs font-semibold bg-[#F8FAFC] focus:ring-2 focus:ring-[#065F46]/20 focus:border-[#065F46] outline-none transition text-gray-700">
                <option value="10" selected>10 Data Terbaru</option>
                <option value="25">25 Data Terbaru</option>
                <option value="50">50 Data Terbaru</option>
                <option value="all">Semua Data</option>
            </select>
        </div>
        
        {{-- Kotak Pencarian Pintar (Live Search) --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400 text-xs"></i>
                </span>
                <input type="text" id="absensiSearch" 
                    class="block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl bg-[#F8FAFC] text-xs font-semibold focus:ring-2 focus:ring-[#065F46]/20 focus:border-[#065F46] outline-none transition placeholder-gray-400 shadow-inner" 
                    placeholder="Cari nama pegawai, lokasi, status, atau kegiatan...">
            </div>

            <button class="bg-[#F8FAFC] text-xs font-bold text-gray-600 px-4 py-2.5 rounded-xl hover:bg-gray-100 border border-gray-200/50 whitespace-nowrap transition flex items-center justify-center gap-2">
                <i class="fas fa-file-export text-gray-400"></i> Download Data (Excel)
            </button>
        </div>
    </div>

    {{-- Notifikasi Sukses / Gagal --}}
    @if(session('success'))
        <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-100 text-green-800 text-sm font-medium rounded-xl flex items-center gap-2">
            <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Kontainer Tabel Utama --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left" id="absensiTable">
            <thead>
                <tr class="bg-[#F1F5F9] text-[#64748B] text-[11px] uppercase font-bold tracking-wider">
                    <th class="px-6 py-4 text-center w-16">No</th>
                    <th class="px-6 py-4">Nama Pegawai</th> {{-- KOLOM BARU --}}
                    <th class="px-6 py-4">Jam Masuk</th>
                    <th class="px-6 py-4">Jam Pulang</th>
                    <th class="px-6 py-4">Lokasi Kerja</th>
                    <th class="px-6 py-4 text-center">Foto Bukti</th>
                    <th class="px-6 py-4">Nama Kegiatan</th>
                    <th class="px-6 py-4 text-center">Status Kehadiran</th>
                    <th class="px-6 py-4 text-center">Lembur</th>
                    <th class="px-6 py-4 text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($absensi as $row)
                <tr class="hover:bg-gray-50/50 transition row-item">
                    <td class="px-6 py-5 text-center text-gray-400 font-medium row-number">{{ $loop->iteration }}</td>
                    
                    {{-- MENAMPILKAN DATA USER / PEGAWAI --}}
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-700 font-bold flex items-center justify-center text-xs uppercase shadow-sm">
                                {{ isset($row->user->nama) ? substr($row->user->nama, 0, 2) : (isset($row->nama) ? substr($row->nama, 0, 2) : '??') }}
                            </div>
                            <div>
                                <p class="text-gray-800 font-semibold tracking-tight">
                                    {{ $row->user->nama ?? ($row->nama ?? 'Pegawai Tidak Dikenal') }}
                                </p>
                                <p class="text-[10px] text-gray-400">ID Pegawai: #{{ $row->user_id ?? ($row->id_user ?? '-') }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-5 text-gray-700 font-mono text-xs">{{ $row->tanggal_datang }}</td>
                    <td class="px-6 py-5 text-gray-600 font-mono text-xs">
                        {{ $row->tanggal_pulang ?? 'Belum Pulang' }}
                    </td>
                    <td class="px-6 py-5">
                        @if($row->lokasi == 'pulo')
                            <span class="text-blue-600 font-semibold text-xs bg-blue-50 px-2 py-1 rounded-md"><i class="fas fa-plane mr-1 text-[10px]"></i> PULO</span>
                        @else
                            <span class="text-indigo-600 font-semibold text-xs bg-indigo-50 px-2 py-1 rounded-md"><i class="fas fa-seedling mr-1 text-[10px]"></i> SINDANG</span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-center">
                        @if($row->image)
                            <div class="relative inline-block">
                                <img src="{{ asset('storage/' . $row->image) }}" 
                                     onclick="openPhotoModal(this.src)"
                                     alt="Foto Selfie" 
                                     class="w-10 h-10 rounded-xl object-cover border-2 border-white shadow-md hover:scale-105 hover:border-[#065F46] transition-all cursor-zoom-in">
                            </div>
                        @else
                            <span class="text-gray-400 text-xs italic bg-gray-50 px-2 py-1 rounded-md">Tidak Ada Foto</span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-gray-600 max-w-[180px] truncate" title="{{ $row->kegiatan }}">
                        {{ $row->kegiatan ?? 'Tidak ada keterangan' }}
                    </td>
                    <td class="px-6 py-5 text-center">
                        @if($row->status == 'absen_pulang' || $row->status == 'selesai')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">
                                Hadir & Pulang
                            </span>
                        @elseif($row->status == 'absen_datang')
                            <span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">
                                Sedang Bekerja
                            </span>
                        @elseif($row->status == 'lembur')
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">
                                Lembur
                            </span>
                        {{-- @elseif($row->status == 'selesai')
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">
                                Selesai
                            </span> --}}
                        @else
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">
                                Tidak Hadir
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-center font-bold text-gray-700 text-xs">
                        {{ $row->total_lembur }} Jam
                    </td>
                    <td class="px-6 py-5 text-center">
                        <button type="button" 
                            onclick="openEditModal({{ json_encode($row) }})"
                            class="bg-amber-50 hover:bg-amber-500 text-amber-700 hover:text-white px-3 py-1.5 rounded-xl border border-amber-200 hover:border-amber-500 transition-colors text-xs font-bold flex items-center gap-1 mx-auto shadow-sm">
                            <i class="fas fa-edit text-[10px]"></i> Ubah
                        </button>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="10" class="px-6 py-12 text-center text-gray-400 italic">Belum ada riwayat daftar hadir pegawai untuk hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Info Hasil Pencarian & Jumlah Data di Bawah Tabel --}}
    <div class="p-6 border-t border-gray-50 bg-[#F8FAFC]">
        <p id="tableInfoText" class="text-xs text-gray-500 font-medium">
            Menampilkan data...
        </p>
    </div>
</div>

{{-- POPUP 1: LIHAT FOTO BESAR --}}
<div id="photoModal" class="fixed inset-0 z-[999] hidden flex items-center justify-center bg-black/90 p-4 transition-opacity duration-300">
    <button onclick="closePhotoModal()" class="absolute top-6 right-6 text-white text-3xl hover:text-gray-300 transition">
        <i class="fas fa-times"></i>
    </button>
    <img id="modalImg" src="" class="max-w-full max-h-[90vh] rounded-2xl shadow-2xl border-4 border-white/10">
</div>

{{-- POPUP 2: FORM EDIT ABSENSI --}}
<div id="editModal" class="fixed inset-0 z-[998] hidden flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden border border-gray-100">
        <div class="bg-[#064E3B] px-6 py-4 flex justify-between items-center">
            <div>
                <h3 class="text-white font-bold text-lg"><i class="fas fa-edit mr-2"></i>Ubah Data Absensi</h3>
                <p id="edit_modal_subtitle" class="text-green-200 text-xs mt-0.5">Silakan sesuaikan data kehadiran di bawah ini.</p>
            </div>
            <button onclick="closeEditModal()" class="text-white/80 hover:text-white text-xl"><i class="fas fa-times"></i></button>
        </div>
        
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-4 max-h-[65vh] overflow-y-auto">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jam Masuk Kerja</label>
                    <input type="text" name="tanggal_datang" id="edit_tanggal_datang" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#065F46]/20 focus:border-[#065F46] outline-none font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jam Pulang Kerja</label>
                    <input type="text" name="tanggal_pulang" id="edit_tanggal_pulang"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#065F46]/20 focus:border-[#065F46] outline-none font-mono">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Lokasi Kebun</label>
                        <select name="lokasi" id="edit_lokasi" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#065F46]/20 focus:border-[#065F46] outline-none bg-white font-medium text-gray-700">
                            <option value="pulo">Pulo</option>
                            <option value="sindang">Sindang</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kondisi / Status</label>
                        <select name="status" id="edit_status" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#065F46]/20 focus:border-[#065F46] outline-none bg-white font-medium text-gray-700">
                            <option value="absen_datang">Sedang Bekerja</option>
                            <option value="absen_pulang">Sudah Pulang</option>
                            <option value="lembur_datang">Kerja Lembur</option>
                            <option value="tidak_hadir">Tidak Hadir</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Durasi Kerja Lembur (Jam)</label>
                    <input type="number" name="total_lembur" id="edit_total_lembur" min="0" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#065F46]/20 focus:border-[#065F46] outline-none font-bold text-gray-700">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Keterangan Aktivitas Pegawai</label>
                    <textarea name="kegiatan" id="edit_kegiatan" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#065F46]/20 focus:border-[#065F46] outline-none"></textarea>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-2 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 bg-gray-200 text-gray-700 text-xs font-bold rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-[#065F46] text-white text-xs font-bold rounded-xl"><i class="fas fa-save mr-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPhotoModal(src) {
        const modal = document.getElementById('photoModal');
        const modalImg = document.getElementById('modalImg');
        modalImg.src = src;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePhotoModal() {
        document.getElementById('photoModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openEditModal(data) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        
        // Sesuaikan endpoint id absensi Anda
        form.action = `/absensi/${data.id_absensi ?? data.id}`;
        
        // Tampilkan info nama pegawai di sub-judul popup modal edit
        let employeeName = data.user ? data.user.nama : (data.nama ? data.nama : 'Pegawai');
        document.getElementById('edit_modal_subtitle').innerText = "Mengubah data absensi milik: " + employeeName;
        
        document.getElementById('edit_tanggal_datang').value = data.tanggal_datang;
        document.getElementById('edit_tanggal_pulang').value = data.tanggal_pulang ? data.tanggal_pulang : '';
        document.getElementById('edit_lokasi').value = data.lokasi;
        document.getElementById('edit_status').value = data.status;
        document.getElementById('edit_total_lembur').value = data.total_lembur;
        document.getElementById('edit_kegiatan').value = data.kegiatan ? data.kegiatan : '';

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('photoModal').addEventListener('click', function(e) { if (e.target === this) closePhotoModal(); });
    document.getElementById('editModal').addEventListener('click', function(e) { if (e.target === this) closeEditModal(); });

    // ===============================================
    // FITUR LIVE SEARCH (MENDUKUNG FILTER NAMA USER) & LIMIT DATA
    // ===============================================
    const searchInput = document.getElementById('absensiSearch');
    const lengthSelect = document.getElementById('dataLengthSelect');
    const tableRows = document.querySelectorAll('#absensiTable .row-item');
    const infoText = document.getElementById('tableInfoText');

    function updateTableDisplay() {
        let filter = searchInput.value.toLowerCase().trim();
        let limit = lengthSelect.value === 'all' ? tableRows.length : parseInt(lengthSelect.value);
        
        let visibleCount = 0;   
        let displayedCount = 0; 

        tableRows.forEach((row) => {
            let text = row.innerText.toLowerCase();
            let matchesSearch = text.includes(filter);

            if (matchesSearch) {
                visibleCount++;
                if (displayedCount < limit) {
                    row.style.display = "";
                    displayedCount++;
                } else {
                    row.style.display = "none";
                }
            } else {
                row.style.display = "none";
            }
        });

        if (visibleCount === 0) {
            infoText.innerHTML = "Menampilkan <span class='font-bold text-red-500'>0</span> data dari total <span class='font-bold'>" + tableRows.length + "</span> catatan.";
        } else {
            infoText.innerHTML = "Menampilkan <span class='font-bold text-[#065F46]'>1 sampai " + displayedCount + "</span> dari <span class='font-bold'>" + visibleCount + "</span> data ditemukan (Total data: " + tableRows.length + ")";
        }

        let emptyMsg = document.getElementById('noResultsMsg');
        if (visibleCount === 0 && filter !== "") {
            if (!emptyMsg) {
                let tbody = document.querySelector('#absensiTable tbody');
                let newRow = tbody.insertRow();
                newRow.id = "noResultsMsg";
                let cell = newRow.insertCell(0);
                cell.colSpan = 10; // Sesuaikan jumlah kolom (sekarang 10)
                cell.className = "px-6 py-12 text-center text-gray-400 italic text-xs bg-gray-50 font-medium";
                cell.innerHTML = "<i class='fas fa-search-minus text-gray-300 text-lg block mb-2'></i> Maaf, data pegawai dengan kata kunci \"" + searchInput.value + "\" tidak ditemukan.";
            }
        } else if (emptyMsg) {
            emptyMsg.remove();
        }
    }

    searchInput.addEventListener('keyup', updateTableDisplay);
    lengthSelect.addEventListener('change', updateTableDisplay);
    document.addEventListener("DOMContentLoaded", updateTableDisplay);
</script>
@endsection