<?php

namespace Database\Seeders;

use App\Models\Peralatan;
use Illuminate\Database\Seeder;

class PeralatanSeeder extends Seeder
{
    public function run(): void
    {
        Peralatan::insert([
            [
                'nama_peralatan' => 'Laptop Asus',
                'kategori' => 'Laptop',
                'jumlah_stok' => 10,
                'kondisi' => 'Baik',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_peralatan' => 'Mouse Logitech',
                'kategori' => 'Aksesoris',
                'jumlah_stok' => 15,
                'kondisi' => 'Baik',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_peralatan' => 'Keyboard Mechanical',
                'kategori' => 'Aksesoris',
                'jumlah_stok' => 8,
                'kondisi' => 'Baik',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}