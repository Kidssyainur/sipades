<?php

namespace App\Filament\Resources\SuratTerbits\Tables;

use App\Models\SuratTerbit;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class SuratTerbitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row_number')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('nomor_surat')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('pengajuanSurat.jenisSurat.nama')
                    ->label('Jenis Surat')
                    ->badge()
                    ->searchable(),
                TextColumn::make('pengajuanSurat.warga.name')
                    ->label('Pemohon')
                    ->searchable()
                    ->description(fn (SuratTerbit $record): ?string => $record->pengajuanSurat?->warga?->nik),
                TextColumn::make('penerbit.name')
                    ->label('Diterbitkan Oleh')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('tanggal_terbit')
                    ->label('Tanggal Terbit')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Filter::make('tanggal_terbit')
                    ->schema([
                        DatePicker::make('dari')->label('Dari Tanggal'),
                        DatePicker::make('sampai')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['dari'] ?? null, fn (Builder $q, $tgl) => $q->whereDate('tanggal_terbit', '>=', $tgl))
                            ->when($data['sampai'] ?? null, fn (Builder $q, $tgl) => $q->whereDate('tanggal_terbit', '<=', $tgl));
                    }),
            ])
            ->recordActionsColumnLabel('Aksi')
            ->recordActions([
                Action::make('unduh')
                    ->label('Unduh PDF')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->color('success')
                    ->url(fn (SuratTerbit $record): string => URL::temporarySignedRoute(
                        'surat.unduh',
                        now()->addMinutes(15),
                        ['surat' => $record->id],
                    ))
                    ->openUrlInNewTab()
                    ->visible(fn (SuratTerbit $record): bool => filled($record->file_path)
                        && Storage::disk('local')->exists($record->file_path)),
            ])
            ->toolbarActions([]);
    }
}
