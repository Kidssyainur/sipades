<?php

namespace App\Http\Controllers;

use App\Models\SuratTerbit;
use App\Services\SuratPdfService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UnduhSuratController extends Controller
{
    /**
     * Streaming file PDF surat terbit. Dilindungi middleware `signed`
     * dengan mekanisme auto-regeneration jika file belum tersimpan di disk.
     */
    public function __invoke(SuratTerbit $surat, SuratPdfService $pdfService): StreamedResponse
    {
        $surat->loadMissing(['pengajuanSurat.jenisSurat', 'pengajuanSurat.warga']);

        if (empty($surat->file_path) || ! Storage::disk('local')->exists($surat->file_path)) {
            // Auto regenerate PDF jika file fisik terhapus/belum ada untuk record SELESAI
            if ($surat->pengajuanSurat) {
                if (empty($surat->tte_token)) {
                    $surat->update(['tte_token' => 'TTE-KDL-' . strtoupper(Str::random(16))]);
                }
                $newPath = $pdfService->generate($surat->pengajuanSurat, $surat->nomor_surat, $surat);
                $surat->update(['file_path' => $newPath]);
            }
        }

        abort_unless(Storage::disk('local')->exists($surat->file_path), 404, 'File surat terbit tidak ditemukan.');

        $namaFile = str_replace(['/', '\\'], '-', $surat->nomor_surat) . '.pdf';

        return Storage::disk('local')->download($surat->file_path, $namaFile);
    }
}
