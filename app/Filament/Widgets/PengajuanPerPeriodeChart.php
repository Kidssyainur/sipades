<?php

namespace App\Filament\Widgets;

use App\Models\PengajuanSurat;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class PengajuanPerPeriodeChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected ?string $heading = 'Tren Pengajuan per Bulan';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '320px';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $rows = $this->query()
            ->selectRaw("DATE_FORMAT(tanggal_pengajuan, '%Y-%m') as periode, COUNT(*) as agregat")
            ->groupBy('periode')
            ->orderBy('periode')
            ->pluck('agregat', 'periode');

        $labels = [];
        $data = [];

        foreach ($rows as $periode => $jumlah) {
            $labels[] = Carbon::createFromFormat('Y-m', $periode)
                ->translatedFormat('M Y');
            $data[] = (int) $jumlah;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pengajuan',
                    'data' => $data,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.15)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => ['precision' => 0],
                ],
            ],
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
