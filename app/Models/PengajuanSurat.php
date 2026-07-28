<?php

namespace App\Models;

use App\Enums\StatusPengajuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PengajuanSurat extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity, SoftDeletes;

    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'nomor_referensi', 'user_id', 'jenis_surat_id', 'data_formulir',
        'status', 'current_level', 'catatan_revisi', 'alasan_penolakan',
        'tanggal_pengajuan', 'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'data_formulir' => 'array',
            'status' => StatusPengajuan::class,
            'tanggal_pengajuan' => 'datetime',
            'tanggal_selesai' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'current_level', 'catatan_revisi', 'alasan_penolakan'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('lampiran'); // KTP, KK, dokumen pendukung lain
    }

    public function warga(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function approvalLogs(): HasMany
    {
        return $this->hasMany(ApprovalLog::class)->orderBy('level');
    }

    public function suratTerbit(): HasOne
    {
        return $this->hasOne(SuratTerbit::class);
    }

    public function notifikasiLogs(): HasMany
    {
        return $this->hasMany(NotifikasiLog::class);
    }
}
