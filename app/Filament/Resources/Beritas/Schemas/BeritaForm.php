<?php

namespace App\Filament\Resources\Beritas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BeritaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Berita')
                    ->columns(2)
                    ->schema([
                        TextInput::make('judul')
                            ->label('Judul Berita')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            // Slug otomatis hanya saat membuat berita; saat edit slug dikelola manual agar URL tidak berubah.
                            ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->hint('Otomatis')
                            ->placeholder('otomatis-dari-judul')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Otomatis dari judul. Dipakai untuk alamat halaman berita, contoh: /berita/judul-berita.'),
                        Select::make('kategori')
                            ->label('Kategori')
                            ->options([
                                'Berita Desa' => 'Berita Desa',
                                'Kegiatan' => 'Kegiatan',
                                'Prestasi' => 'Prestasi',
                                'Pengumuman' => 'Pengumuman',
                                'Pemerintahan' => 'Pemerintahan',
                                'Wisata' => 'Wisata',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->default('Berita Desa'),
                        DatePicker::make('tanggal')
                            ->label('Tanggal Terbit')
                            ->required()
                            ->default(now())
                            ->displayFormat('d F Y'),
                        TextInput::make('penulis')
                            ->label('Penulis / Redaksi')
                            ->maxLength(100)
                            ->default(fn (): string => (string) auth()->user()?->name)
                            ->placeholder('Admin Desa'),
                        FileUpload::make('gambar')
                            ->label('Gambar Sampul')
                            ->image()
                            ->imageEditor()
                            ->imageCropAspectRatio('16:9')
                            ->directory('berita')
                            ->disk('public')
                            ->maxSize(4096)
                            ->helperText('Gambar sampul kartu & halaman berita (disarankan 16:9, maks. 4 MB).'),
                        Select::make('status')
                            ->label('Status Publikasi')
                            ->options([
                                'draft' => 'Draft (disimpan, belum tampil)',
                                'published' => 'Terbit (tampil di landing page)',
                            ])
                            ->default('draft')
                            ->helperText('Hanya berita berstatus Terbit yang muncul di landing page.'),
                        Toggle::make('is_featured')
                            ->label('Berita Unggulan (Featured)')
                            ->default(false)
                            ->helperText('Ditandai sebagai berita utama/unggulan.'),
                    ]),

                Section::make('Ringkasan')
                    ->description('Cuplikan singkat yang tampil di kartu daftar berita. Kosongkan untuk otomatis memakai awal isi berita.')
                    ->schema([
                        Textarea::make('ringkasan')
                            ->hiddenLabel()
                            ->rows(3)
                            ->maxLength(500),
                    ]),

                Section::make('Isi Berita')
                    ->description('Editor lengkap: format teks, daftar, kutipan, tabel, tautan, warna teks, dan lainnya.')
                    ->schema([
                        RichEditor::make('isi')
                            ->label('Konten Berita')
                            ->required()
                            ->placeholder('Mulai tulis isi berita…')
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike', 'link',
                                'h1', 'h2', 'h3', 'paragraph',
                                'alignStart', 'alignCenter', 'alignEnd', 'alignJustify',
                                'blockquote', 'codeBlock', 'bulletList', 'orderedList', 'horizontalRule',
                                'table', 'textColor',
                                'undo', 'redo', 'clearFormatting',
                            ])
                            ->extraInputAttributes(['style' => 'min-height: 320px;'])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
