<?php

namespace App\Services;

use App\Models\NomorSuratCounter;
use Illuminate\Support\Facades\DB;

class NomorSuratService
{
    /**
     * Angka bulan → angka Romawi untuk format nomor surat.
     */
    private const ROMAWI = [
        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
        7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
    ];

    /**
     * Generate nomor surat resmi yang unik & berurutan per (jenis surat, tahun).
     *
     * Format: 470/{urutan}/DS-KDL/{bulan_romawi}/{tahun}
     */
    public function generate(int $jenisSuratId): string
    {
        $tahun = (int) now()->format('Y');
        $bulanRomawi = self::ROMAWI[(int) now()->format('n')];

        return DB::transaction(function () use ($jenisSuratId, $tahun, $bulanRomawi) {
            $counter = NomorSuratCounter::query()
                ->where('jenis_surat_id', $jenisSuratId)
                ->where('tahun', $tahun)
                ->lockForUpdate()
                ->first();

            if (! $counter) {
                $counter = NomorSuratCounter::create([
                    'jenis_surat_id' => $jenisSuratId,
                    'tahun' => $tahun,
                    'nomor_terakhir' => 0,
                ]);
            }

            do {
                $counter->nomor_terakhir += 1;
                $urutanFormat = str_pad((string) $counter->nomor_terakhir, 3, '0', STR_PAD_LEFT);
                $nomorSurat = "470/{$urutanFormat}/DS-KDL/{$bulanRomawi}/{$tahun}";
                $exists = \App\Models\SuratTerbit::where('nomor_surat', $nomorSurat)->exists();
            } while ($exists);

            $counter->save();

            return $nomorSurat;
        });
    }
}
