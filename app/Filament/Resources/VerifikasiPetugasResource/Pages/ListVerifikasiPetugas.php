<?php

namespace App\Filament\Resources\VerifikasiPetugasResource\Pages;

use App\Filament\Resources\VerifikasiPetugasResource;
use Filament\Resources\Pages\ListRecords;

class ListVerifikasiPetugas extends ListRecords
{
    protected static string $resource = VerifikasiPetugasResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
