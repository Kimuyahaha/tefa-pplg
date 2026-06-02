<?php

namespace App\Http\Requests;

use App\Models\Peralatan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdatePeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'pengguna_id' => ['required', 'exists:penggunas,id'],
            'peralatan_id' => ['required', 'exists:peralatans,id'],
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_kembali' => ['nullable', 'date', 'after_or_equal:tanggal_pinjam'],
            'jumlah_pinjam' => ['required', 'integer', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $peminjaman = $this->route('peminjaman');
                $peralatanBaru = Peralatan::find($this->peralatan_id);

                if (!$peminjaman || !$peralatanBaru) {
                    return;
                }

                $stokTersedia = $peralatanBaru->jumlah_stok;

                if ((int) $peminjaman->peralatan_id === (int) $this->peralatan_id) {
                    $stokTersedia += $peminjaman->jumlah_pinjam;
                }

                if ((int) $this->jumlah_pinjam > $stokTersedia) {
                    $validator->errors()->add('jumlah_pinjam', 'Jumlah pinjam melebihi stok tersedia.');
                }
            }
        ];
    }
}