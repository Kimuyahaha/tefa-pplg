<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">
                    Data Peminjaman
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola transaksi peminjaman peralatan Lab TEFA PPLG
                </p>
            </div>

            @if(auth()->user()->role === 'admin')
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('peminjamans.export-pdf') }}"
                        class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-sm text-sm font-semibold">
                        Export PDF
                    </a>

                    <a href="{{ route('peminjamans.export') }}"
                        class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-sm text-sm font-semibold">
                        Export Excel
                    </a>

                    <a href="{{ route('peminjamans.create') }}"
                        class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-sm text-sm font-semibold">
                        + Tambah Peminjaman
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-5">
                <form id="filterPeminjaman" method="GET" action="{{ route('peminjamans.index') }}"
                    class="grid grid-cols-1 md:grid-cols-12 gap-3">

                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Cari Data
                        </label>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nama, kelas, jurusan, atau peralatan..."
                            class="w-full border-gray-300 rounded-lg shadow-sm js-auto-search">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status
                        </label>
                        <select
                            name="status"
                            class="w-full border-gray-300 rounded-lg shadow-sm js-auto-submit">

                            <option value="">Semua Status</option>
                            <option value="dipinjam" @selected(request('status') == 'dipinjam')>
                                Dipinjam
                            </option>
                            <option value="dikembalikan" @selected(request('status') == 'dikembalikan')>
                                Dikembalikan
                            </option>
                            <option value="terlambat" @selected(request('status') == 'terlambat')>
                                Terlambat
                            </option>
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Waktu
                        </label>
                        <select
                            name="waktu"
                            class="w-full border-gray-300 rounded-lg shadow-sm js-auto-submit">

                            <option value="">Semua Waktu</option>
                            <option value="hari_ini" @selected(request('waktu') == 'hari_ini')>
                                Hari Ini
                            </option>
                            <option value="7_hari" @selected(request('waktu') == '7_hari')>
                                7 Hari Terakhir
                            </option>
                            <option value="bulan_ini" @selected(request('waktu') == 'bulan_ini')>
                                Bulan Ini
                            </option>
                        </select>
                    </div>

                    <div class="md:col-span-2 flex items-end">
                        <a href="{{ route('peminjamans.index') }}"
                            class="w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm table-fixed">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-4 text-left font-semibold text-gray-600 w-14">
                                    No
                                </th>
                                <th class="px-4 py-4 text-left font-semibold text-gray-600 w-40">
                                    Peminjam
                                </th>
                                <th class="px-4 py-4 text-left font-semibold text-gray-600 w-28">
                                    Kelas
                                </th>
                                <th class="px-4 py-4 text-left font-semibold text-gray-600 w-44">
                                    Peralatan
                                </th>
                                <th class="px-4 py-4 text-center font-semibold text-gray-600 w-24">
                                    Jumlah
                                </th>
                                <th class="px-4 py-4 text-left font-semibold text-gray-600 w-36">
                                    Tanggal Pinjam
                                </th>
                                <th class="px-4 py-4 text-left font-semibold text-gray-600 w-40">
                                    Harus Kembali
                                </th>
                                <th class="px-4 py-4 text-left font-semibold text-gray-600 w-44">
                                    Dikembalikan
                                </th>
                                <th class="px-4 py-4 text-center font-semibold text-gray-600 w-36">
                                    Status
                                </th>
                                <th class="px-4 py-4 text-center font-semibold text-gray-600 w-52">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($peminjamans as $peminjaman)
                                @php
                                    $terlambat = $peminjaman->status === 'dipinjam'
                                        && $peminjaman->tanggal_kembali
                                        && $peminjaman->tanggal_kembali < now()->toDateString();

                                    $tanggalPinjam = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d M Y');
                                    $tanggalKembali = $peminjaman->tanggal_kembali
                                        ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->translatedFormat('d M Y')
                                        : '-';

                                    $tanggalDikembalikan = $peminjaman->tanggal_dikembalikan
                                        ? \Carbon\Carbon::parse($peminjaman->tanggal_dikembalikan)->translatedFormat('d M Y')
                                        : '-';
                                @endphp

                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 text-gray-700">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-4 py-4 font-medium text-gray-900 whitespace-normal break-words">
                                        {{ $peminjaman->pengguna->nama }}
                                    </td>

                                    <td class="px-4 py-4 whitespace-normal break-words">
                                        {{ $peminjaman->pengguna->kelas }}
                                    </td>

                                    <td class="px-4 py-4 whitespace-normal break-words">
                                        {{ $peminjaman->peralatan->nama_peralatan }}
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold">
                                            {{ $peminjaman->jumlah_pinjam }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 whitespace-nowrap">
                                        {{ $tanggalPinjam }}
                                    </td>

                                    <td class="px-4 py-4 whitespace-nowrap">
                                        {{ $tanggalKembali }}
                                    </td>

                                    <td class="px-4 py-4 whitespace-nowrap">
                                        {{ $tanggalDikembalikan }}
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        @if($peminjaman->status === 'dikembalikan')
                                            <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold whitespace-nowrap">
                                                Dikembalikan
                                            </span>
                                        @elseif($terlambat)
                                            <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-semibold whitespace-nowrap">
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 text-xs font-semibold whitespace-nowrap">
                                                Dipinjam
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4">
                                        @if(auth()->user()->role === 'admin')
                                            <div class="flex justify-center gap-2">
                                                @if($peminjaman->status === 'dipinjam')
                                                    <form action="{{ route('peminjamans.selesai', $peminjaman->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                            onclick="return confirm('Tandai peminjaman ini sebagai dikembalikan?')"
                                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-xs font-semibold">
                                                            Selesai
                                                        </button>
                                                    </form>

                                                    <a href="{{ route('peminjamans.edit', $peminjaman->id) }}"
                                                        class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-2 rounded-lg text-xs font-semibold">
                                                        Edit
                                                    </a>
                                                @endif

                                                <form action="{{ route('peminjamans.destroy', $peminjaman->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        onclick="return confirm('Yakin hapus data ini?')"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-xs font-semibold">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">
                                                Tidak ada akses
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                                        Data peminjaman tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                {{ $peminjamans->links() }}
            </div>
        </div>
    </div>

    <script>
        const formPeminjaman = document.getElementById('filterPeminjaman');
        let timerPeminjaman;

        document.querySelectorAll('.js-auto-submit').forEach(function (element) {
            element.addEventListener('change', function () {
                formPeminjaman.submit();
            });
        });

        document.querySelectorAll('.js-auto-search').forEach(function (element) {
            element.addEventListener('input', function () {
                clearTimeout(timerPeminjaman);
                timerPeminjaman = setTimeout(function () {
                    formPeminjaman.submit();
                }, 600);
            });
        });
    </script>
</x-app-layout>