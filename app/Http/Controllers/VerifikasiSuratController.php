<?php

namespace App\Http\Controllers;

use App\Models\SuratTerbit;
use Illuminate\Contracts\View\View;

class VerifikasiSuratController extends Controller
{
    /**
     * Tampilkan halaman verifikasi keabsahan Tanda Tangan Elektronik (TTE) dokumen resmi.
     */
    public function __invoke(string $token): View
    {
        $surat = SuratTerbit::where('tte_token', $token)
            ->with(['pengajuanSurat.warga', 'pengajuanSurat.jenisSurat', 'penerbit'])
            ->firstOrFail();

        return view('surat.verifikasi', [
            'surat' => $surat,
            'pengajuan' => $surat->pengajuanSurat,
            'warga' => $surat->pengajuanSurat?->warga,
            'jenis' => $surat->pengajuanSurat?->jenisSurat,
            'penerbit' => $surat->penerbit,
        ]);
    }
}
