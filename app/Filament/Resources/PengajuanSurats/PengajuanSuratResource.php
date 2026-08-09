<?php

namespace App\Filament\Resources\PengajuanSurats;

use App\Filament\Resources\PengajuanSurats\Pages\ListPengajuanSurats;
use App\Filament\Resources\PengajuanSurats\Pages\ViewPengajuanSurat;
use App\Filament\Resources\PengajuanSurats\Schemas\PengajuanSuratInfolist;
use App\Filament\Resources\PengajuanSurats\Tables\PengajuanSuratsTable;
use App\Models\PengajuanSurat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PengajuanSuratResource extends Resource
{
    protected static ?string $model = PengajuanSurat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Semua Pengajuan';

    protected static ?string $modelLabel = 'Pengajuan Surat';

    protected static ?string $pluralModelLabel = 'Pengajuan Surat';

    protected static string|UnitEnum|null $navigationGroup = 'Pelayanan Surat';

    protected static ?int $navigationSort = 1;

    public static function infolist(Schema $schema): Schema
    {
        return PengajuanSuratInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengajuanSuratsTable::configure($table, includeReference: false, includeLevel: true);
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
            'index' => ListPengajuanSurats::route('/'),
            'view' => ViewPengajuanSurat::route('/{record}'),
        ];
    }

    /**
     * Staf tidak membuat/mengedit pengajuan lewat panel — pengajuan lahir dari
     * portal warga, keputusan approval lewat Action kustom (§11.4). Panel hanya
     * baca + aksi.
     */
    public static function canCreate(): bool
    {
        return false;
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
