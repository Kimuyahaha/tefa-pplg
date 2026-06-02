<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Tambah Peralatan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto">

            <form
                action="{{ route('peralatans.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="bg-white p-6 rounded-lg shadow">

                @csrf

                <div class="mb-4">
                    <label>Nama Peralatan</label>
                    <input
                        type="text"
                        name="nama_peralatan"
                        class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label>Kategori</label>
                    <input
                        type="text"
                        name="kategori"
                        class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label>Jumlah Stok</label>
                    <input
                        type="number"
                        name="jumlah_stok"
                        class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label>Kondisi</label>

                    <select
                        name="kondisi"
                        class="w-full border rounded p-2">

                        <option>Baik</option>
                        <option>Rusak Ringan</option>
                        <option>Rusak Berat</option>

                    </select>
                </div>

                <div class="mb-4">
                    <label>Foto</label>

                    <input
                        type="file"
                        name="foto"
                        class="w-full border rounded p-2">
                </div>

                <button
                    class="bg-blue-600 text-white px-4 py-2 rounded">

                    Simpan

                </button>

            </form>

        </div>
    </div>
</x-app-layout>