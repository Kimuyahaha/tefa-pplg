<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800">
                Riwayat Pengembalian
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Data peminjaman yang sudah dikembalikan
            </p>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-5">
                <form method="GET" action="{{ route('peminjamans.riwayat-pengembalian') }}"
                    class="flex gap-3">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari peminjam atau peralatan..."
                        class="w-full border-gray-300 rounded-lg shadow-sm">

                    <button
                        type="submit"
                        class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg font-semibold">
                        Cari
                    </button>

                    <a href="{{ route('peminjamans.riwayat-pengembalian') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold">
                        Reset
                    </a>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left">No</th>
                                <th class="px-6 py-4 text-left">Peminjam</th>
                                <th class="px-6 py-4 text-left">Kelas</th>
                                <th class="px-6 py-4 text-left">Peralatan</th>
                                <th class="px-6 py-4 text-center">Jumlah</th>
                                <th class="px-6 py-4 text-left">Tanggal Pinjam</th>
                                <th class="px-6 py-4 text-left">Tanggal Harus Kembali</th>
                                <th class="px-6 py-4 text-left">Tanggal Dikembalikan</th>
                                <th class="px-6 py-4 text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($peminjamans as $peminjaman)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $loop->iteration }}</td>

                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $peminjaman->pengguna->nama }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $peminjaman->pengguna->kelas }}
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
                                        {{ $peminjaman->tanggal_kembali ?? '-' }}
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
                                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                        Belum ada riwayat pengembalian.
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
</x-app-layout>