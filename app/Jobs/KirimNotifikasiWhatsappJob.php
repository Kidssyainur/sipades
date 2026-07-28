<?php

namespace App\Jobs;

use App\Models\NotifikasiLog;
use App\Services\WhatsappGatewayService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class KirimNotifikasiWhatsappJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    /** @var array<int, int> Jeda retry: 1 menit, 5 menit, 15 menit */
    public array $backoff = [60, 300, 900];

    public function __construct(
        public string $noHp,
        public string $pesan,
        public ?int $userId = null,
        public ?int $pengajuanSuratId = null,
        public ?int $templatePesanId = null,
    ) {}

    public function handle(WhatsappGatewayService $gateway): void
    {
        $log = NotifikasiLog::create([
            'user_id' => $this->userId,
            'pengajuan_surat_id' => $this->pengajuanSuratId,
            'template_pesan_id' => $this->templatePesanId,
            'no_hp_tujuan' => $this->noHp,
            'pesan' => $this->pesan,
            'status' => 'pending',
            'percobaan' => 0,
        ]);

        $hasil = $gateway->send($this->noHp, $this->pesan);

        $log->update([
            'status' => $hasil['sukses'] ? 'terkirim' : 'gagal',
            'percobaan' => $log->percobaan + 1,
            'response_gateway' => $hasil['body'],
            'dikirim_pada' => $hasil['sukses'] ? now() : null,
        ]);

        if (! $hasil['sukses']) {
            // Memicu retry sesuai $backoff, tercatat di failed_jobs setelah 3x gagal.
            $this->fail(new \RuntimeException('Gateway WhatsApp gagal mengirim pesan.'));
        }
    }
}
