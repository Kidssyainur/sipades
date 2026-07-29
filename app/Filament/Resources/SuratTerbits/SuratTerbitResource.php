<?php

namespace App\Filament\Resources\SuratTerbits;

use App\Filament\Resources\SuratTerbits\Pages\ListSuratTerbits;
use App\Filament\Resources\SuratTerbits\Tables\SuratTerbitsTable;
use App\Models\SuratTerbit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SuratTerbitResource extends Resource
{
    protected static ?string $model = SuratTerbit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static ?string $navigationLabel = 'Surat Terbit';

    protected static ?string $modelLabel = 'Surat Terbit';

    protected static ?string $pluralModelLabel = 'Surat Terbit';

    protected static string|UnitEnum|null $navigationGroup = 'Pelayanan Surat';

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return SuratTerbitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSuratTerbits::route('/'),
        ];
    }

    /**
     * Arsip surat lahir dari TerbitkanSuratJob (§11.5) — panel hanya untuk
     * rekap & unduh, tidak boleh dibuat/diubah manual.
     */
    public static function canCreate(): bool
    {
        return false;
    }
}
