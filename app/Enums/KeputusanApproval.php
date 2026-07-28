<?php

namespace App\Enums;

enum KeputusanApproval: string
{
    case SETUJU = 'setuju';
    case REVISI = 'revisi';
    case TOLAK = 'tolak';

    public function label(): string
    {
        return match ($this) {
            self::SETUJU => 'Setuju',
            self::REVISI => 'Minta Revisi',
            self::TOLAK => 'Tolak',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SETUJU => 'success',
            self::REVISI => 'warning',
            self::TOLAK => 'danger',
        };
    }
}
