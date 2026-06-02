<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard Lab TEFA PPLG
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Selamat datang, {{ auth()->user()->nama }}
            </p>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(auth()->user()->role === 'admin')

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
                        <h3 class="mt-3 text-4xl font-bold text-gray-900">{{ $totalPengguna }}</h3>
                        <p class="text-xs text-gray-400 mt-2">Akun murid terdaftar</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <p class="text-sm font-medium text-gray-500">Total Peralatan</p>
                        <h3 class="mt-3 text-4xl font-bold text-gray-900">{{ $totalPeralatan }}</h3>
                        <p class="text-xs text-gray-400 mt-2">Jenis barang tersedia</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <p class="text-sm font-medium text-gray-500">Peminjaman Aktif</p>
                        <h3 class="mt-3 text-4xl font-bold text-yellow-600">{{ $totalDipinjam }}</h3>
                        <p class="text-xs text-gray-400 mt-2">Belum dikembalikan</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <p class="text-sm font-medium text-gray-500">Terlambat</p>
                        <h3 class="mt-3 text-4xl font-bold text-red-600">{{ $totalTerlambat }}</h3>
                        <p class="text-xs text-gray-400 mt-2">Melewati tanggal kembali</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                        <h3 class="mt-3 text-3xl font-bold text-gray-900">{{ $totalPeminjaman }}</h3>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <p class="text-sm font-medium text-gray-500">Dikembalikan</p>
                        <h3 class="mt-3 text-3xl font-bold text-green-600">{{ $totalDikembalikan }}</h3>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <p class="text-sm font-medium text-gray-500">Total Stok</p>
                        <h3 class="mt-3 text-3xl font-bold text-gray-900">{{ $totalStok }}</h3>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <p class="text-sm font-medium text-gray-500">Peralatan Terfavorit</p>
                        <h3 class="mt-3 text-lg font-bold text-gray-900">
                            {{ $peralatanTerfavorit?->peralatan?->nama_peralatan ?? '-' }}
                        </h3>
                        <p class="text-xs text-gray-400 mt-2">
                            {{ $peralatanTerfavorit?->total ?? 0 }} kali dipinjam
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-semibold text-gray-800">Grafik Peminjaman Bulanan</h3>
                                <p class="text-sm text-gray-500">Jumlah transaksi peminjaman tahun ini</p>
                            </div>
                        </div>

                        <div class="h-72">
                            <canvas id="peminjamanChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Ringkasan Status</h3>

                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Dipinjam</span>
                                    <span class="font-semibold">{{ $totalDipinjam }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $totalPeminjaman > 0 ? ($totalDipinjam / $totalPeminjaman) * 100 : 0 }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Dikembalikan</span>
                                    <span class="font-semibold">{{ $totalDikembalikan }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $totalPeminjaman > 0 ? ($totalDikembalikan / $totalPeminjaman) * 100 : 0 }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Terlambat</span>
                                    <span class="font-semibold">{{ $totalTerlambat }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ $totalPeminjaman > 0 ? ($totalTerlambat / $totalPeminjaman) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="font-semibold text-gray-800">Peralatan Paling Sering Dipinjam</h3>
                        </div>

                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left">Peralatan</th>
                                    <th class="px-6 py-4 text-center">Total Dipinjam</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($topPeralatans as $item)
                                    <tr>
                                        <td class="px-6 py-4 font-medium">
                                            {{ $item->peralatan->nama_peralatan }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            {{ $item->total }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-8 text-center text-gray-500">
                                            Belum ada data.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="font-semibold text-gray-800">Peminjam Teraktif</h3>
                        </div>

                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left">Peminjam</th>
                                    <th class="px-6 py-4 text-left">Kelas</th>
                                    <th class="px-6 py-4 text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($topPeminjams as $item)
                                    <tr>
                                        <td class="px-6 py-4 font-medium">
                                            {{ $item->pengguna->nama }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $item->pengguna->kelas }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            {{ $item->total }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                            Belum ada data.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="font-semibold text-gray-800">Peminjaman Terbaru</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left">Peminjam</th>
                                    <th class="px-6 py-4 text-left">Peralatan</th>
                                    <th class="px-6 py-4 text-center">Jumlah</th>
                                    <th class="px-6 py-4 text-left">Tanggal Pinjam</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @forelse($peminjamansTerbaru as $peminjaman)
                                    @php
                                        $terlambat = $peminjaman->status === 'dipinjam'
                                            && $peminjaman->tanggal_kembali
                                            && $peminjaman->tanggal_kembali < now()->toDateString();
                                    @endphp

                                    <tr>
                                        <td class="px-6 py-4 font-medium">
                                            {{ $peminjaman->pengguna->nama }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ $peminjaman->peralatan->nama_peralatan }}
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            {{ $peminjaman->jumlah_pinjam }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ $peminjaman->tanggal_pinjam }}
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @if($peminjaman->status === 'dikembalikan')
                                                <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold">
                                                    Dikembalikan
                                                </span>
                                            @elseif($terlambat)
                                                <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-semibold">
                                                    Terlambat
                                                </span>
                                            @else
                                                <span class="px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 text-xs font-semibold">
                                                    Dipinjam
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            Belum ada peminjaman.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="font-semibold text-gray-800">Riwayat Pengembalian Terbaru</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left">Peminjam</th>
                                    <th class="px-6 py-4 text-left">Peralatan</th>
                                    <th class="px-6 py-4 text-center">Jumlah</th>
                                    <th class="px-6 py-4 text-left">Tanggal Pinjam</th>
                                    <th class="px-6 py-4 text-left">Tanggal Dikembalikan</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @forelse($riwayatPengembalian as $peminjaman)
                                    <tr>
                                        <td class="px-6 py-4 font-medium">
                                            {{ $peminjaman->pengguna->nama }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ $peminjaman->peralatan->nama_peralatan }}
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            {{ $peminjaman->jumlah_pinjam }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ $peminjaman->tanggal_pinjam }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ $peminjaman->tanggal_dikembalikan ?? '-' }}
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold">
                                                Dikembalikan
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                            Belum ada data pengembalian.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            @else

                @php
                    $statusPengembalian = '-';
                    $statusClass = 'text-gray-900';

                    if ($peminjamanAktif && $peminjamanAktif->tanggal_kembali) {
                        $tanggalKembali = \Carbon\Carbon::parse($peminjamanAktif->tanggal_kembali);
                        $hari = today()->diffInDays($tanggalKembali, false);

                        if ($hari < 0) {
                            $statusPengembalian = 'Terlambat ' . abs($hari) . ' hari';
                            $statusClass = 'text-red-600';
                        } elseif ($hari == 0) {
                            $statusPengembalian = 'Hari ini';
                            $statusClass = 'text-yellow-600';
                        } else {
                            $statusPengembalian = $hari . ' hari lagi';
                            $statusClass = 'text-green-600';
                        }
                    }
                @endphp

                @if($terlambatSaya > 0)
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                        Anda memiliki peminjaman yang melewati tanggal pengembalian.
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <p class="text-sm font-medium text-gray-500">Sedang Dipinjam</p>
                        <h3 class="mt-3 text-4xl font-bold text-yellow-600">{{ $dipinjamSaya }}</h3>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <p class="text-sm font-medium text-gray-500">Dikembalikan</p>
                        <h3 class="mt-3 text-4xl font-bold text-green-600">{{ $dikembalikanSaya }}</h3>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <p class="text-sm font-medium text-gray-500">Batas Pengembalian</p>
                        <h3 class="mt-3 text-2xl font-bold {{ $statusClass }}">{{ $statusPengembalian }}</h3>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <p class="text-sm font-medium text-gray-500">Total Riwayat</p>
                        <h3 class="mt-3 text-4xl font-bold text-gray-900">{{ $peminjamanSaya }}</h3>
                    </div>
                </div>

                @if($peminjamanAktif)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Peminjaman Aktif</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Peralatan</p>
                                <p class="font-semibold mt-1">{{ $peminjamanAktif->peralatan->nama_peralatan }}</p>
                            </div>

                            <div>
                                <p class="text-gray-500">Jumlah</p>
                                <p class="font-semibold mt-1">{{ $peminjamanAktif->jumlah_pinjam }}</p>
                            </div>

                            <div>
                                <p class="text-gray-500">Tanggal Pinjam</p>
                                <p class="font-semibold mt-1">{{ $peminjamanAktif->tanggal_pinjam }}</p>
                            </div>

                            <div>
                                <p class="text-gray-500">Tanggal Kembali</p>
                                <p class="font-semibold mt-1">{{ $peminjamanAktif->tanggal_kembali ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
                        <h3 class="font-semibold text-gray-800">Riwayat Peminjaman Saya</h3>

                        <a href="{{ route('peminjamans.saya') }}" class="text-blue-600 text-sm font-semibold">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left">Peralatan</th>
                                    <th class="px-6 py-4 text-center">Jumlah</th>
                                    <th class="px-6 py-4 text-left">Tanggal Pinjam</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @forelse($riwayatSaya as $peminjaman)
                                    @php
                                        $terlambat = $peminjaman->status === 'dipinjam'
                                            && $peminjaman->tanggal_kembali
                                            && $peminjaman->tanggal_kembali < now()->toDateString();
                                    @endphp

                                    <tr>
                                        <td class="px-6 py-4 font-medium">
                                            {{ $peminjaman->peralatan->nama_peralatan }}
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            {{ $peminjaman->jumlah_pinjam }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ $peminjaman->tanggal_pinjam }}
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @if($peminjaman->status === 'dikembalikan')
                                                <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold">
                                                    Dikembalikan
                                                </span>
                                            @elseif($terlambat)
                                                <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-semibold">
                                                    Terlambat
                                                </span>
                                            @else
                                                <span class="px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 text-xs font-semibold">
                                                    Dipinjam
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                            Kamu belum memiliki peminjaman.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif

        </div>
    </div>

    @if(auth()->user()->role === 'admin')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('peminjamanChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: @json($chartData),
                        borderWidth: 1,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
    @endif
</x-app-layout>