<?php

namespace App\Filament\Resources\TemplatePesans;

use App\Filament\Resources\TemplatePesans\Pages\CreateTemplatePesan;
use App\Filament\Resources\TemplatePesans\Pages\EditTemplatePesan;
use App\Filament\Resources\TemplatePesans\Pages\ListTemplatePesans;
use App\Filament\Resources\TemplatePesans\Schemas\TemplatePesanForm;
use App\Filament\Resources\TemplatePesans\Tables\TemplatePesansTable;
use App\Models\TemplatePesan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TemplatePesanResource extends Resource
{
    protected static ?string $model = TemplatePesan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected static ?string $navigationLabel = 'Template Pesan WA';

    protected static ?string $modelLabel = 'Template Pesan';

    protected static ?string $pluralModelLabel = 'Template Pesan WA';

    protected static string|UnitEnum|null $navigationGroup = 'WhatsApp Gateway';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return TemplatePesanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TemplatePesansTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTemplatePesans::route('/'),
            'create' => CreateTemplatePesan::route('/create'),
            'edit' => EditTemplatePesan::route('/{record}/edit'),
        ];
    }
}
