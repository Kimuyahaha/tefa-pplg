<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Tambah Peminjaman
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4">

            <form action="{{ route('peminjamans.store') }}"
                method="POST"
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">

                @csrf

                <div class="mb-4">
                    <label class="block mb-2 font-medium">Pengguna</label>
                    <select name="pengguna_id" class="w-full border rounded-lg px-3 py-2">
                        <option value="">Pilih Pengguna</option>
                        @foreach($penggunas as $pengguna)
                            <option value="{{ $pengguna->id }}" @selected(old('pengguna_id') == $pengguna->id)>
                                {{ $pengguna->nama }} - {{ $pengguna->kelas }}
                            </option>
                        @endforeach
                    </select>
                    @error('pengguna_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-medium">Peralatan</label>
                    <select name="peralatan_id" class="w-full border rounded-lg px-3 py-2">
                        <option value="">Pilih Peralatan</option>
                        @foreach($peralatans as $peralatan)
                            <option value="{{ $peralatan->id }}" @selected(old('peralatan_id') == $peralatan->id)>
                                {{ $peralatan->nama_peralatan }} - Stok: {{ $peralatan->jumlah_stok }}
                            </option>
                        @endforeach
                    </select>
                    @error('peralatan_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-medium">Tanggal Pinjam</label>
                    <input type="date"
                        name="tanggal_pinjam"
                        value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                        class="w-full border rounded-lg px-3 py-2">
                    @error('tanggal_pinjam')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-medium">Tanggal Kembali</label>
                    <input type="date"
                        name="tanggal_kembali"
                        value="{{ old('tanggal_kembali') }}"
                        class="w-full border rounded-lg px-3 py-2">
                    @error('tanggal_kembali')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-medium">Jumlah Pinjam</label>
                    <input type="number"
                        name="jumlah_pinjam"
                        value="{{ old('jumlah_pinjam', 1) }}"
                        min="1"
                        class="w-full border rounded-lg px-3 py-2">
                    @error('jumlah_pinjam')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">
                        Simpan
                    </button>

                    <a href="{{ route('peminjamans.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>