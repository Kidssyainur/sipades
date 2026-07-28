<?php

namespace App\Services;

use App\Models\PengajuanSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratPdfService
{
    /**
     * Render PDF surat dari template Blade jenis surat, simpan ke storage.
     *
     * @return string path relatif file yang tersimpan (disk 'local')
     */
    public function generate(PengajuanSurat $pengajuan, string $nomorSurat): string
    {
        $pengajuan->loadMissing(['warga', 'jenisSurat']);

        $penduduk = $pengajuan->warga
            ? \App\Models\DataKependudukan::where('nik', $pengajuan->warga->nik)->first()
            : null;

        $view = $pengajuan->jenisSurat->template_view ?: 'surat.default';

        $pdf = Pdf::loadView($view, [
            'pengajuan' => $pengajuan,
            'penduduk' => $penduduk,
            'data' => $pengajuan->data_formulir ?? [],
            'nomorSurat' => $nomorSurat,
            'tanggalTerbit' => now(),
            'desa' => [
                'nama' => config('desa.nama', env('DESA_NAMA', 'Desa Karduluk')),
                'kecamatan' => config('desa.kecamatan', env('DESA_KECAMATAN', 'Pragaan')),
                'kabupaten' => config('desa.kabupaten', env('DESA_KABUPATEN', 'Sumenep')),
            ],
        ]);

        $path = 'surat/'.now()->format('Y/m').'/'.Str::slug($pengajuan->nomor_referensi).'.pdf';

        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }
}
