<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NomorSuratCounter extends Model
{
    protected $table = 'nomor_surat_counters';

    protected $fillable = ['jenis_surat_id', 'tahun', 'nomor_terakhir'];
}
