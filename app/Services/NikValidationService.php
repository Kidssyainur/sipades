<?php

namespace App\Services;

use App\Models\DataKependudukan;

class NikValidationService
{
    /**
     * Validasi NIK terhadap data kependudukan (FR-01).
     *
     * NIK harus ditemukan dan belum pernah didaftarkan sebagai akun warga.
     *
     * @return array{valid: bool, pesan: string, data: ?DataKependudukan}
     */
    public function validate(string $nik): array
    {
        $penduduk = DataKependudukan::where('nik', $nik)->first();

        if (! $penduduk) {
            return [
                'valid' => false,
                'pesan' => 'NIK tidak ditemukan pada data kependudukan desa.',
                'data' => null,
            ];
        }

        if ($penduduk->sudah_didaftarkan) {
            return [
                'valid' => false,
                'pesan' => 'NIK ini sudah terdaftar sebagai akun. Silakan login.',
                'data' => null,
            ];
        }

        return [
            'valid' => true,
            'pesan' => 'NIK valid.',
            'data' => $penduduk,
        ];
    }
}
