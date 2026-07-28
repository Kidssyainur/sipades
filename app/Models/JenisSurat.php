<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class JenisSurat extends Model
{
    use LogsActivity;

    protected $table = 'jenis_surat';

    protected $fillable = [
        'kode', 'nama', 'deskripsi', 'persyaratan', 'field_formulir',
        'template_view', 'estimasi_hari', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'persyaratan' => 'array',
            'field_formulir' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function pengajuanSurat(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kode', 'nama', 'estimasi_hari', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
