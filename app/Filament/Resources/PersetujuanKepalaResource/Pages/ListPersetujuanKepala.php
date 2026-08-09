<?php

namespace App\Filament\Resources\PersetujuanKepalaResource\Pages;

use App\Filament\Resources\PersetujuanKepalaResource;
use Filament\Resources\Pages\ListRecords;

class ListPersetujuanKepala extends ListRecords
{
    protected static string $resource = PersetujuanKepalaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
