<?php

namespace App\Filament\Resources\TemplatePesans\Pages;

use App\Filament\Resources\TemplatePesans\TemplatePesanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTemplatePesans extends ListRecords
{
    protected static string $resource = TemplatePesanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
