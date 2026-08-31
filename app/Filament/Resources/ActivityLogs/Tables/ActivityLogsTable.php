<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('log_name')
                    ->label('Log')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Aktivitas')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('event')
                    ->label('Peristiwa')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    })
                    ->placeholder('—'),

                TextColumn::make('subject_type')
                    ->label('Objek')
                    ->formatStateUsing(fn (?string $state, Activity $record): string => $state
                        ? class_basename($state).($record->subject_id ? ' #'.$record->subject_id : '')
                        : '—')
                    ->toggleable(),

                TextColumn::make('causer.name')
                    ->label('Oleh')
                    ->placeholder('Sistem')
                    ->searchable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('event')
                    ->label('Peristiwa')
                    ->options([
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                    ]),

                SelectFilter::make('log_name')
                    ->label('Nama Log')
                    ->options(fn (): array => Activity::query()
                        ->distinct()
                        ->orderBy('log_name')
                        ->pluck('log_name', 'log_name')
                        ->filter()
                        ->all()),
            ])
            ->recordActions([
                Action::make('detail')
                    ->label('Detail Perubahan')
                    ->icon(Heroicon::OutlinedEye)
                    ->color('gray')
                    ->modalHeading('Detail Perubahan')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn (Activity $record) => view(
                        'filament.activity-log-detail',
                        ['perubahan' => $record->properties?->toArray() ?? []],
                    ))
                    ->visible(fn (Activity $record): bool => ! empty($record->properties?->toArray() ?? [])),
            ])
            ->toolbarActions([]);
    }
}
