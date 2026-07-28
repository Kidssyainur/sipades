<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifikasiLog extends Model
{
    protected $table = 'notifikasi_log';

    protected $fillable = [
        'user_id', 'pengajuan_surat_id', 'template_pesan_id',
        'no_hp_tujuan', 'pesan', 'status', 'percobaan',
        'response_gateway', 'dikirim_pada',
    ];

    protected function casts(): array
    {
        return ['dikirim_pada' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pengajuanSurat(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class);
    }

    public function templatePesan(): BelongsTo
    {
        return $this->belongsTo(TemplatePesan::class);
    }
}
