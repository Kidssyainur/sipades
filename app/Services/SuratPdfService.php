<?php

namespace App\Services;

use App\Models\PengajuanSurat;
use App\Models\SuratTerbit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratPdfService
{
    /**
     * Render PDF surat dari template Blade jenis surat, simpan ke storage.
     *
     * @return string path relatif file yang tersimpan (disk 'local')
     */
    public function generate(PengajuanSurat $pengajuan, string $nomorSurat, ?SuratTerbit $suratTerbit = null): string
    {
        $pengajuan->loadMissing(['warga', 'jenisSurat', 'suratTerbit']);

        $suratTerbitRecord = $suratTerbit ?? $pengajuan->suratTerbit;

        $penduduk = $pengajuan->warga
            ? \App\Models\DataKependudukan::where('nik', $pengajuan->warga->nik)->first()
            : null;

        $view = $pengajuan->jenisSurat?->template_view ?: 'surat.default';
        if (! view()->exists($view)) {
            $view = 'surat.default';
        }

        // TTE Token & QR Code Data URI
        $tteToken = $suratTerbitRecord?->tte_token ?? 'TTE-KDL-' . strtoupper(Str::random(16));
        $verifikasiUrl = route('surat.verifikasi', $tteToken);
        $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($verifikasiUrl);

        try {
            $response = Http::timeout(5)->get($qrApiUrl);
            $qrBase64 = $response->successful()
                ? 'data:image/png;base64,' . base64_encode($response->body())
                : $qrApiUrl;
        } catch (\Throwable $e) {
            $qrBase64 = $qrApiUrl;
        }

        $pdf = Pdf::setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ])->loadView($view, [
            'pengajuan' => $pengajuan,
            'penduduk' => $penduduk,
            'data' => $pengajuan->data_formulir ?? [],
            'nomorSurat' => $nomorSurat,
            'suratTerbit' => $suratTerbitRecord,
            'tteToken' => $tteToken,
            'verifikasiUrl' => $verifikasiUrl,
            'qrBase64' => $qrBase64,
            'tanggalTerbit' => $suratTerbitRecord?->tanggal_terbit ?? now(),
            'desa' => [
                'nama' => config('desa.nama', env('DESA_NAMA', 'Desa Karduluk')),
                'kecamatan' => config('desa.kecamatan', env('DESA_KECAMATAN', 'Pragaan')),
                'kabupaten' => config('desa.kabupaten', env('DESA_KABUPATEN', 'Sumenep')),
            ],
        ]);

        $path = 'surat/' . now()->format('Y/m') . '/' . Str::slug($pengajuan->nomor_referensi) . '.pdf';

        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }
}
