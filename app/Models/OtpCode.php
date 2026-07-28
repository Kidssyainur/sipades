<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $table = 'otp_codes';

    protected $fillable = ['email', 'kode_otp', 'tipe', 'kadaluarsa_pada', 'digunakan_pada'];

    protected function casts(): array
    {
        return [
            'kadaluarsa_pada' => 'datetime',
            'digunakan_pada' => 'datetime',
        ];
    }

    public function isValid(): bool
    {
        return is_null($this->digunakan_pada) && $this->kadaluarsa_pada->isFuture();
    }
}
