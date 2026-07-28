<?php

namespace App\Filament\Resources\JenisSurats\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class JenisSuratsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->label('Nama Surat')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('field_formulir')
                    ->label('Jumlah Field')
                    ->badge()
                    ->state(fn ($record): int => is_array($record->field_formulir) ? count($record->field_formulir) : 0),
                TextColumn::make('estimasi_hari')
                    ->label('Estimasi (hari)')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nama')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
