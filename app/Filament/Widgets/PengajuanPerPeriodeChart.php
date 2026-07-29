<?php

namespace App\Filament\Widgets;

use App\Models\PengajuanSurat;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PengajuanPerPeriodeChart extends ChartWidget
{
    protected ?string $heading = 'Tren Pengajuan Surat Bulanan (6 Bulan Terakhir)';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $months = [];
        $counts = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M Y');
            $months[] = $monthName;

            $counts[] = PengajuanSurat::whereYear('tanggal_pengajuan', $date->year)
                ->whereMonth('tanggal_pengajuan', $date->month)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pengajuan Surat',
                    'data' => $counts,
                    'borderColor' => '#059669',
                    'backgroundColor' => 'rgba(5, 150, 105, 0.15)',
                    'fill' => true,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
