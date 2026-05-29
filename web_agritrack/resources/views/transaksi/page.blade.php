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

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <form action="{{ route('transaksi.store') }}" method="POST" class="p-6">
        @csrf
        
        {{-- Row Identitas Pembeli --}}
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
        </div>

        <div id="item-container" class="space-y-4">
            {{-- Baris Produk --}}
            <div class="item-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-[#F8FAFC] p-4 rounded-2xl border border-gray-50">
                <div class="md:col-span-7">
                    <label class="text-[10px] uppercase text-gray-400 font-extrabold tracking-widest mb-2 block">Pilih Produk</label>
                    <select name="items[0][id_produk]" class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-sm font-semibold outline-none" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($produks as $p)
                            <option value="{{ $p->id_produk }}">{{ $p->nama_produk }} (Stok: {{ $p->stok }}) - Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</option>
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

        {{-- Tombol Aksi --}}
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

{{-- Tabel Riwayat --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
        <h3 class="text-lg font-bold text-gray-800">Riwayat Penjualan</h3>
        <div class="relative w-full md:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="fas fa-search text-gray-400 text-xs"></i>
            </span>
            <input type="text" id="trxSearch" class="block w-full pl-10 pr-4 py-2 border border-gray-100 rounded-xl bg-[#F8FAFC] text-xs font-semibold outline-none" placeholder="Cari kode atau nama...">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left" id="trxTable">
            <thead>
                <tr class="bg-[#F1F5F9] text-[#64748B] text-[10px] uppercase font-extrabold tracking-widest">
                    <th class="px-6 py-4">Kode & Waktu</th>
                    <th class="px-6 py-4">Pembeli & Lokasi</th>
                    <th class="px-6 py-4">No. Tlp</th>
                    <th class="px-6 py-4">Detail Barang</th>
                    <th class="px-6 py-4 text-right">Total Bayar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($riwayat as $trx)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-5">
                        <div class="font-bold text-[#065F46]">{{ $trx->kode_transaksi }}</div>
                        <div class="text-[10px] text-gray-400">{{ $trx->created_at->format('d M Y, H:i') }}</div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="font-bold text-gray-700">{{ $trx->nama_pembeli ?? 'Umum' }}</div>
                        <div class="text-[10px] text-gray-400 italic"><i class="fas fa-map-marker-alt"></i> {{ $trx->lokasi ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="font-bold text-gray-700">{{ $trx->no_tlp ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-5">
                        @foreach($trx->details as $detail)
                            <span class="inline-block bg-gray-100 text-[10px] px-2 py-1 rounded-md mr-1 mb-1 font-bold">
                                {{ $detail->produk->nama_produk }} (x{{ $detail->jumlah }})
                            </span>
                        @endforeach
                    </td>
                    <td class="px-6 py-5 text-right font-black text-green-600">
                        Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
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
                        <option value="{{ $p->id_produk }}">{{ $p->nama_produk }} (Stok: {{ $p->stok }})</option>
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

    // Fitur Search Sederhana
    document.getElementById('trxSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#trxTable tbody tr');
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection