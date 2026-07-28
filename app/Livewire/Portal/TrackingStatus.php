<?php

namespace App\Livewire\Portal;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.portal')]
#[Title('Lacak Status')]
class TrackingStatus extends Component
{
    public PengajuanSurat $pengajuan;

    public function mount(PengajuanSurat $pengajuan): void
    {
        // Warga hanya boleh melihat pengajuannya sendiri.
        abort_unless($pengajuan->user_id === Auth::id(), 403);

        $pengajuan->load(['jenisSurat', 'approvalLogs.approver', 'suratTerbit']);

        $this->pengajuan = $pengajuan;
    }

    public function getTautanUnduhProperty(): ?string
    {
        $surat = $this->pengajuan->suratTerbit;

        if (! $surat) {
            return null;
        }

        return URL::temporarySignedRoute('surat.unduh', now()->addMinutes(15), ['surat' => $surat->id]);
    }

    /**
     * Timeline langkah approval untuk ditampilkan ke warga.
     *
     * @return array<int, array{label: string, selesai: bool, aktif: bool}>
     */
    public function getTimelineProperty(): array
    {
        $status = $this->pengajuan->status;
        $level = $this->pengajuan->current_level;

        if ($status === StatusPengajuan::DITOLAK) {
            return [
                ['label' => 'Diajukan', 'selesai' => true, 'aktif' => false],
                ['label' => 'Ditolak', 'selesai' => true, 'aktif' => true],
            ];
        }

        $tercapai = match (true) {
            $status === StatusPengajuan::SELESAI => 4,
            $status === StatusPengajuan::DISETUJUI_KEPALA => 3,
            $status === StatusPengajuan::DISETUJUI_SEKRETARIS => 3,
            $status === StatusPengajuan::DIVERIFIKASI_PETUGAS => 2,
            default => 1,
        };

        $langkah = ['Diajukan', 'Verifikasi Petugas', 'Persetujuan Sekretaris', 'Persetujuan Kepala Desa & Terbit'];

        return collect($langkah)->map(fn ($label, $i) => [
            'label' => $label,
            'selesai' => ($i + 1) < $tercapai || $status === StatusPengajuan::SELESAI,
            'aktif' => ($i + 1) === $tercapai && $status !== StatusPengajuan::SELESAI,
        ])->all();
    }

    public function render()
    {
        return view('livewire.portal.tracking-status');
    }
}
