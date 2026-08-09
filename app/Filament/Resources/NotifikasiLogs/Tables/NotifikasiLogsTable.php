<?php

namespace App\Filament\Resources\NotifikasiLogs\Tables;

use App\Jobs\KirimNotifikasiWhatsappJob;
use App\Models\NotifikasiLog;
use App\Services\WhatsappGatewayService;
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
                    ->label('Waktu Log')
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
                    ->label('Isi Pesan WA')
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
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),
                TextColumn::make('percobaan')
                    ->label('Retry')
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
                    ->label('Kirim Ulang (Retry)')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Ulang Notifikasi WA')
                    ->modalDescription('Pesan akan dicoba kirim ulang secara langsung melalui WhatsApp Gateway.')
                    ->visible(fn (NotifikasiLog $record): bool => in_array($record->status, ['gagal', 'pending'], true))
                    ->action(function (NotifikasiLog $record, WhatsappGatewayService $gateway): void {
                        try {
                            $hasil = $gateway->send($record->no_hp_tujuan, $record->pesan);

                            $record->update([
                                'status' => $hasil['sukses'] ? 'terkirim' : 'gagal',
                                'percobaan' => $record->percobaan + 1,
                                'response_gateway' => is_string($hasil['body']) ? $hasil['body'] : json_encode($hasil['body']),
                                'dikirim_pada' => $hasil['sukses'] ? now() : null,
                            ]);

                            if ($hasil['sukses']) {
                                Notification::make()
                                    ->title('Pesan Berhasil Dikirim Ulang')
                                    ->body('WhatsApp Gateway merespons sukses.')
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Pengiriman Ulang Gagal')
                                    ->body('WhatsApp Gateway status: ' . $hasil['status_code'])
                                    ->danger()
                                    ->send();
                            }
                        } catch (\Throwable $e) {
                            $record->increment('percobaan');
                            $record->update(['response_gateway' => $e->getMessage()]);

                            Notification::make()
                                ->title('Gagal Mengirim Ulang')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->toolbarActions([]);
    }
}
