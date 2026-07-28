<?php

namespace App\Filament\Resources\SuratTerbits\Pages;

use App\Filament\Resources\SuratTerbits\SuratTerbitResource;
use Filament\Resources\Pages\ListRecords;

class ListSuratTerbits extends ListRecords
{
    protected static string $resource = SuratTerbitResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
