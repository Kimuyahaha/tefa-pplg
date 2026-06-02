<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        Pengguna::create([
            'nama' => 'Admin TEFA',
            'kelas' => '-',
            'jurusan' => 'PPLG',
            'no_hp' => '081234567890',
            'email' => 'admin@tefa.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        Pengguna::create([
            'nama' => 'Ahmad Fauzan',
            'kelas' => 'XI PPLG 1',
            'jurusan' => 'PPLG',
            'no_hp' => '081111111111',
            'email' => 'ahmad@tefa.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        Pengguna::create([
            'nama' => 'Rizky Pratama',
            'kelas' => 'XI PPLG 2',
            'jurusan' => 'PPLG',
            'no_hp' => '082222222222',
            'email' => 'rizky@tefa.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        Pengguna::create([
            'nama' => 'Dinda Putri',
            'kelas' => 'XI PPLG 1',
            'jurusan' => 'PPLG',
            'no_hp' => '083333333333',
            'email' => 'dinda@tefa.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}