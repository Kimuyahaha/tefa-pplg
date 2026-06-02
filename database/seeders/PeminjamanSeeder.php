<?php

namespace Database\Seeders;

use App\Models\Peminjaman;
use App\Models\Peralatan;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        Peminjaman::create([
            'pengguna_id' => 2,
            'peralatan_id' => 1,
            'tanggal_pinjam' => now()->subDays(2)->toDateString(),
            'tanggal_kembali' => now()->addDays(3)->toDateString(),
            'jumlah_pinjam' => 2,
        ]);

        Peminjaman::create([
            'pengguna_id' => 3,
            'peralatan_id' => 2,
            'tanggal_pinjam' => now()->subDay()->toDateString(),
            'tanggal_kembali' => now()->addDays(2)->toDateString(),
            'jumlah_pinjam' => 1,
        ]);

        Peralatan::where('id', 1)->decrement('jumlah_stok', 2);
        Peralatan::where('id', 2)->decrement('jumlah_stok', 1);
    }
}