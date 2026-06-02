<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Peralatan</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #111827;
            padding-bottom: 12px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 12px;
        }

        .meta {
            margin-bottom: 12px;
            font-size: 11px;
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
            padding: 8px;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            width: 100%;
        }

        .signature {
            width: 230px;
            float: right;
            text-align: center;
        }

        .signature-space {
            height: 60px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Data Peralatan</h1>
        <p>Aplikasi Manajemen Peminjaman Peralatan Lab TEFA PPLG</p>
    </div>

    <div class="meta">
        <strong>Tanggal Cetak:</strong> {{ $tanggalCetak }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="40">No</th>
                <th>Nama Peralatan</th>
                <th>Kategori</th>
                <th class="text-center">Jumlah Stok</th>
                <th>Kondisi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($peralatans as $peralatan)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $peralatan->nama_peralatan }}</td>
                    <td>{{ $peralatan->kategori }}</td>
                    <td class="text-center">{{ $peralatan->jumlah_stok }}</td>
                    <td>{{ $peralatan->kondisi }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Data peralatan belum tersedia.</td>
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