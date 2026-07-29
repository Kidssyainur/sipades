<?php

namespace App\Filament\Resources\TemplatePesans\Pages;

use App\Filament\Resources\TemplatePesans\TemplatePesanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTemplatePesan extends EditRecord
{
    protected static string $resource = TemplatePesanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
