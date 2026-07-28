<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataKependudukan extends Model
{
    protected $table = 'data_kependudukan';

    protected $fillable = [
        'nik', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
        'alamat', 'rt_rw', 'agama', 'status_perkawinan', 'pekerjaan',
        'kewarganegaraan', 'sudah_didaftarkan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'sudah_didaftarkan' => 'boolean',
        ];
    }
}
