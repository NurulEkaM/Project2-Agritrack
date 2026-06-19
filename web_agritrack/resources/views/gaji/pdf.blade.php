<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #e5e7eb; font-weight: bold; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <h2 style="margin-bottom: 5px;">LAPORAN DATA GAJI PEGAWAI</h2>
        <p style="margin-top: 0; font-weight: bold;">Pegawai: {{ $namaPegawai }} | Periode: {{ \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Pegawai</th>
                <th>Total Absen</th>
                <th>Total Lembur (Jam)</th>
                <th>Nominal Lembur</th>
                <th>Total Gaji (Diterima)</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($d->tanggal)->format('d/m/y') }}</td>
                <td>{{ $d->nama_pegawai }}</td>
                <td>{{ $d->total_absen }} Hari</td>
                <td>{{ $d->total_lembur }}</td>
                <td>Rp {{ number_format($d->total_lembur * 10000, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($d->total_gaji, 0, ',', '.') }}</td>
                <td>{{ strtoupper($d->status) }}</td>
                <td class="text-left">{{ $d->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f3f4f6; font-weight: bold;">
                <td colspan="4" style="text-align: right; padding-right: 10px;">TOTAL KESELURUHAN</td>
                <td>{{ $totalLemburKeseluruhan }} Jam</td>
                <td>Rp {{ number_format($totalPendapatanLembur, 0, ',', '.') }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>