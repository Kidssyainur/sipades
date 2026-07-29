<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\PengajuanPerJenisChart;
use App\Filament\Widgets\PengajuanPerPeriodeChart;
use App\Filament\Widgets\StatistikPengajuanOverview;
use App\Models\JenisSurat;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class Laporan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Arsip & Laporan';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $title = 'Laporan Rekapitulasi Pengajuan';

    protected string $view = 'filament.pages.laporan';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();

        return $user?->hasAnyRole(['admin', 'kepala_desa']) ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Filter Periode')
                    ->description('Saring rekapitulasi berdasarkan rentang tanggal dan jenis surat.')
                    ->schema([
                        DatePicker::make('dari')
                            ->label('Tanggal Dari')
                            ->native(false)
                            ->maxDate(now())
                            ->live(),
                        DatePicker::make('sampai')
                            ->label('Tanggal Sampai')
                            ->native(false)
                            ->maxDate(now())
                            ->live(),
                        Select::make('jenis_surat_id')
                            ->label('Jenis Surat')
                            ->placeholder('Semua Jenis Surat')
                            ->options(fn (): array => JenisSurat::query()
                                ->orderBy('nama')
                                ->pluck('nama', 'id')
                                ->all())
                            ->searchable()
                            ->live(),
                    ])
                    ->columns(3),
            ]);
    }

    public function getWidgetData(): array
    {
        return [
            'pageFilters' => $this->data,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            StatistikPengajuanOverview::class,
            PengajuanPerJenisChart::class,
            PengajuanPerPeriodeChart::class,
        ];
    }
}
