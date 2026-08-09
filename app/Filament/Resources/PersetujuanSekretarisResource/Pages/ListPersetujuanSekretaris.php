<?php

namespace App\Filament\Resources\PersetujuanSekretarisResource\Pages;

use App\Filament\Resources\PersetujuanSekretarisResource;
use Filament\Resources\Pages\ListRecords;

class ListPersetujuanSekretaris extends ListRecords
{
    protected static string $resource = PersetujuanSekretarisResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
