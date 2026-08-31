<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(150),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(150),
                        TextInput::make('nik')
                            ->label('NIK')
                            ->rules(['nullable', 'digits:16'])
                            ->maxLength(16)
                            ->unique(ignoreRecord: true)
                            ->helperText('16 digit sesuai KTP (opsional untuk petugas).'),
                        TextInput::make('no_hp')
                            ->label('Nomor HP / WhatsApp')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('08xxxxxxxxxx / 628xxxxxxxxxx'),
                    ]),

                Section::make('Keamanan & Akses')
                    ->columns(2)
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn (?string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->helperText('Kosongkan saat mengedit jika tidak ingin mengubah password.'),
                        Toggle::make('is_active')
                            ->label('Akun Aktif')
                            ->default(true)
                            ->helperText('Nonaktifkan untuk mencabut akses tanpa menghapus akun.'),
                        Select::make('roles')
                            ->label('Peran (Role)')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->options(fn (): array => Role::query()->pluck('name', 'id')->all())
                            ->helperText('Warga memakai portal terpisah; jangan beri role warga untuk akun panel.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
