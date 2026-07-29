<?php

namespace App\Filament\Widgets;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanSurat;
use App\Models\SuratTerbit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PengajuanStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $level1 = PengajuanSurat::where('status', StatusPengajuan::DIAJUKAN)->count();
        $level2 = PengajuanSurat::where('status', StatusPengajuan::DIVERIFIKASI_PETUGAS)->count();
        $level3 = PengajuanSurat::where('status', StatusPengajuan::DISETUJUI_SEKRETARIS)->count();
        $totalTerbit = SuratTerbit::count();

        return [
            Stat::make('Antrian Level 1 (Petugas)', $level1)
                ->description('Diajukan oleh warga')
                ->descriptionIcon('heroicon-m-clock')
                ->color($level1 > 0 ? 'warning' : 'gray'),

            Stat::make('Antrian Level 2 (Sekdes)', $level2)
                ->description('Diverifikasi oleh Petugas')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color($level2 > 0 ? 'info' : 'gray'),

            Stat::make('Antrian Level 3 (Kepala Desa)', $level3)
                ->description('Disetujui Sekdes & Siap TTE')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color($level3 > 0 ? 'primary' : 'gray'),

            Stat::make('Surat Terbit (Selesai)', $totalTerbit)
                ->description('Telah terbit & TTE')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
