<!DOCTYPE html>
<html>
<head>
    <title>Laporan Cashflow Kiwari Farm</title>
    <style>
    @page { margin: 1cm; }
    /* Gunakan font standar PDF untuk meminimalisir error render */
    body { font-family: sans-serif; font-size: 9px; color: #333; }
    .header-title { 
        background-color: #064E3B; 
        color: #ffffff; 
        text-align: center; 
        padding: 10px; 
        font-size: 14px; 
    }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 0.5pt solid #000; padding: 4px; }
    th { background-color: #fbbf24; }
    .text-right { text-align: right; }
    .text-red { color: #ff0000; }
    .text-blue { color: #0000ff; }
</style>
</head>
<body>

    <div class="header-title">
        CASHFLOW KIWARI FARM <br> 
        <span style="font-size: 11px; font-weight: normal;">Laporan Keuangan Operasional - {{ $tanggal_cetak }}</span>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th rowspan="2" class="col-hari">Hari</th>
                <th rowspan="2" class="col-tgl">Tgl</th>
                <th rowspan="2">Keterangan Transaksi</th>
                <th rowspan="2" class="col-nominal">Debit<br>(Pemasukan)</th>
                <th colspan="2">Kredit (Pengeluaran)</th>
            </tr>
            <tr>
                <th class="col-nominal">Tetap</th>
                <th class="col-nominal">Tidak Tetap</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $currentDate = null; 
                $totalTetap = 0;
                $totalTidakTetap = 0;
            @endphp

            @foreach($cashflow as $item)
            @php 
                $tanggal = \Carbon\Carbon::parse($item->tanggal);
                $isNewDate = $currentDate !== $item->tanggal;
                if($isNewDate) $currentDate = $item->tanggal;

                // Logika pemisahan Tetap/Tidak Tetap 
                // Jika Anda belum punya kolom 'jenis_biaya', kita buat asumsi sederhana:
                // Misalnya: Gaji & Listrik dianggap Tetap, sisanya Tidak Tetap.
                $isTetap = false;
                if(str_contains(strtolower($item->nama), 'gaji') || str_contains(strtolower($item->nama), 'listrik')) {
                    $isTetap = true;
                    if($item->kategori == 'PENGELUARAN') $totalTetap += $item->nominal;
                } else {
                    if($item->kategori == 'PENGELUARAN') $totalTidakTetap += $item->nominal;
                }
            @endphp
            <tr>
                <td class="text-center">{{ $isNewDate ? strtoupper($tanggal->translatedFormat('l')) : '' }}</td>
                <td class="text-center">{{ $isNewDate ? $tanggal->format('j') : '' }}</td>
                
                <td style="text-transform: capitalize;">{{ $item->nama }}</td>
                
                <td class="text-right">
                    @if($item->kategori == 'PEMASUKAN')
                        <span class="text-blue">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                    @endif
                </td>

                <td class="text-right">
                    @if($item->kategori == 'PENGELUARAN' && $isTetap)
                        <span class="text-red">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                    @endif
                </td>

                <td class="text-right">
                    @if($item->kategori == 'PENGELUARAN' && !$isTetap)
                        <span class="text-red">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-total">
                <td colspan="3" class="text-right">TOTAL PER KATEGORI</td>
                <td class="text-right text-blue">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                <td class="text-right text-red">Rp {{ number_format($totalTetap, 0, ',', '.') }}</td>
                <td class="text-right text-red">Rp {{ number_format($totalTidakTetap, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right" style="background: #f8fafc; font-weight: bold;">TOTAL PENGELUARAN (KREDIT)</td>
                <td colspan="2" class="text-center text-red" style="font-weight: bold; font-size: 11px;">
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </td>
            </tr>
            <tr style="background-color: #064E3B; color: white;">
                <td colspan="3" class="text-right" style="font-weight: bold;">SALDO BERSIH (NET BALANCE)</td>
                <td colspan="3" class="text-center" style="font-weight: bold; font-size: 12px;">
                    Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 20px; font-style: italic; color: #666; font-size: 9px;">
        * Pengeluaran Tetap mencakup biaya rutin seperti Gaji dan Listrik.
    </div>

</body>
</html>