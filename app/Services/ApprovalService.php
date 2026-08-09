<?php

namespace App\Services;

use App\Enums\StatusPengajuan;
use App\Jobs\KirimNotifikasiWhatsappJob;
use App\Jobs\TerbitkanSuratJob;
use App\Models\ApprovalLog;
use App\Models\PengajuanSurat;
use App\Models\TemplatePesan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use InvalidArgumentException;

/**
 * State machine approval multi-level (fleksibel 1, 2, atau 3 level per jenis surat).
 */
class ApprovalService
{
    /** Permission approval yang dibutuhkan per level. */
    private const PERMISSION_LEVEL = [
        1 => 'approve_level_1',
        2 => 'approve_level_2',
        3 => 'approve_level_3_sign',
    ];

    /**
     * Approver menyetujui pengajuan pada level saat ini.
     */
    public function setujui(PengajuanSurat $pengajuan, User $approver, ?string $catatan = null): PengajuanSurat
    {
        $pengajuan->loadMissing('jenisSurat');
        $this->pastikanBolehApprove($pengajuan, $approver);

        $level = $pengajuan->current_level;
        $maxLevel = $pengajuan->jenisSurat->jumlah_level_approval ?? 3;
        $butuhTte = $pengajuan->jenisSurat->butuh_tte_kades ?? true;

        return DB::transaction(function () use ($pengajuan, $approver, $level, $maxLevel, $butuhTte, $catatan) {
            $isFinalLevel = ($level >= $maxLevel);

            $this->catatKeputusan(
                $pengajuan,
                $approver,
                'setuju',
                $catatan,
                tandatangan: $isFinalLevel && $butuhTte
            );

            if ($isFinalLevel) {
                // Pengajuan disetujui penuh pada level maksimum jenis surat ini
                $pengajuan->update([
                    'status' => StatusPengajuan::DISETUJUI_KEPALA,
                ]);
            } else {
                // Lanjut ke level berikutnya
                $nextLevel = $level + 1;
                $nextStatus = match ($nextLevel) {
                    2 => StatusPengajuan::DIVERIFIKASI_PETUGAS,
                    3 => StatusPengajuan::DISETUJUI_SEKRETARIS,
                    default => StatusPengajuan::DIVERIFIKASI_PETUGAS,
                };

                $pengajuan->update([
                    'status' => $nextStatus,
                    'current_level' => $nextLevel,
                ]);
            }

            $pengajuan->refresh();

            // Notifikasi hasil persetujuan per level
            $kodeTemplate = match ($level) {
                1 => $isFinalLevel ? null : 'DISETUJUI_PETUGAS',
                2 => $isFinalLevel ? null : 'DISETUJUI_SEKRETARIS',
                default => null,
            };

            if ($kodeTemplate !== null) {
                $this->kirimNotifikasi($pengajuan, $kodeTemplate);
            }

            // Jika level final → terbitkan surat PDF & TTE
            if ($isFinalLevel) {
                TerbitkanSuratJob::dispatchSync(
                    pengajuanSuratId: $pengajuan->id,
                    diterbitkanOleh: $approver->id,
                );
            }

            return $pengajuan;
        });
    }

    /**
     * Approver meminta revisi. Status → direvisi, current_level dikembalikan ke 1.
     */
    public function mintaRevisi(PengajuanSurat $pengajuan, User $approver, string $catatan): PengajuanSurat
    {
        $this->pastikanBolehApprove($pengajuan, $approver);

        return DB::transaction(function () use ($pengajuan, $approver, $catatan) {
            $this->catatKeputusan($pengajuan, $approver, 'revisi', $catatan);

            $pengajuan->update([
                'status' => StatusPengajuan::DIREVISI,
                'current_level' => 1,
                'catatan_revisi' => $catatan,
            ]);

            $pengajuan->refresh();
            $this->kirimNotifikasi($pengajuan, 'REVISI_DIMINTA');

            return $pengajuan;
        });
    }

    /**
     * Approver menolak pengajuan (final, gagal).
     */
    public function tolak(PengajuanSurat $pengajuan, User $approver, string $alasan): PengajuanSurat
    {
        $this->pastikanBolehApprove($pengajuan, $approver);

        return DB::transaction(function () use ($pengajuan, $approver, $alasan) {
            $this->catatKeputusan($pengajuan, $approver, 'tolak', $alasan);

            $pengajuan->update([
                'status' => StatusPengajuan::DITOLAK,
                'alasan_penolakan' => $alasan,
            ]);

            $pengajuan->refresh();
            $this->kirimNotifikasi($pengajuan, 'DITOLAK');

            return $pengajuan;
        });
    }

    /**
     * Cek apakah user boleh mengambil keputusan approval untuk pengajuan ini.
     */
    public function bolehApprove(PengajuanSurat $pengajuan, User $approver): bool
    {
        if (! $this->statusMenunggu($pengajuan)) {
            return false;
        }

        $permission = self::PERMISSION_LEVEL[$pengajuan->current_level] ?? null;

        return $permission !== null && $approver->can($permission);
    }

    private function pastikanBolehApprove(PengajuanSurat $pengajuan, User $approver): void
    {
        if (! $this->statusMenunggu($pengajuan)) {
            throw new InvalidArgumentException(
                'Pengajuan tidak dalam status yang menunggu keputusan approval.'
            );
        }

        $permission = self::PERMISSION_LEVEL[$pengajuan->current_level] ?? null;

        if ($permission === null || ! $approver->can($permission)) {
            throw new UnauthorizedException(
                "Anda tidak memiliki izin approval untuk level {$pengajuan->current_level}."
            );
        }
    }

    private function statusMenunggu(PengajuanSurat $pengajuan): bool
    {
        return in_array($pengajuan->status, [
            StatusPengajuan::DIAJUKAN,
            StatusPengajuan::DIVERIFIKASI_PETUGAS,
            StatusPengajuan::DISETUJUI_SEKRETARIS,
        ], true);
    }

    private function catatKeputusan(
        PengajuanSurat $pengajuan,
        User $approver,
        string $keputusan,
        ?string $catatan,
        bool $tandatangan = false,
    ): void {
        ApprovalLog::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'user_id' => $approver->id,
            'level' => $pengajuan->current_level,
            'role_saat_itu' => $approver->getRoleNames()->first() ?? 'staf',
            'keputusan' => $keputusan,
            'catatan' => $catatan,
            'ditandatangani_pada' => $tandatangan ? now() : null,
        ]);
    }

    private function kirimNotifikasi(PengajuanSurat $pengajuan, string $kodeTemplate): void
    {
        $pengajuan->loadMissing(['warga', 'jenisSurat']);

        if (! $pengajuan->warga?->no_hp) {
            return;
        }

        $template = TemplatePesan::where('kode', $kodeTemplate)
            ->where('is_active', true)
            ->first();

        if (! $template) {
            return;
        }

        $pesan = $template->render([
            'nama' => $pengajuan->warga->name,
            'jenis_surat' => $pengajuan->jenisSurat->nama,
            'nomor_referensi' => $pengajuan->nomor_referensi,
            'catatan' => $pengajuan->catatan_revisi ?? $pengajuan->alasan_penolakan ?? '',
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
