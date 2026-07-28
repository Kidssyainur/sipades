<?php

namespace App\Enums;

enum StatusNotifikasi: string
{
    case PENDING = 'pending';
    case TERKIRIM = 'terkirim';
    case GAGAL = 'gagal';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu',
            self::TERKIRIM => 'Terkirim',
            self::GAGAL => 'Gagal',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::TERKIRIM => 'success',
            self::GAGAL => 'danger',
        };
    }
}
