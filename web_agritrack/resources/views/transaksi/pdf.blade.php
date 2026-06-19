<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #064E3B; padding-bottom: 10px; }
        .header h2 { color: #064E3B; margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; font-size: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #064E3B; color: white; padding: 8px; text-align: left; font-size: 10px; }
        td { border: 0.5pt solid #ddd; padding: 6px; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Baris Total */
        .total-row { background-color: #f9f9f9; font-weight: bold; }
        .grand-total { background-color: #064E3B; color: white; font-weight: bold; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Transaksi Kiwari Farm</h2>
        <p>Periode: {{ \Carbon\Carbon::create()->month((int)request('bulan', now()->month))->translatedFormat('F') }} {{ request('tahun', now()->year) }} | Kategori: {{ strtoupper(request('jenis_pesanan', 'Semua')) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Pembeli</th>
                <th>Jenis</th>
                <th>Detail Barang</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($transaksi as $t)
                @php $grandTotal += $t->total_harga; @endphp
                <tr>
                    <td>{{ $t->kode_transaksi }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($t->tanggal_pesan)->format('d/m/y') }}</td>
                    <td>{{ $t->nama_pembeli }}</td>
                    <td>{{ str_replace('_', ' ', $t->jenis_pesanan) }}</td>
                    <td>
                        @foreach($t->details as $d)
                            {{ $d->produk->nama_produk ?? 'N/A' }} <small>({{ $d->jumlah }}x)</small>
                            @if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td class="text-right">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data transaksi ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="grand-total">
                <td colspan="5" class="text-right">TOTAL PENDAPATAN</td>
                <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>