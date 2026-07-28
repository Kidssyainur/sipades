<?php

namespace App\Filament\Widgets;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanSurat;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class StatistikPengajuanOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $total = $this->query()->count();

        $dalamProses = $this->query()
            ->whereIn('status', [
                StatusPengajuan::DIAJUKAN->value,
                StatusPengajuan::DIVERIFIKASI_PETUGAS->value,
                StatusPengajuan::DISETUJUI_SEKRETARIS->value,
                StatusPengajuan::DISETUJUI_KEPALA->value,
                StatusPengajuan::DIREVISI->value,
            ])
            ->count();

        $selesai = $this->query()
            ->where('status', StatusPengajuan::SELESAI->value)
            ->count();

        $ditolak = $this->query()
            ->where('status', StatusPengajuan::DITOLAK->value)
            ->count();

        return [
            Stat::make('Total Pengajuan', number_format($total, 0, ',', '.'))
                ->description('Seluruh pengajuan pada periode')
                ->descriptionIcon(Heroicon::OutlinedClipboardDocumentList)
                ->color('primary'),

            Stat::make('Dalam Proses', number_format($dalamProses, 0, ',', '.'))
                ->description('Sedang diverifikasi / disetujui')
                ->descriptionIcon(Heroicon::OutlinedClock)
                ->color('warning'),

            Stat::make('Selesai', number_format($selesai, 0, ',', '.'))
                ->description('Surat telah terbit')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle)
                ->color('success'),

            Stat::make('Ditolak', number_format($ditolak, 0, ',', '.'))
                ->description('Pengajuan ditolak')
                ->descriptionIcon(Heroicon::OutlinedXCircle)
                ->color('danger'),
        ];
    }

    protected function query(): Builder
    {
        $query = PengajuanSurat::query();

        $dari = $this->pageFilters['dari'] ?? null;
        $sampai = $this->pageFilters['sampai'] ?? null;
        $jenisSuratId = $this->pageFilters['jenis_surat_id'] ?? null;

        if ($dari) {
            $query->whereDate('tanggal_pengajuan', '>=', $dari);
        }

        if ($sampai) {
            $query->whereDate('tanggal_pengajuan', '<=', $sampai);
        }

        if ($jenisSuratId) {
            $query->where('jenis_surat_id', $jenisSuratId);
        }

        return $query;
    }
}
