<?php

namespace App\Filament\Resources\WaMessageResource\Pages;

use App\Filament\Resources\WaMessageResource;
use Filament\Resources\Pages\ListRecords;

class ListWaMessages extends ListRecords
{
    protected static string $resource = WaMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
