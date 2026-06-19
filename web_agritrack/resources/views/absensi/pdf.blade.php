<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 4px; text-align: center; }
        th { background: #e5e7eb; font-weight: bold; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <h2 style="margin-bottom: 5px;">ABSENSI KARYAWAN {{ $namaPegawai }}</h2>
        <p style="margin-top: 0;">Pegawai: {{ $namaPegawai }} | Periode: {{ \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Tanggal</th>
                <th colspan="2">Jam Kerja</th>
                <th rowspan="2">Lokasi</th>
                <th rowspan="2" class="text-left">Kegiatan</th>
                {{-- <th colspan="2">Jam Lembur</th> --}}
                <th rowspan="2">status</th>
                <th rowspan="2">Lembur</th>
                <th rowspan="2">Jumlah Lembur</th>
            </tr>
            <tr>
                <th>IN</th>
                <th>OUT</th>
   
            </tr>
        </thead>
        <tbody>
            @foreach($data as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($d->tanggal_datang)->format('d/m/y') }}</td>
                <td>{{ \Carbon\Carbon::parse($d->tanggal_datang)->format('H:i') }}</td>
                <td>{{ $d->tanggal_pulang ? \Carbon\Carbon::parse($d->tanggal_pulang)->format('H:i') : '-' }}</td>
                <td>{{ strtoupper($d->lokasi) }}</td>
                <td class="text-left">{{ $d->kegiatan }}</td>
                <td>{{ $d->status }}</td>
                <td>{{ $d->total_lembur }}</td>
                <td>Rp {{ number_format($d->total_lembur * 10000, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f3f4f6; font-weight: bold;">
                <td colspan="7" style="text-align: right; padding-right: 10px;">TOTAL LEMBUR </td>
                <td>{{ $totalLemburKeseluruhan }} Jam</td>
                <td>Rp {{ number_format($totalPendapatanLembur, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>