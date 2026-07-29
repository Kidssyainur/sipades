<?php

namespace App\Filament\Resources\JenisSurats\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class JenisSuratForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Jenis Surat')
                    ->columns(2)
                    ->schema([
                        TextInput::make('kode')
                            ->label('Kode')
                            ->required()
                            ->maxLength(50)
                            ->rule('alpha_dash')
                            ->helperText('Huruf kapital tanpa spasi, contoh: DOMISILI.')
                            ->dehydrateStateUsing(fn (?string $state) => Str::upper((string) $state)),
                        TextInput::make('nama')
                            ->label('Nama Surat')
                            ->required()
                            ->maxLength(150),
                        Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('template_view')
                            ->label('Template Blade')
                            ->maxLength(150)
                            ->placeholder('surat.default')
                            ->helperText('Nama view Blade. Kosongkan untuk memakai surat.default.'),
                        TextInput::make('estimasi_hari')
                            ->label('Estimasi Selesai (hari)')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(3),
                        Select::make('jumlah_level_approval')
                            ->label('Alur / Jumlah Level Approval')
                            ->required()
                            ->options([
                                1 => '1 Level (Hanya Verifikasi Petugas Desa)',
                                2 => '2 Level (Petugas Desa → Sekretaris Desa)',
                                3 => '3 Level (Petugas Desa → Sekdes → Kepala Desa & TTE)',
                            ])
                            ->default(3)
                            ->helperText('Menentukan berapa tingkat persetujuan sebelum surat terbit.'),
                        Toggle::make('butuh_tte_kades')
                            ->label('Butuh Tanda Tangan Elektronik (TTE)')
                            ->default(true)
                            ->helperText('Tampilkan blok TTE dan QR Code verifikasi pada PDF surat terbit.'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Hanya jenis aktif yang tampil di portal warga.'),
                    ]),

                Section::make('Persyaratan Berkas')
                    ->description('Daftar dokumen yang wajib dilampirkan pemohon.')
                    ->schema([
                        Repeater::make('persyaratan')
                            ->hiddenLabel()
                            ->simple(
                                TextInput::make('item')
                                    ->hiddenLabel()
                                    ->required()
                                    ->placeholder('Contoh: Fotokopi KTP'),
                            )
                            ->addActionLabel('Tambah Persyaratan')
                            ->reorderable()
                            ->defaultItems(1),
                    ]),

                Section::make('Field Formulir Dinamis')
                    ->description('Field isian yang harus diisi warga saat mengajukan surat ini.')
                    ->schema([
                        Repeater::make('field_formulir')
                            ->hiddenLabel()
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Field (key)')
                                    ->required()
                                    ->rule('alpha_dash')
                                    ->helperText('Tanpa spasi, contoh: nama_usaha.'),
                                TextInput::make('label')
                                    ->label('Label Tampilan')
                                    ->required(),
                                Select::make('type')
                                    ->label('Tipe')
                                    ->required()
                                    ->options([
                                        'text' => 'Teks Singkat',
                                        'textarea' => 'Teks Panjang',
                                        'number' => 'Angka',
                                        'date' => 'Tanggal',
                                        'select' => 'Pilihan (Select)',
                                    ])
                                    ->live()
                                    ->default('text'),
                                Toggle::make('required')
                                    ->label('Wajib Diisi')
                                    ->default(true),
                                Repeater::make('options')
                                    ->label('Opsi Pilihan')
                                    ->simple(
                                        TextInput::make('opsi')
                                            ->hiddenLabel()
                                            ->required(),
                                    )
                                    ->addActionLabel('Tambah Opsi')
                                    ->visible(fn (Get $get): bool => $get('type') === 'select')
                                    ->columnSpanFull(),
                            ])
                            ->addActionLabel('Tambah Field')
                            ->reorderable()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? $state['name'] ?? null)
                            ->collapsible(),
                    ]),
            ]);
    }
}
