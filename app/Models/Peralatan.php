<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peralatan extends Model
{
    protected $table = 'peralatans';

    protected $fillable = [
        'nama_peralatan',
        'kategori',
        'jumlah_stok',
        'kondisi',
        'foto',
    ];

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'peralatan_id');
    }
}