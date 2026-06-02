<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800">
                Peminjaman Saya
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Riwayat peminjaman peralatan milik saya
            </p>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-gray-600">No</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-600">Peralatan</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-600">Jumlah</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-600">Tanggal Pinjam</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-600">Tanggal Harus Kembali</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-600">Tanggal Dikembalikan</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($peminjamans as $peminjaman)
                                @php
                                    $terlambat = $peminjaman->status === 'dipinjam'
                                        && $peminjaman->tanggal_kembali
                                        && $peminjaman->tanggal_kembali < now()->toDateString();
                                @endphp

                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $peminjaman->peralatan->nama_peralatan }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold">
                                            {{ $peminjaman->jumlah_pinjam }}
                                        </span>
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
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        Kamu belum memiliki data peminjaman.
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