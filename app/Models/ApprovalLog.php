<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ApprovalLog extends Model
{
    use LogsActivity;

    protected $table = 'approval_log';

    protected $fillable = [
        'pengajuan_surat_id', 'user_id', 'level', 'role_saat_itu',
        'keputusan', 'catatan', 'ditandatangani_pada',
    ];

    protected function casts(): array
    {
        return ['ditandatangani_pada' => 'datetime'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['pengajuan_surat_id', 'user_id', 'level', 'role_saat_itu', 'keputusan', 'catatan', 'ditandatangani_pada'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function pengajuanSurat(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
