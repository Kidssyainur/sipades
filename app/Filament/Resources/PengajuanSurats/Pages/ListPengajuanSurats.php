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
            'perlu_tindakan' => Tab::make('Perlu Tindakan')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [
                    StatusPengajuan::DIAJUKAN->value,
                    StatusPengajuan::DIVERIFIKASI_PETUGAS->value,
                    StatusPengajuan::DISETUJUI_SEKRETARIS->value,
                ])),
            'direvisi' => Tab::make('Revisi')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusPengajuan::DIREVISI->value)),
            'selesai' => Tab::make('Selesai')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusPengajuan::SELESAI->value)),
            'ditolak' => Tab::make('Ditolak')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', StatusPengajuan::DITOLAK->value)),
        ];
    }
}
