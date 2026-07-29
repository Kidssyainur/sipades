<?php

namespace App\Filament\Resources\NotifikasiLogs;

use App\Filament\Resources\NotifikasiLogs\Pages\ListNotifikasiLogs;
use App\Filament\Resources\NotifikasiLogs\Tables\NotifikasiLogsTable;
use App\Models\NotifikasiLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class NotifikasiLogResource extends Resource
{
    protected static ?string $model = NotifikasiLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Log Notifikasi';

    protected static ?string $modelLabel = 'Log Notifikasi';

    protected static ?string $pluralModelLabel = 'Log Notifikasi';

    protected static string|UnitEnum|null $navigationGroup = 'WhatsApp Gateway';

    protected static ?int $navigationSort = 10;

    public static function table(Table $table): Table
    {
        return NotifikasiLogsTable::configure($table);
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
            'index' => ListNotifikasiLogs::route('/'),
        ];
    }

    /**
     * Log dibuat otomatis oleh KirimNotifikasiWhatsappJob (§11.6) — panel hanya
     * untuk pemantauan & kirim ulang, tidak dibuat/diedit manual.
     */
    public static function canCreate(): bool
    {
        return false;
    }
}
