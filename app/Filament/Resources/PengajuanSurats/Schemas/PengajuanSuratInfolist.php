<?php

namespace App\Filament\Resources\PengajuanSurats\Schemas;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanSurat;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PengajuanSuratInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ringkasan Pengajuan')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('nomor_referensi')->label('No. Referensi')->copyable(),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (StatusPengajuan $state): string => $state->label())
                            ->color(fn (StatusPengajuan $state): string => $state->color()),
                        TextEntry::make('jenisSurat.nama')->label('Jenis Surat'),
                        TextEntry::make('current_level')->label('Level Approval')->badge(),
                        TextEntry::make('tanggal_pengajuan')->label('Diajukan')->dateTime('d M Y H:i'),
                        TextEntry::make('tanggal_selesai')->label('Selesai')->dateTime('d M Y H:i')->placeholder('—'),
                        TextEntry::make('catatan_revisi')->label('Catatan Revisi')->placeholder('—')->columnSpanFull(),
                        TextEntry::make('alasan_penolakan')->label('Alasan Penolakan')->placeholder('—')->columnSpanFull(),
                    ]),

                Section::make('Data Pemohon')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('warga.name')->label('Nama'),
                        TextEntry::make('warga.nik')->label('NIK'),
                        TextEntry::make('warga.no_hp')->label('No. HP'),
                        TextEntry::make('warga.email')->label('Email'),
                    ]),

                Section::make('Isian Formulir')
                    ->schema([
                        KeyValueEntry::make('data_formulir')
                            ->label('')
                            ->keyLabel('Field')
                            ->valueLabel('Nilai'),
                    ]),

                Section::make('Riwayat Approval')
                    ->schema([
                        RepeatableEntry::make('approvalLogs')
                            ->label('')
                            ->columns(4)
                            ->schema([
                                TextEntry::make('level')->badge(),
                                TextEntry::make('role_saat_itu')->label('Role'),
                                TextEntry::make('keputusan')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'setuju' => 'success',
                                        'revisi' => 'warning',
                                        'tolak' => 'danger',
                                        default => 'gray',
                                    }),
                                TextEntry::make('created_at')->label('Waktu')->dateTime('d M Y H:i'),
                                TextEntry::make('catatan')->placeholder('—')->columnSpanFull(),
                            ]),
                    ])
                    ->visible(fn (PengajuanSurat $record): bool => $record->approvalLogs()->exists()),
            ]);
    }
}
