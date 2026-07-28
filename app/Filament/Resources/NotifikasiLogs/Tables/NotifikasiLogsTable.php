<?php

namespace App\Filament\Resources\NotifikasiLogs\Tables;

use App\Jobs\KirimNotifikasiWhatsappJob;
use App\Models\NotifikasiLog;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NotifikasiLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('no_hp_tujuan')
                    ->label('No. HP Tujuan')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('user.name')
                    ->label('Penerima')
                    ->placeholder('—')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('pengajuanSurat.id')
                    ->label('ID Pengajuan')
                    ->prefix('#')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('pesan')
                    ->label('Pesan')
                    ->limit(50)
                    ->tooltip(fn (NotifikasiLog $record): ?string => $record->pesan)
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'terkirim' => 'success',
                        'gagal' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('percobaan')
                    ->label('Percobaan')
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('dikirim_pada')
                    ->label('Terkirim Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'terkirim' => 'Terkirim',
                        'gagal' => 'Gagal',
                    ]),
            ])
            ->recordActions([
                Action::make('kirim_ulang')
                    ->label('Kirim Ulang')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Ulang Notifikasi')
                    ->modalDescription('Pesan yang sama akan dikirim ulang ke nomor tujuan melalui gateway WhatsApp.')
                    ->visible(fn (NotifikasiLog $record): bool => $record->status === 'gagal')
                    ->action(function (NotifikasiLog $record): void {
                        KirimNotifikasiWhatsappJob::dispatch(
                            noHp: $record->no_hp_tujuan,
                            pesan: $record->pesan,
                            userId: $record->user_id,
                            pengajuanSuratId: $record->pengajuan_surat_id,
                            templatePesanId: $record->template_pesan_id,
                        );

                        Notification::make()
                            ->title('Notifikasi dijadwalkan ulang')
                            ->body('Pesan telah dimasukkan ke antrian pengiriman.')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([]);
    }
}
