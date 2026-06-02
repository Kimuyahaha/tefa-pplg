<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Peralatan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4">

            <form
                action="{{ route('peralatans.update', $peralatan->id) }}"
                method="POST"
                enctype="multipart/form-data"
                style="background:white;padding:24px;border-radius:10px;box-shadow:0 1px 3px rgba(0,0,0,.1);">

                @csrf
                @method('PUT')

                <div style="margin-bottom:16px;">
                    <label>Nama Peralatan</label>
                    <input
                        type="text"
                        name="nama_peralatan"
                        value="{{ old('nama_peralatan', $peralatan->nama_peralatan) }}"
                        style="width:100%;border:1px solid #94a3b8;border-radius:6px;padding:10px;margin-top:6px;">
                </div>

                <div style="margin-bottom:16px;">
                    <label>Kategori</label>
                    <input
                        type="text"
                        name="kategori"
                        value="{{ old('kategori', $peralatan->kategori) }}"
                        style="width:100%;border:1px solid #94a3b8;border-radius:6px;padding:10px;margin-top:6px;">
                </div>

                <div style="margin-bottom:16px;">
                    <label>Jumlah Stok</label>
                    <input
                        type="number"
                        name="jumlah_stok"
                        value="{{ old('jumlah_stok', $peralatan->jumlah_stok) }}"
                        style="width:100%;border:1px solid #94a3b8;border-radius:6px;padding:10px;margin-top:6px;">
                </div>

                <div style="margin-bottom:16px;">
                    <label>Kondisi</label>
                    <select
                        name="kondisi"
                        style="width:100%;border:1px solid #94a3b8;border-radius:6px;padding:10px;margin-top:6px;">
                        <option value="Baik" @selected($peralatan->kondisi == 'Baik')>Baik</option>
                        <option value="Rusak Ringan" @selected($peralatan->kondisi == 'Rusak Ringan')>Rusak Ringan</option>
                        <option value="Rusak Berat" @selected($peralatan->kondisi == 'Rusak Berat')>Rusak Berat</option>
                    </select>
                </div>

                @if($peralatan->foto)
                    <div style="margin-bottom:16px;">
                        <p style="margin-bottom:8px;">Foto Saat Ini</p>
                        <img
                            src="{{ asset('storage/' . $peralatan->foto) }}"
                            style="width:160px;height:120px;object-fit:cover;border-radius:8px;">
                    </div>
                @endif

                <div style="margin-bottom:20px;">
                    <label>Ganti Foto</label>
                    <input
                        type="file"
                        name="foto"
                        style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:10px;margin-top:6px;">
                </div>

                <div style="display:flex;gap:10px;">
                    <button
                        type="submit"
                        style="background:#2563eb;color:white;padding:10px 18px;border:none;border-radius:6px;cursor:pointer;">
                        Update
                    </button>

                    <a
                        href="{{ route('peralatans.index') }}"
                        style="background:#6b7280;color:white;padding:10px 18px;border-radius:6px;text-decoration:none;">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>