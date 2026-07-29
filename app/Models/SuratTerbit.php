<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratTerbit extends Model
{
    protected $table = 'surat_terbit';

    protected $fillable = [
        'pengajuan_surat_id', 'nomor_surat', 'diterbitkan_oleh',
        'file_path', 'tte_token', 'qr_code_path', 'tanggal_terbit',
    ];

    protected function casts(): array
    {
        return ['tanggal_terbit' => 'datetime'];
    }

    public function pengajuanSurat(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class);
    }

    public function penerbit(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diterbitkan_oleh');
    }
}
