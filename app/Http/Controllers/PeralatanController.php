<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeralatanRequest;
use App\Http\Requests\UpdatePeralatanRequest;
use App\Models\Peminjaman;
use App\Models\Peralatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;


class PeralatanController extends Controller
{
    public function index(): View
    {
        $search = request('search');
        $kategori = request('kategori');

        $peralatans = Peralatan::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nama_peralatan', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('kondisi', 'like', "%{$search}%");
            })
            ->when($kategori, function ($query) use ($kategori) {
                $query->where('kategori', $kategori);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kategoriList = Peralatan::select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('peralatans.index', compact(
            'peralatans',
            'search',
            'kategori',
            'kategoriList'
        ));
    }

    public function create(): View
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('peralatans.create');
    }

    public function store(StorePeralatanRequest $request): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('peralatans', 'public');
        }

        Peralatan::create($data);

        return redirect()
            ->route('peralatans.index')
            ->with('success', 'Data peralatan berhasil ditambahkan.');
    }

    public function edit(Peralatan $peralatan): View
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('peralatans.edit', compact('peralatan'));
    }

    public function update(UpdatePeralatanRequest $request, Peralatan $peralatan): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($peralatan->foto) {
                Storage::disk('public')->delete($peralatan->foto);
            }

            $data['foto'] = $request->file('foto')->store('peralatans', 'public');
        }

        $peralatan->update($data);

        return redirect()
            ->route('peralatans.index')
            ->with('success', 'Data peralatan berhasil diperbarui.');
    }

    public function destroy(Peralatan $peralatan): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($peralatan->foto) {
            Storage::disk('public')->delete($peralatan->foto);
        }

        $peralatan->delete();

        return redirect()
            ->route('peralatans.index')
            ->with('success', 'Data peralatan berhasil dihapus.');
    }

    public function pinjam(Peralatan $peralatan): RedirectResponse
    {
        if (auth()->user()->role !== 'user') {
            abort(403);
        }

        request()->validate([
            'jumlah_pinjam' => ['required', 'integer', 'min:1'],
        ]);

        $jumlahPinjam = (int) request('jumlah_pinjam');

        $masihPinjam = Peminjaman::where('pengguna_id', auth()->id())
            ->where('peralatan_id', $peralatan->id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($masihPinjam) {
            return redirect()
                ->route('peralatans.index')
                ->with('error', 'Kamu masih meminjam peralatan ini. Kembalikan dulu sebelum meminjam lagi.');
        }

        DB::transaction(function () use ($peralatan, $jumlahPinjam) {
            $peralatan = Peralatan::lockForUpdate()->findOrFail($peralatan->id);

            if ($jumlahPinjam > $peralatan->jumlah_stok) {
                abort(422, 'Stok tidak mencukupi.');
            }

            $peralatan->decrement('jumlah_stok', $jumlahPinjam);

            Peminjaman::create([
                'pengguna_id' => auth()->id(),
                'peralatan_id' => $peralatan->id,
                'tanggal_pinjam' => now()->toDateString(),
                'tanggal_kembali' => now()->addDays(7)->toDateString(),
                'jumlah_pinjam' => $jumlahPinjam,
                'status' => 'dipinjam',
            ]);
        });

        return redirect()
            ->route('peminjamans.saya')
            ->with('success', 'Peralatan berhasil dipinjam.');
    }

    public function export()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $fileName = 'data-peralatan.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'No',
                'Nama Peralatan',
                'Kategori',
                'Jumlah Stok',
                'Kondisi',
            ]);

            $peralatans = Peralatan::orderBy('nama_peralatan')->get();

            foreach ($peralatans as $index => $peralatan) {
                fputcsv($file, [
                    $index + 1,
                    $peralatan->nama_peralatan,
                    $peralatan->kategori,
                    $peralatan->jumlah_stok,
                    $peralatan->kondisi,
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

        $peralatans = Peralatan::orderBy('nama_peralatan')->get();

        $pdf = Pdf::loadView('peralatans.pdf', [
            'peralatans' => $peralatans,
            'tanggalCetak' => now()->format('d-m-Y H:i'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-data-peralatan.pdf');
    }
}