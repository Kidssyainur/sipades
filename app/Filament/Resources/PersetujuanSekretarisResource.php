<?php

namespace App\Filament\Resources;

use App\Enums\StatusPengajuan;
use App\Filament\Resources\PengajuanSurats\Pages\ViewPengajuanSurat;
use App\Filament\Resources\PengajuanSurats\Schemas\PengajuanSuratInfolist;
use App\Filament\Resources\PengajuanSurats\Tables\PengajuanSuratsTable;
use App\Filament\Resources\PersetujuanSekretarisResource\Pages\ListPersetujuanSekretaris;
use App\Models\PengajuanSurat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PersetujuanSekretarisResource extends Resource
{
    protected static ?string $model = PengajuanSurat::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationLabel = 'Persetujuan Sekdes';

    protected static ?string $modelLabel = 'Persetujuan Sekdes';

    protected static ?string $pluralModelLabel = 'Persetujuan Sekdes';

    protected static string|UnitEnum|null $navigationGroup = 'Pelayanan Surat';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', StatusPengajuan::DIVERIFIKASI_PETUGAS->value);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PengajuanSuratInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengajuanSuratsTable::configure($table, includeReference: false, includeLevel: false);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPersetujuanSekretaris::route('/'),
            'view' => ViewPengajuanSurat::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->hasRole('admin') || $user->can('approve_level_2') || $user->hasRole('sekretaris_desa');
    }
}
