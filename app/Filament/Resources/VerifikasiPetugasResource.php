<?php

namespace App\Filament\Resources;

use App\Enums\StatusPengajuan;
use App\Filament\Resources\PengajuanSurats\Pages\ViewPengajuanSurat;
use App\Filament\Resources\PengajuanSurats\Schemas\PengajuanSuratInfolist;
use App\Filament\Resources\PengajuanSurats\Tables\PengajuanSuratsTable;
use App\Filament\Resources\VerifikasiPetugasResource\Pages\ListVerifikasiPetugas;
use App\Models\PengajuanSurat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class VerifikasiPetugasResource extends Resource
{
    protected static ?string $model = PengajuanSurat::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $navigationLabel = 'Verifikasi Petugas';

    protected static ?string $modelLabel = 'Verifikasi Petugas';

    protected static ?string $pluralModelLabel = 'Verifikasi Petugas';

    protected static string|UnitEnum|null $navigationGroup = 'Pelayanan Surat';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', StatusPengajuan::DIAJUKAN->value);
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
            'index' => ListVerifikasiPetugas::route('/'),
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

        return $user->hasRole('admin') || $user->can('approve_level_1') || $user->hasRole('petugas');
    }
}
