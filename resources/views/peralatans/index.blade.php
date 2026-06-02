<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">
                    Data Peralatan
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola data peralatan Lab TEFA PPLG
                </p>
            </div>

            @if(auth()->user()->role === 'admin')
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('peralatans.export-pdf') }}"
                        class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-sm text-sm font-semibold">
                        Export PDF
                    </a>

                    <a href="{{ route('peralatans.export') }}"
                        class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-sm text-sm font-semibold">
                        Export Excel
                    </a>

                    <a href="{{ route('peralatans.create') }}"
                        class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-sm text-sm font-semibold">
                        + Tambah Peralatan
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

            @if(session('error'))
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-5">
                <form id="filterPeralatan" method="GET" action="{{ route('peralatans.index') }}"
                    class="grid grid-cols-1 md:grid-cols-12 gap-3">

                    <div class="md:col-span-6">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama, kategori, atau kondisi..."
                            class="w-full border-gray-300 rounded-lg shadow-sm js-auto-search">
                    </div>

                    <div class="md:col-span-4">
                        <select
                            name="kategori"
                            class="w-full border-gray-300 rounded-lg shadow-sm js-auto-submit">

                            <option value="">Semua Kategori</option>

                            @foreach($kategoriList as $item)
                                <option value="{{ $item }}" @selected(request('kategori') == $item)>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <a href="{{ route('peralatans.index') }}"
                            class="block text-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-gray-600">No</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-600">Foto</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-600">Nama Peralatan</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-600">Kategori</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-600">Stok</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-600">Kondisi</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($peralatans as $peralatan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($peralatan->foto)
                                            <img src="{{ asset('storage/' . $peralatan->foto) }}"
                                                class="w-16 h-16 object-cover rounded-lg border">
                                        @else
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-xs text-gray-400 border">
                                                No Image
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $peralatan->nama_peralatan }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $peralatan->kategori }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold">
                                            {{ $peralatan->jumlah_stok }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if($peralatan->kondisi === 'Baik')
                                            <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold">
                                                Baik
                                            </span>
                                        @elseif($peralatan->kondisi === 'Rusak Ringan')
                                            <span class="px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 text-xs font-semibold">
                                                Rusak Ringan
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-semibold">
                                                {{ $peralatan->kondisi }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if(auth()->user()->role === 'admin')
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ route('peralatans.edit', $peralatan->id) }}"
                                                    class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-2 rounded-lg text-xs font-semibold">
                                                    Edit
                                                </a>

                                                <form action="{{ route('peralatans.destroy', $peralatan->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-xs font-semibold">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            @if($peralatan->jumlah_stok > 0)
                                                <form action="{{ route('peralatans.pinjam', $peralatan->id) }}" method="POST"
                                                    class="flex justify-center gap-2">
                                                    @csrf

                                                    <input
                                                        type="number"
                                                        name="jumlah_pinjam"
                                                        value="1"
                                                        min="1"
                                                        max="{{ $peralatan->jumlah_stok }}"
                                                        class="w-16 border-gray-300 rounded-lg text-sm">

                                                    <button type="submit"
                                                        onclick="return confirm('Pinjam peralatan ini?')"
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-xs font-semibold">
                                                        Pinjam
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-red-500 text-sm font-semibold">Stok Habis</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        Data peralatan tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                {{ $peralatans->links() }}
            </div>
        </div>
    </div>

    <script>
        const formPeralatan = document.getElementById('filterPeralatan');
        let timerPeralatan;

        document.querySelectorAll('.js-auto-submit').forEach(function (element) {
            element.addEventListener('change', function () {
                formPeralatan.submit();
            });
        });

        document.querySelectorAll('.js-auto-search').forEach(function (element) {
            element.addEventListener('input', function () {
                clearTimeout(timerPeralatan);
                timerPeralatan = setTimeout(function () {
                    formPeralatan.submit();
                }, 600);
            });
        });
    </script>
</x-app-layout>