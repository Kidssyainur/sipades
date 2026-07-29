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
        'template_view', 'estimasi_hari', 'jumlah_level_approval', 'butuh_tte_kades', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'persyaratan' => 'array',
            'field_formulir' => 'array',
            'jumlah_level_approval' => 'integer',
            'butuh_tte_kades' => 'boolean',
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
            ->dontLogEmptyChanges();
    }
}
