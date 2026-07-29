<?php

namespace App\Jobs;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanSurat;
use App\Models\SuratTerbit;
use App\Models\TemplatePesan;
use App\Services\NomorSuratService;
use App\Services\SuratPdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class TerbitkanSuratJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [30, 120, 300];

    public function __construct(
        public int $pengajuanSuratId,
        public int $diterbitkanOleh,
    ) {}

    public function handle(NomorSuratService $nomorSurat, SuratPdfService $pdf): void
    {
        $pengajuan = PengajuanSurat::with(['jenisSurat', 'warga'])->findOrFail($this->pengajuanSuratId);

        // Idempotensi: jangan terbitkan dua kali.
        if ($pengajuan->suratTerbit()->exists()) {
            return;
        }

        $suratTerbit = DB::transaction(function () use ($pengajuan, $nomorSurat, $pdf) {
            $nomor = $nomorSurat->generate($pengajuan->jenis_surat_id);
            $tteToken = 'TTE-KDL-' . strtoupper(Str::random(16));

            $surat = SuratTerbit::create([
                'pengajuan_surat_id' => $pengajuan->id,
                'nomor_surat' => $nomor,
                'diterbitkan_oleh' => $this->diterbitkanOleh,
                'file_path' => 'surat/pending.pdf',
                'tte_token' => $tteToken,
                'tanggal_terbit' => now(),
            ]);

            $filePath = $pdf->generate($pengajuan, $nomor, $surat);

            $surat->update(['file_path' => $filePath]);

            $pengajuan->update([
                'status' => StatusPengajuan::SELESAI,
                'tanggal_selesai' => now(),
            ]);

            return $surat;
        });

        $this->kirimNotifikasiTerbit($pengajuan, $suratTerbit);
    }

    private function kirimNotifikasiTerbit(PengajuanSurat $pengajuan, SuratTerbit $suratTerbit): void
    {
        if (! $pengajuan->warga?->no_hp) {
            return;
        }

        $tautan = URL::temporarySignedRoute(
            'surat.unduh',
            now()->addDays(7),
            ['surat' => $suratTerbit->id]
        );

        $template = TemplatePesan::where('kode', 'SURAT_TERBIT')->where('is_active', true)->first();

        $pesan = $template
            ? $template->render([
                'nama' => $pengajuan->warga->name,
                'jenis_surat' => $pengajuan->jenisSurat->nama,
                'nomor_surat' => $suratTerbit->nomor_surat,
                'tautan' => $tautan,
            ])
            : "Surat Anda ({$pengajuan->jenisSurat->nama}) telah terbit. Nomor: {$suratTerbit->nomor_surat}. Unduh: {$tautan}";

        KirimNotifikasiWhatsappJob::dispatch(
            noHp: $pengajuan->warga->no_hp,
            pesan: $pesan,
            userId: $pengajuan->user_id,
            pengajuanSuratId: $pengajuan->id,
            templatePesanId: $template?->id,
        );
    }
}
