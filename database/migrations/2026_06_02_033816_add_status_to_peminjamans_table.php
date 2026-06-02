<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->enum('status', ['dipinjam', 'dikembalikan'])
                ->default('dipinjam')
                ->after('jumlah_pinjam');

            $table->date('tanggal_dikembalikan')
                ->nullable()
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn(['status', 'tanggal_dikembalikan']);
        });
    }
};