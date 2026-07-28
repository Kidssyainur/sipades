<?php

namespace App\Filament\Widgets;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class PengajuanPerJenisChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected ?string $heading = 'Pengajuan per Jenis Surat';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '320px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $counts = $this->query()
            ->selectRaw('jenis_surat_id, COUNT(*) as agregat')
            ->groupBy('jenis_surat_id')
            ->pluck('agregat', 'jenis_surat_id');

        $jenisSurat = JenisSurat::query()
            ->orderBy('nama')
            ->get(['id', 'nama']);

        $labels = [];
        $data = [];

        foreach ($jenisSurat as $jenis) {
            $labels[] = $jenis->nama;
            $data[] = (int) ($counts[$jenis->id] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pengajuan',
                    'data' => $data,
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#d97706',
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
            'plugins' => [
                'legend' => ['display' => false],
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
