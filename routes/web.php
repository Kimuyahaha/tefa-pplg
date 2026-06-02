<?php

use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use App\Models\Peralatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        $peminjamanBulanan = Peminjaman::selectRaw('MONTH(tanggal_pinjam) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_pinjam', now()->year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $chartLabels = [];
        $chartData = [];

        for ($i = 1; $i <= 12; $i++) {
            $chartLabels[] = now()->month($i)->translatedFormat('M');
            $chartData[] = $peminjamanBulanan[$i] ?? 0;
        }

        $peralatanTerfavorit = Peminjaman::select('peralatan_id', DB::raw('COUNT(*) as total'))
            ->with('peralatan')
            ->groupBy('peralatan_id')
            ->orderByDesc('total')
            ->first();

        $topPeralatans = Peminjaman::select('peralatan_id', DB::raw('COUNT(*) as total'))
            ->with('peralatan')
            ->groupBy('peralatan_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $topPeminjams = Peminjaman::select('pengguna_id', DB::raw('COUNT(*) as total'))
            ->with('pengguna')
            ->groupBy('pengguna_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('dashboard', [
            'totalPengguna' => Pengguna::where('role', 'user')->count(),
            'totalPeralatan' => Peralatan::count(),
            'totalPeminjaman' => Peminjaman::count(),
            'totalStok' => Peralatan::sum('jumlah_stok'),

            'totalDipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'totalDikembalikan' => Peminjaman::where('status', 'dikembalikan')->count(),
            'totalTerlambat' => Peminjaman::where('status', 'dipinjam')
                ->whereDate('tanggal_kembali', '<', today())
                ->count(),

            'peralatanTerfavorit' => $peralatanTerfavorit,
            'topPeralatans' => $topPeralatans,
            'topPeminjams' => $topPeminjams,

            'chartLabels' => $chartLabels,
            'chartData' => $chartData,

            'peminjamansTerbaru' => Peminjaman::with(['pengguna', 'peralatan'])
                ->latest()
                ->take(5)
                ->get(),

            'riwayatPengembalian' => Peminjaman::with(['pengguna', 'peralatan'])
                ->where('status', 'dikembalikan')
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }

    $peminjamanAktif = Peminjaman::with('peralatan')
        ->where('pengguna_id', auth()->id())
        ->where('status', 'dipinjam')
        ->orderBy('tanggal_kembali')
        ->first();

    $terlambatSaya = Peminjaman::where('pengguna_id', auth()->id())
        ->where('status', 'dipinjam')
        ->whereDate('tanggal_kembali', '<', today())
        ->count();

    return view('dashboard', [
        'totalPeralatan' => Peralatan::count(),

        'peminjamanSaya' => Peminjaman::where('pengguna_id', auth()->id())->count(),

        'dipinjamSaya' => Peminjaman::where('pengguna_id', auth()->id())
            ->where('status', 'dipinjam')
            ->count(),

        'dikembalikanSaya' => Peminjaman::where('pengguna_id', auth()->id())
            ->where('status', 'dikembalikan')
            ->count(),

        'terlambatSaya' => $terlambatSaya,
        'peminjamanAktif' => $peminjamanAktif,

        'riwayatSaya' => Peminjaman::with('peralatan')
            ->where('pengguna_id', auth()->id())
            ->latest()
            ->take(5)
            ->get(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/peralatans', [PeralatanController::class, 'index'])->name('peralatans.index');

    Route::post('/peralatans/{peralatan}/pinjam', [PeralatanController::class, 'pinjam'])
        ->middleware('user')
        ->name('peralatans.pinjam');

    Route::middleware('admin')->group(function () {
        Route::get('/peralatans/create', [PeralatanController::class, 'create'])->name('peralatans.create');
        Route::post('/peralatans', [PeralatanController::class, 'store'])->name('peralatans.store');
        Route::get('/peralatans/{peralatan}/edit', [PeralatanController::class, 'edit'])->name('peralatans.edit');
        Route::put('/peralatans/{peralatan}', [PeralatanController::class, 'update'])->name('peralatans.update');
        Route::patch('/peralatans/{peralatan}', [PeralatanController::class, 'update']);
        Route::delete('/peralatans/{peralatan}', [PeralatanController::class, 'destroy'])->name('peralatans.destroy');

        Route::get('/peralatans-export', [PeralatanController::class, 'export'])->name('peralatans.export');
        Route::get('/peralatans-export-pdf', [PeralatanController::class, 'exportPdf'])->name('peralatans.export-pdf');

        Route::get('/peminjamans', [PeminjamanController::class, 'index'])->name('peminjamans.index');
        Route::get('/peminjamans/create', [PeminjamanController::class, 'create'])->name('peminjamans.create');
        Route::post('/peminjamans', [PeminjamanController::class, 'store'])->name('peminjamans.store');
        Route::get('/peminjamans/{peminjaman}/edit', [PeminjamanController::class, 'edit'])->name('peminjamans.edit');
        Route::put('/peminjamans/{peminjaman}', [PeminjamanController::class, 'update'])->name('peminjamans.update');
        Route::patch('/peminjamans/{peminjaman}', [PeminjamanController::class, 'update']);
        Route::delete('/peminjamans/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('peminjamans.destroy');

        Route::patch('/peminjamans/{peminjaman}/selesai', [PeminjamanController::class, 'selesai'])->name('peminjamans.selesai');

        Route::get('/peminjamans-export', [PeminjamanController::class, 'export'])->name('peminjamans.export');
        Route::get('/peminjamans-export-pdf', [PeminjamanController::class, 'exportPdf'])->name('peminjamans.export-pdf');

        Route::get('/riwayat-pengembalian', [PeminjamanController::class, 'riwayatPengembalian'])
            ->name('peminjamans.riwayat-pengembalian');
    });

    Route::get('/peminjaman-saya', [PeminjamanController::class, 'riwayatSaya'])
        ->middleware('user')
        ->name('peminjamans.saya');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';