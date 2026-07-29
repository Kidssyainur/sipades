<?php

namespace App\Filament\Resources\TemplatePesans\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TemplatePesanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')
                    ->label('Kode Template')
                    ->required()
                    ->maxLength(40)
                    ->unique(ignoreRecord: true)
                    ->helperText('Contoh: PENGAJUAN_DITERIMA, REVISI_DIMINTA, SURAT_TERBIT'),
                TextInput::make('judul')
                    ->label('Judul / Deskripsi Template')
                    ->required()
                    ->maxLength(255),
                Textarea::make('isi_template')
                    ->label('Isi Pesan Template')
                    ->required()
                    ->rows(5)
                    ->helperText('Placeholder tersedia: {nama}, {nomor_referensi}, {jenis_surat}, {catatan}, {nomor_surat}, {tautan}'),
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
            ]);
    }
}
