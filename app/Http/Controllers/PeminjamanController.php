<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeminjamanRequest;
use App\Http\Requests\UpdatePeminjamanRequest;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use App\Models\Peralatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function index(): View
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $search = request('search');
        $status = request('status');
        $waktu = request('waktu');

        $peminjamans = Peminjaman::with(['pengguna', 'peralatan'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('pengguna', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('kelas', 'like', "%{$search}%")
                        ->orWhere('jurusan', 'like', "%{$search}%");
                })->orWhereHas('peralatan', function ($q) use ($search) {
                    $q->where('nama_peralatan', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%");
                });
            })
            ->when($status === 'dipinjam', function ($query) {
                $query->where('status', 'dipinjam');
            })
            ->when($status === 'dikembalikan', function ($query) {
                $query->where('status', 'dikembalikan');
            })
            ->when($status === 'terlambat', function ($query) {
                $query->where('status', 'dipinjam')
                    ->whereDate('tanggal_kembali', '<', today());
            })
            ->when($waktu === 'hari_ini', function ($query) {
                $query->whereDate('tanggal_pinjam', today());
            })
            ->when($waktu === '7_hari', function ($query) {
                $query->whereDate('tanggal_pinjam', '>=', now()->subDays(7)->toDateString());
            })
            ->when($waktu === 'bulan_ini', function ($query) {
                $query->whereMonth('tanggal_pinjam', now()->month)
                    ->whereYear('tanggal_pinjam', now()->year);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peminjamans.index', compact(
            'peminjamans',
            'search',
            'status',
            'waktu'
        ));
    }

    public function riwayatSaya(): View
    {
        if (auth()->user()->role !== 'user') {
            abort(403);
        }

        $peminjamans = Peminjaman::with(['peralatan'])
            ->where('pengguna_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('peminjamans.saya', compact('peminjamans'));
    }

    public function create(): View
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $penggunas = Pengguna::where('role', 'user')->orderBy('nama')->get();
        $peralatans = Peralatan::orderBy('nama_peralatan')->get();

        return view('peminjamans.create', compact('penggunas', 'peralatans'));
    }

    public function store(StorePeminjamanRequest $request): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $peralatan = Peralatan::lockForUpdate()->findOrFail($data['peralatan_id']);

            if ($data['jumlah_pinjam'] > $peralatan->jumlah_stok) {
                abort(422, 'Stok tidak mencukupi.');
            }

            $peralatan->decrement('jumlah_stok', $data['jumlah_pinjam']);

            Peminjaman::create([
                'pengguna_id' => $data['pengguna_id'],
                'peralatan_id' => $data['peralatan_id'],
                'tanggal_pinjam' => $data['tanggal_pinjam'],
                'tanggal_kembali' => $data['tanggal_kembali'] ?? null,
                'jumlah_pinjam' => $data['jumlah_pinjam'],
                'status' => 'dipinjam',
            ]);
        });

        return redirect()
            ->route('peminjamans.index')
            ->with('success', 'Data peminjaman berhasil ditambahkan.');
    }

    public function edit(Peminjaman $peminjaman): View
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($peminjaman->status === 'dikembalikan') {
            abort(403, 'Data yang sudah dikembalikan tidak dapat diubah.');
        }

        $penggunas = Pengguna::where('role', 'user')->orderBy('nama')->get();
        $peralatans = Peralatan::orderBy('nama_peralatan')->get();

        return view('peminjamans.edit', compact('peminjaman', 'penggunas', 'peralatans'));
    }

    public function update(UpdatePeminjamanRequest $request, Peminjaman $peminjaman): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($peminjaman->status === 'dikembalikan') {
            return redirect()
                ->route('peminjamans.index')
                ->with('success', 'Peminjaman yang sudah dikembalikan tidak dapat diubah.');
        }

        $data = $request->validated();

        DB::transaction(function () use ($data, $peminjaman) {
            $peralatanLama = Peralatan::lockForUpdate()->findOrFail($peminjaman->peralatan_id);
            $peralatanBaru = Peralatan::lockForUpdate()->findOrFail($data['peralatan_id']);

            $peralatanLama->increment('jumlah_stok', $peminjaman->jumlah_pinjam);

            if ($data['jumlah_pinjam'] > $peralatanBaru->jumlah_stok) {
                abort(422, 'Stok tidak mencukupi.');
            }

            $peralatanBaru->decrement('jumlah_stok', $data['jumlah_pinjam']);

            $peminjaman->update($data);
        });

        return redirect()
            ->route('peminjamans.index')
            ->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function selesai(Peminjaman $peminjaman): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($peminjaman->status === 'dikembalikan') {
            return redirect()
                ->route('peminjamans.index')
                ->with('success', 'Peminjaman sudah dikembalikan sebelumnya.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peralatan = Peralatan::lockForUpdate()->findOrFail($peminjaman->peralatan_id);

            $peralatan->increment('jumlah_stok', $peminjaman->jumlah_pinjam);

            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_dikembalikan' => now()->toDateString(),
            ]);
        });

        return redirect()
            ->route('peminjamans.index')
            ->with('success', 'Peralatan berhasil dikembalikan dan stok sudah bertambah.');
    }

    public function destroy(Peminjaman $peminjaman): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        DB::transaction(function () use ($peminjaman) {
            if ($peminjaman->status === 'dipinjam') {
                $peralatan = Peralatan::lockForUpdate()->findOrFail($peminjaman->peralatan_id);
                $peralatan->increment('jumlah_stok', $peminjaman->jumlah_pinjam);
            }

            $peminjaman->delete();
        });

        return redirect()
            ->route('peminjamans.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }

    public function riwayatPengembalian(): View
    {
        $search = request('search');

        $peminjamans = Peminjaman::with(['pengguna', 'peralatan'])
            ->where('status', 'dikembalikan')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('pengguna', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
                })->orWhereHas('peralatan', function ($q) use ($search) {
                $q->where('nama_peralatan', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

            return view('peminjamans.riwayat-pengembalian', compact('peminjamans', 'search'));
    }

    public function export()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $fileName = 'data-peminjaman.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'No',
                'Nama Peminjam',
                'Kelas',
                'Jurusan',
                'Peralatan',
                'Jumlah Pinjam',
                'Tanggal Pinjam',
                'Tanggal Harus Kembali',
                'Tanggal Dikembalikan',
                'Status',
            ]);

            $peminjamans = Peminjaman::with(['pengguna', 'peralatan'])
                ->latest()
                ->get();

            foreach ($peminjamans as $index => $peminjaman) {
                fputcsv($file, [
                    $index + 1,
                    $peminjaman->pengguna->nama,
                    $peminjaman->pengguna->kelas,
                    $peminjaman->pengguna->jurusan,
                    $peminjaman->peralatan->nama_peralatan,
                    $peminjaman->jumlah_pinjam,
                    $peminjaman->tanggal_pinjam,
                    $peminjaman->tanggal_kembali ?? '-',
                    $peminjaman->tanggal_dikembalikan ?? '-',
                    ucfirst($peminjaman->status),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
        public function exportPdf()
    {   
        if (auth()->user()->role !== 'admin') {
            abort(403);
    }

        $peminjamans = Peminjaman::with(['pengguna', 'peralatan'])
            ->latest()
            ->get();

        $pdf = Pdf::loadView('peminjamans.pdf', [
            'peminjamans' => $peminjamans,
            'tanggalCetak' => now()->format('d-m-Y H:i'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-data-peminjaman.pdf');
    }
}