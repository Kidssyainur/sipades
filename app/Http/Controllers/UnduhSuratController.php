<?php

namespace App\Http\Controllers;

use App\Models\SuratTerbit;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UnduhSuratController extends Controller
{
    /**
     * Streaming file PDF surat terbit. Dilindungi middleware `signed`
     * sehingga tautan otomatis kedaluwarsa (PRD §11.5 & FR-10).
     */
    public function __invoke(SuratTerbit $surat): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($surat->file_path), 404, 'File surat tidak ditemukan.');

        $namaFile = str_replace(['/', '\\'], '-', $surat->nomor_surat).'.pdf';

        return Storage::disk('local')->download($surat->file_path, $namaFile);
    }
}
