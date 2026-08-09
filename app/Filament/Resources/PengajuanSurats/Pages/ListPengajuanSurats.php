<?php

namespace App\Filament\Resources\PengajuanSurats\Pages;

use App\Enums\StatusPengajuan;
use App\Filament\Resources\PengajuanSurats\PengajuanSuratResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPengajuanSurats extends ListRecords
{
    protected static string $resource = PengajuanSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    /**
     * Tab cepat berdasarkan status — mempermudah approver menyaring antrian.
     *
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua'),
            'diajukan' => Tab::make('Diajukan (Baru)')
                ->badge(\App\Models\PengajuanSurat::where('status', StatusPengajuan::DIAJUKAN->value)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusPengajuan::DIAJUKAN->value)),
            'verifikasi_petugas' => Tab::make('Diverifikasi Petugas')
                ->badge(\App\Models\PengajuanSurat::where('status', StatusPengajuan::DIVERIFIKASI_PETUGAS->value)->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusPengajuan::DIVERIFIKASI_PETUGAS->value)),
            'disetujui_sekretaris' => Tab::make('Disetujui Sekdes')
                ->badge(\App\Models\PengajuanSurat::where('status', StatusPengajuan::DISETUJUI_SEKRETARIS->value)->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusPengajuan::DISETUJUI_SEKRETARIS->value)),
            'direvisi' => Tab::make('Revisi')
                ->badge(\App\Models\PengajuanSurat::where('status', StatusPengajuan::DIREVISI->value)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusPengajuan::DIREVISI->value)),
            'selesai' => Tab::make('Selesai')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusPengajuan::SELESAI->value)),
            'ditolak' => Tab::make('Ditolak')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusPengajuan::DITOLAK->value)),
        ];
    }
}
