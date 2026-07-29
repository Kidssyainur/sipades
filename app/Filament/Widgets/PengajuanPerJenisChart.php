<?php

namespace App\Filament\Widgets;

use App\Models\JenisSurat;
use Filament\Widgets\ChartWidget;

class PengajuanPerJenisChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Pengajuan per Jenis Surat';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $jenisList = JenisSurat::withCount('pengajuanSurat')->get();

        $labels = $jenisList->pluck('nama')->toArray();
        $counts = $jenisList->pluck('pengajuan_surat_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pengajuan',
                    'data' => $counts,
                    'backgroundColor' => [
                        '#10b981', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6', '#14b8a6', '#f97316',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
