@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#064E3B] tracking-tight">Input Transaksi Produk</h1>
    <p class="text-xs text-gray-400">Kelola penjualan produk dan stok inventaris secara real-time.</p>
</div>

{{-- Notifikasi --}}
@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl text-xs font-bold">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl text-xs font-bold">{{ session('error') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 items-start">
    {{-- Card Form Input --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-2">
        <form action="{{ route('transaksi.store') }}" method="POST" class="p-6">
            @csrf
            
            {{-- Row Identitas Pembeli & Informasi Pesanan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="text-[10px] uppercase text-gray-400 font-extrabold tracking-widest mb-2 block">Nama Pembeli</label>
                    <input type="text" name="nama_pembeli" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-sm font-semibold outline-none focus:border-green-500 transition-all" placeholder="Nama Pelanggan" required>
                </div>
                <div>
                    <label class="text-[10px] uppercase text-gray-400 font-extrabold tracking-widest mb-2 block">Lokasi</label>
                    <input type="text" name="lokasi" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-sm font-semibold outline-none focus:border-green-500 transition-all" placeholder="Contoh: Jakarta / Stand Utama" required>
                </div>
                <div>
                    <label class="text-[10px] uppercase text-gray-400 font-extrabold tracking-widest mb-2 block">No. Telepon</label>
                    <input type="text" name="no_tlp" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-sm font-semibold outline-none focus:border-green-500 transition-all" placeholder="Contoh: 081234567890" required>
                </div>  
                <div>
                    <label class="text-[10px] uppercase text-gray-400 font-extrabold tracking-widest mb-2 block">Tanggal Pesan</label>
                    <input type="date" name="tanggal_pesan" value="{{ date('Y-m-d') }}" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-sm font-semibold outline-none focus:border-green-500 transition-all" required>
                </div>  
                <div>
                    <label class="text-[10px] uppercase text-gray-400 font-extrabold tracking-widest mb-2 block">Jenis Pesanan</label>
                    <select name="jenis_pesanan" id="jenis_pesanan" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-sm font-semibold outline-none focus:border-green-500 transition-all" required>
                        <option value="datang_langsung">Datang Langsung</option>
                        <option value="wa">WhatsApp (WA)</option>
                        <option value="online_shop">Online Shop</option>
                    </select>
                </div>  
                <div id="resi-container" class="hidden">
                    <label class="text-[10px] uppercase text-gray-400 font-extrabold tracking-widest mb-2 block">No. Resi</label>
                    <input type="text" name="no_resi" id="no_resi" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-sm font-semibold outline-none focus:border-green-500 transition-all" placeholder="Masukkan nomor resi pengiriman">
                </div>  
            </div>

            {{-- Container Produk Dinamis --}}
            <div id="item-container" class="space-y-4">
                <div class="item-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-[#F8FAFC] p-4 rounded-2xl border border-gray-50">
                    <div class="md:col-span-7">
                        <label class="text-[10px] uppercase text-gray-400 font-extrabold tracking-widest mb-2 block">Pilih Produk</label>
                        <select name="items[0][id_produk]" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-sm font-semibold outline-none" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produks as $p)
                                <option value="{{ $p->id_produk }}">{{ $p->nama_produk }} (Stok: {{ $p->stok }}) - Rp {{ number_format($p->harga_satuan, 0, ',', '.') }} {{ $p->deskripsi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label class="text-[10px] uppercase text-gray-400 font-extrabold tracking-widest mb-2 block">Qty</label>
                        <input type="number" name="items[0][qty]" min="1" value="1" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-sm font-semibold outline-none" required>
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <span class="text-[9px] font-black text-green-600 uppercase tracking-widest bg-green-50 px-3 py-2 rounded-lg">Utama</span>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi Form --}}
            <div class="mt-6 flex flex-col md:flex-row justify-between gap-4">
                <button type="button" id="add-item" class="text-sm font-bold text-[#064E3B] hover:text-[#065F46] flex items-center gap-2 transition-all">
                    <i class="fas fa-plus-circle"></i> Tambah Produk Lainnya
                </button>
                <button type="submit" class="bg-[#064E3B] text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg hover:bg-[#053F30] transition-all flex items-center gap-2 justify-center">
                    <i class="fas fa-save"></i> Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

    {{-- Card Grafik Custom Sesuai Gambar --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between h-full min-h-[380px]">
        <div>
            <h3 class="text-sm font-extrabold uppercase text-gray-400 tracking-widest mb-1">Proporsi Pesanan</h3>
            <p class="text-xs text-gray-400 mb-4">Grafik kuantitas penjualan berdasarkan jenis pesanan.</p>
        </div>
        <div class="relative flex-1 flex items-center justify-center">
            <canvas id="orderChart" class="max-h-[220px]"></canvas>
        </div>
    </div>
</div>

{{-- Card Tabel Riwayat Berdasarkan Kategori (Tabs) --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden" id="riwayat-section">
    <div class="p-6 border-b border-gray-50 flex flex-col gap-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h3 class="text-lg font-bold text-gray-800">Riwayat Penjualan</h3>
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400 text-xs"></i>
                </span>
                <input type="text" id="trxSearch" class="block w-full pl-10 pr-4 py-2 border border-gray-100 rounded-xl bg-[#F8FAFC] text-xs font-semibold outline-none focus:border-green-500 transition-all" placeholder="Cari kode atau nama...">
            </div>
        </div>

        {{-- Navigasi Tabs --}}
        <div class="flex border-b border-gray-100 gap-2 overflow-x-auto text-xs font-bold">
            <button type="button" onclick="switchTab('all')" id="tab-all" class="tab-btn py-2 px-4 text-green-700 border-b-2 border-[#064E3B] whitespace-nowrap">
                Semua Data ({{ $riwayat->count() }})
            </button>
            <button type="button" onclick="switchTab('datang_langsung')" id="tab-datang_langsung" class="tab-btn py-2 px-4 text-gray-400 hover:text-gray-600 border-b-2 border-transparent whitespace-nowrap">
                <i class="fas fa-store text-gray-400 mr-1"></i> Datang Langsung ({{ $riwayat->where('jenis_pesanan', 'datang_langsung')->count() }})
            </button>
            <button type="button" onclick="switchTab('wa')" id="tab-wa" class="tab-btn py-2 px-4 text-gray-400 hover:text-gray-600 border-b-2 border-transparent whitespace-nowrap">
                <i class="fab fa-whatsapp text-green-500 mr-1"></i> WhatsApp ({{ $riwayat->where('jenis_pesanan', 'wa')->count() }})
            </button>
            <button type="button" onclick="switchTab('online_shop')" id="tab-online_shop" class="tab-btn py-2 px-4 text-gray-400 hover:text-gray-600 border-b-2 border-transparent whitespace-nowrap">
                <i class="fas fa-shopping-bag text-blue-500 mr-1"></i> Online Shop ({{ $riwayat->where('jenis_pesanan', 'online_shop')->count() }})
            </button>
        </div>
    </div>

    {{-- Content Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left" id="trxTable">
            <thead>
                <tr class="bg-[#F1F5F9] text-[#64748B] text-[10px] uppercase font-extrabold tracking-widest">
                    <th class="px-6 py-4">Kode & Tgl Pesan</th>
                    <th class="px-6 py-4">Pembeli & Lokasi</th>
                    <th class="px-6 py-4">Kontak / Resi</th>
                    <th class="px-6 py-4">Jenis Pesanan</th>
                    <th class="px-6 py-4">Detail Barang</th>
                    <th class="px-6 py-4 text-right">Total Bayar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($riwayat as $trx)
                <tr class="hover:bg-gray-50/50 transition trx-row" data-type="{{ $trx->jenis_pesanan }}">
                    <td class="px-6 py-5">
                        <div class="font-bold text-[#065F46]">{{ $trx->kode_transaksi }}</div>
                        <div class="text-[10px] text-gray-500 font-semibold">Pesan: {{ $trx->tanggal_pesan ? \Carbon\Carbon::parse($trx->tanggal_pesan)->format('d M Y') : '-' }}</div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="font-bold text-gray-700">{{ $trx->nama_pembeli ?? 'Umum' }}</div>
                        <div class="text-[10px] text-gray-400 italic"><i class="fas fa-map-marker-alt"></i> {{ $trx->lokasi ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="font-bold text-gray-700">{{ $trx->no_tlp ?? '-' }}</div>
                        @if($trx->no_resi)
                            <div class="text-[10px] text-blue-600 font-semibold mt-1 bg-blue-50 px-2 py-0.5 rounded-md inline-block"><i class="fas fa-truck text-[9px]"></i> {{ $trx->no_resi }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-xs font-bold">
                        @if($trx->jenis_pesanan == 'wa')
                            <span class="bg-green-50 text-green-700 px-2.5 py-1 rounded-full text-[10px] flex items-center w-fit gap-1"><i class="fab fa-whatsapp"></i> WhatsApp</span>
                        @elseif($trx->jenis_pesanan == 'online_shop')
                            <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full text-[10px] flex items-center w-fit gap-1"><i class="fas fa-shopping-bag"></i> Online Shop</span>
                        @else
                            <span class="bg-gray-100 text-gray-700 px-2.5 py-1 rounded-full text-[10px] flex items-center w-fit gap-1"><i class="fas fa-store"></i> Langsung</span>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        @foreach($trx->details as $detail)
                            <span class="inline-block bg-gray-100 text-[10px] px-2 py-1 rounded-md mr-1 mb-1 font-bold">
                                {{ $detail->produk->nama_produk ?? 'Produk Dihapus' }} (x{{ $detail->jumlah }})
                            </span>
                        @endforeach
                    </td>
                    <td class="px-6 py-5 text-right font-black text-green-600">
                        Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr id="no-data-row"><td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">Belum ada transaksi.</td></tr>
                @endforelse
                
                <tr id="tab-empty-row" class="hidden">
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">Tidak ada data transaksi untuk kategori ini.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Load CDN Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 1. Inisialisasi Grafik Pie Terpotong (Sesuai Gambar Contoh)
    const ctx = document.getElementById('orderChart').getContext('2d');
    
    // Perhitungan Total Data & Persentase
    const dataDatangLangsung = {{ $riwayat->where('jenis_pesanan', 'datang_langsung')->count() }};
    const dataWA = {{ $riwayat->where('jenis_pesanan', 'wa')->count() }};
    const dataOnlineShop = {{ $riwayat->where('jenis_pesanan', 'online_shop')->count() }};
    const totalData = dataDatangLangsung + dataWA + dataOnlineShop;

    const orderChart = new Chart(ctx, {
        type: 'pie', // Diubah menjadi bentuk full pie (lingkaran utuh)
        data: {
            labels: ['Datang Langsung', 'WhatsApp', 'Online Shop'],
            datasets: [{
                data: [dataDatangLangsung, dataWA, dataOnlineShop],
                backgroundColor: [
                    '#D9F99D', // Hijau muda kekuningan (Aksen dominan mirip gambar)
                    '#06B6D4', // Biru cyan kustom
                    '#3B82F6'  // Blue primer
                ],
                borderWidth: 0, 
                // Fitur Offset untuk membuat salah satu atau semua bagian terpisah berjarak seperti di gambar
                offset: [10, 10, 10] 
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: { size: 11, weight: 'bold' },
                        padding: 15
                    }
                },
                // Menampilkan label angka persentase langsung di dalam grafik
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            let percentage = totalData > 0 ? ((value / totalData) * 100).toFixed(1) + '%' : '0%';
                            return ` ${context.label}: ${value} trx (${percentage})`;
                        }
                    }
                }
            }
        }
    });

    // 2. Kondisional Input No. Resi
    const jenisPesananSelect = document.getElementById('jenis_pesanan');
    const resiContainer = document.getElementById('resi-container');
    const noResiInput = document.getElementById('no_resi');

    jenisPesananSelect.addEventListener('change', function() {
        if (this.value === 'online_shop') {
            resiContainer.classList.remove('hidden');
            noResiInput.setAttribute('required', 'required');
        } else {
            resiContainer.classList.add('hidden');
            noResiInput.removeAttribute('required');
            noResiInput.value = ''; 
        }
    });

    // 3. Logika Tambah Baris Produk Dinamis
    let itemIndex = 1;
    document.getElementById('add-item').addEventListener('click', function() {
        const container = document.getElementById('item-container');
        const newRow = document.createElement('div');
        newRow.className = "item-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-[#F8FAFC] p-4 rounded-2xl border border-gray-50 mt-4";
        
        newRow.innerHTML = `
            <div class="md:col-span-7">
                <select name="items[${itemIndex}][id_produk]" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-sm font-semibold outline-none" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($produks as $p)
                        <option value="{{ $p->id_produk }}">{{ $p->nama_produk }} (Stok: {{ $p->stok }}) (Rp {{ number_format($p->harga, 0, ',', '.') }}) ({{ $p->deskripsi }})</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <input type="number" name="items[${itemIndex}][qty]" min="1" value="1" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-sm font-semibold outline-none" required>
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="button" class="remove-item text-red-500 hover:text-red-700 text-xs font-bold uppercase tracking-widest bg-red-50 px-3 py-2 rounded-lg">
                    Hapus
                </button>
            </div>
        `;
        container.appendChild(newRow);
        itemIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });

    // 4. Sistem Filter Tabs + Search Terintegrasi
    let currentTab = 'all';

    function switchTab(type) {
        currentTab = type;
        const rows = document.querySelectorAll('.trx-row');
        const searchInput = document.getElementById('trxSearch');
        let filterText = searchInput.value.toLowerCase();
        let visibleCount = 0;

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('text-green-700', 'border-[#064E3B]');
            btn.classList.add('text-gray-400', 'border-transparent');
        });
        
        const activeTab = document.getElementById(`tab-${type}`);
        activeTab.classList.remove('text-gray-400', 'border-transparent');
        activeTab.classList.add('text-green-700', 'border-[#064E3B]');

        rows.forEach(row => {
            let rowType = row.getAttribute('data-type');
            let rowText = row.innerText.toLowerCase();

            let matchTab = (type === 'all' || rowType === type);
            let matchSearch = rowText.includes(filterText);

            if (matchTab && matchSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const emptyRow = document.getElementById('tab-empty-row');
        if (emptyRow) {
            if (visibleCount === 0 && rows.length > 0) {
                emptyRow.classList.remove('hidden');
            } else {
                emptyRow.classList.add('hidden');
            }
        }
    }

    document.getElementById('trxSearch').addEventListener('keyup', function() {
        switchTab(currentTab); 
    });
</script>
@endsection