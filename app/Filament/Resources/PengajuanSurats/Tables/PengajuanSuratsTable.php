<?php

namespace App\Filament\Resources\PengajuanSurats\Tables;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanSurat;
use App\Models\User;
use App\Services\ApprovalService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PengajuanSuratsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_referensi')
                    ->label('No. Referensi')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('warga.name')
                    ->label('Pemohon')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenisSurat.nama')
                    ->label('Jenis Surat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (StatusPengajuan $state): string => $state->label())
                    ->color(fn (StatusPengajuan $state): string => $state->color()),
                TextColumn::make('current_level')
                    ->label('Level')
                    ->badge()
                    ->sortable(),
                TextColumn::make('tanggal_pengajuan')
                    ->label('Diajukan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('—'),
            ])
            ->defaultSort('tanggal_pengajuan', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(collect(StatusPengajuan::cases())
                        ->mapWithKeys(fn (StatusPengajuan $s): array => [$s->value => $s->label()])
                        ->all()),
                SelectFilter::make('jenis_surat_id')
                    ->label('Jenis Surat')
                    ->relationship('jenisSurat', 'nama'),
            ])
            ->recordActions(self::recordActions());
    }

    /**
     * Aksi per-baris: lihat detail + keputusan approval kustom (§11.4).
     *
     * @return array<int, mixed>
     */
    protected static function recordActions(): array
    {
        return [
            ViewAction::make(),
            ActionGroup::make([
                self::aksiSetujui(),
                self::aksiRevisi(),
                self::aksiTolak(),
            ])
                ->label('Keputusan')
                ->button()
                ->visible(function (PengajuanSurat $record): bool {
                    $approver = self::approver();

                    return $approver !== null
                        && app(ApprovalService::class)->bolehApprove($record, $approver);
                }),
        ];
    }

    protected static function aksiSetujui(): Action
    {
        return Action::make('setujui')
            ->label('Setujui')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->modalWidth(Width::Large)
            ->modalHeading('Setujui Pengajuan')
            ->modalDescription(fn (PengajuanSurat $record): string => $record->current_level === 3
                ? 'Persetujuan Kepala Desa akan menandatangani & menerbitkan surat secara otomatis.'
                : 'Pengajuan akan diteruskan ke tahap approval berikutnya.')
            ->schema([
                Textarea::make('catatan')
                    ->label('Catatan (opsional)')
                    ->rows(3),
            ])
            ->action(function (PengajuanSurat $record, array $data): void {
                self::jalankan(
                    fn (ApprovalService $svc, User $u) => $svc->setujui($record, $u, $data['catatan'] ?? null),
                    'Pengajuan disetujui.'
                );
            });
    }

    protected static function aksiRevisi(): Action
    {
        return Action::make('revisi')
            ->label('Minta Revisi')
            ->icon('heroicon-o-pencil-square')
            ->color('warning')
            ->modalWidth(Width::Large)
            ->schema([
                Textarea::make('catatan')
                    ->label('Catatan revisi')
                    ->required()
                    ->rows(3)
                    ->helperText('Dikirim ke warga sebagai instruksi perbaikan.'),
            ])
            ->action(function (PengajuanSurat $record, array $data): void {
                self::jalankan(
                    fn (ApprovalService $svc, User $u) => $svc->mintaRevisi($record, $u, $data['catatan']),
                    'Permintaan revisi dikirim ke warga.'
                );
            });
    }

    protected static function aksiTolak(): Action
    {
        return Action::make('tolak')
            ->label('Tolak')
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->requiresConfirmation()
            ->modalWidth(Width::Large)
            ->schema([
                Textarea::make('alasan')
                    ->label('Alasan penolakan')
                    ->required()
                    ->rows(3),
            ])
            ->action(function (PengajuanSurat $record, array $data): void {
                self::jalankan(
                    fn (ApprovalService $svc, User $u) => $svc->tolak($record, $u, $data['alasan']),
                    'Pengajuan ditolak.'
                );
            });
    }

    /**
     * Jalankan closure keputusan dengan approver aktif, tangani error jadi notifikasi.
     *
     * @param  \Closure(ApprovalService, User): mixed  $callback
     */
    protected static function jalankan(\Closure $callback, string $pesanSukses): void
    {
        $approver = self::approver();

        if (! $approver) {
            Notification::make()->title('Sesi tidak valid.')->danger()->send();

            return;
        }

        try {
            $callback(app(ApprovalService::class), $approver);
            Notification::make()->title($pesanSukses)->success()->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal memproses keputusan')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected static function approver(): ?User
    {
        return auth()->user();
    }
}
