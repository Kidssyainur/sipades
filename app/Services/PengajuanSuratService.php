<?php

namespace App\Services;

use App\Enums\StatusPengajuan;
use App\Jobs\KirimNotifikasiWhatsappJob;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Models\TemplatePesan;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PengajuanSuratService
{
    /**
     * Buat pengajuan surat baru dalam satu transaksi — §11.3 poin 4.
     *
     * @param  array<string, mixed>  $dataFormulir
     * @param  array<int, UploadedFile>  $lampiran
     */
    public function ajukan(User $warga, JenisSurat $jenisSurat, array $dataFormulir, array $lampiran = []): PengajuanSurat
    {
        $pengajuan = DB::transaction(function () use ($warga, $jenisSurat, $dataFormulir): PengajuanSurat {
            return PengajuanSurat::create([
                'nomor_referensi' => $this->generateNomorReferensi(),
                'user_id' => $warga->id,
                'jenis_surat_id' => $jenisSurat->id,
                'data_formulir' => $dataFormulir,
                'status' => StatusPengajuan::DIAJUKAN,
                'current_level' => 1,
                'tanggal_pengajuan' => now(),
            ]);
        });

        // Lampiran disimpan setelah transaksi (I/O file) ke media collection 'lampiran'.
        foreach ($lampiran as $file) {
            $pengajuan->addMedia($file->getRealPath())
                ->usingFileName($file->getClientOriginalName())
                ->toMediaCollection('lampiran');
        }

        $this->kirimNotifikasi($pengajuan);

        return $pengajuan;
    }

    /**
     * Warga mengirim ulang pengajuan yang berstatus `direvisi` — FR-04.
     * Data formulir diperbarui, lampiran lama diganti bila ada unggahan baru,
     * status dikembalikan ke `diajukan` pada level 1 agar proses approval diulang.
     *
     * @param  array<string, mixed>  $dataFormulir
     * @param  array<int, UploadedFile>  $lampiran
     */
    public function ajukanUlang(PengajuanSurat $pengajuan, array $dataFormulir, array $lampiran = []): PengajuanSurat
    {
        if ($pengajuan->status !== StatusPengajuan::DIREVISI) {
            throw new \InvalidArgumentException('Hanya pengajuan berstatus revisi yang dapat dikirim ulang.');
        }

        DB::transaction(function () use ($pengajuan, $dataFormulir): void {
            $pengajuan->update([
                'data_formulir' => $dataFormulir,
                'status' => StatusPengajuan::DIAJUKAN,
                'current_level' => 1,
                'catatan_revisi' => null,
                'tanggal_pengajuan' => now(),
            ]);
        });

        // Bila warga mengunggah lampiran baru, ganti seluruh lampiran lama.
        if (! empty($lampiran)) {
            $pengajuan->clearMediaCollection('lampiran');

            foreach ($lampiran as $file) {
                $pengajuan->addMedia($file->getRealPath())
                    ->usingFileName($file->getClientOriginalName())
                    ->toMediaCollection('lampiran');
            }
        }

        $this->kirimNotifikasi($pengajuan);

        return $pengajuan;
    }

    /**
     * Format: REF-{YYYYMMDD}-{urutan 4 digit}, reset harian.
     */
    private function generateNomorReferensi(): string
    {
        $tanggal = now()->format('Ymd');

        $urutan = PengajuanSurat::whereDate('tanggal_pengajuan', now()->toDateString())->count() + 1;

        return sprintf('REF-%s-%04d', $tanggal, $urutan);
    }

    private function kirimNotifikasi(PengajuanSurat $pengajuan): void
    {
        $pengajuan->loadMissing(['warga', 'jenisSurat']);

        if (! $pengajuan->warga?->no_hp) {
            return;
        }

        $template = TemplatePesan::where('kode', 'PENGAJUAN_DITERIMA')
            ->where('is_active', true)
            ->first();

        if (! $template) {
            return;
        }

        $pesan = $template->render([
            'nama' => $pengajuan->warga->name,
            'jenis_surat' => $pengajuan->jenisSurat->nama,
            'nomor_referensi' => $pengajuan->nomor_referensi,
        ]);

        KirimNotifikasiWhatsappJob::dispatch(
            noHp: $pengajuan->warga->no_hp,
            pesan: $pesan,
            userId: $pengajuan->user_id,
            pengajuanSuratId: $pengajuan->id,
            templatePesanId: $template->id,
        );
    }
}
