<?php

namespace App\Filament\Resources\NotifikasiLogs\Pages;

use App\Filament\Resources\NotifikasiLogs\NotifikasiLogResource;
use Filament\Resources\Pages\ListRecords;

class ListNotifikasiLogs extends ListRecords
{
    protected static string $resource = NotifikasiLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
