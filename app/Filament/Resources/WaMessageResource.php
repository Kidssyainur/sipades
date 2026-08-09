<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WaMessageResource\Pages\ListWaMessages;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Kstmostofa\LaravelWhatsApp\Models\WaMessage;
use UnitEnum;

class WaMessageResource extends Resource
{
    protected static ?string $model = WaMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Log Messages WhatsApp';

    protected static ?string $modelLabel = 'Pesan WhatsApp';

    protected static ?string $pluralModelLabel = 'Log Messages WhatsApp';

    protected static string|UnitEnum|null $navigationGroup = 'WhatsApp Gateway';

    protected static ?int $navigationSort = 31;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('session_id')
                    ->label('Sesi')
                    ->badge()
                    ->sortable(),
                TextColumn::make('direction')
                    ->label('Arah')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'outbound' => 'success',
                        'inbound' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),
                TextColumn::make('chat_id')
                    ->label('Chat ID')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('body')
                    ->label('Isi Pesan')
                    ->limit(60)
                    ->tooltip(fn (WaMessage $record): ?string => $record->body)
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('ack')
                    ->label('Ack')
                    ->numeric()
                    ->alignCenter()
                    ->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('direction')
                    ->label('Arah')
                    ->options([
                        'inbound' => 'Masuk (Inbound)',
                        'outbound' => 'Keluar (Outbound)',
                    ]),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWaMessages::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
