<?php

namespace App\Filament\Resources\PengajuanSurats\Pages;

use App\Filament\Resources\PengajuanSurats\PengajuanSuratResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewPengajuanSurat extends ViewRecord
{
    protected static string $resource = PengajuanSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn (): string => $this->getResource()::getUrl('index')),
        ];
    }
}
