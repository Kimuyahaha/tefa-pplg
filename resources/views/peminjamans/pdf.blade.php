<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Peminjaman</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
            border-bottom: 2px solid #111827;
            padding-bottom: 12px;
        }

        .header h1 {
            margin: 0;
            font-size: 17px;
            text-transform: uppercase;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 11px;
        }

        .meta {
            margin-bottom: 10px;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #e5e7eb;
            font-weight: bold;
            text-align: left;
        }

        th, td {
            border: 1px solid #9ca3af;
            padding: 6px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .status-dipinjam {
            color: #92400e;
            font-weight: bold;
        }

        .status-dikembalikan {
            color: #166534;
            font-weight: bold;
        }

        .status-terlambat {
            color: #b91c1c;
            font-weight: bold;
        }

        .footer {
            margin-top: 26px;
            width: 100%;
        }

        .signature {
            width: 230px;
            float: right;
            text-align: center;
        }

        .signature-space {
            height: 55px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Data Peminjaman</h1>
        <p>Aplikasi Manajemen Peminjaman Peralatan Lab TEFA PPLG</p>
    </div>

    <div class="meta">
        <strong>Tanggal Cetak:</strong> {{ $tanggalCetak }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="30">No</th>
                <th>Peminjam</th>
                <th>Kelas</th>
                <th>Peralatan</th>
                <th class="text-center">Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Harus Kembali</th>
                <th>Dikembalikan</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($peminjamans as $peminjaman)
                @php
                    $terlambat = $peminjaman->status === 'dipinjam'
                        && $peminjaman->tanggal_kembali
                        && $peminjaman->tanggal_kembali < now()->toDateString();
                @endphp

                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $peminjaman->pengguna->nama }}</td>
                    <td>{{ $peminjaman->pengguna->kelas }}</td>
                    <td>{{ $peminjaman->peralatan->nama_peralatan }}</td>
                    <td class="text-center">{{ $peminjaman->jumlah_pinjam }}</td>
                    <td>{{ $peminjaman->tanggal_pinjam }}</td>
                    <td>{{ $peminjaman->tanggal_kembali ?? '-' }}</td>
                    <td>{{ $peminjaman->tanggal_dikembalikan ?? '-' }}</td>
                    <td>
                        @if($peminjaman->status === 'dikembalikan')
                            <span class="status-dikembalikan">Dikembalikan</span>
                        @elseif($terlambat)
                            <span class="status-terlambat">Terlambat</span>
                        @else
                            <span class="status-dipinjam">Dipinjam</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Data peminjaman belum tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Petugas Lab TEFA PPLG</p>
            <div class="signature-space"></div>
            <p><strong>Admin TEFA</strong></p>
        </div>
    </div>

</body>
</html>