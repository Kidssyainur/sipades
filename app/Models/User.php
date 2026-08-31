<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, LogsActivity, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'nik', 'no_hp', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected static function booted(): void
    {
        static::deleting(function (User $user): void {
            foreach ($user->pengajuanSurat()->withTrashed()->get() as $pengajuan) {
                $pengajuan->suratTerbit()?->delete();
                $pengajuan->approvalLogs()->delete();
                $pengajuan->forceDelete();
            }

            if (! empty($user->nik)) {
                DataKependudukan::where('nik', (string) $user->nik)->update([
                    'sudah_didaftarkan' => false,
                ]);
            }
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'nik', 'no_hp', 'is_active'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function pengajuanSurat(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class);
    }

    public function approvalLogs(): HasMany
    {
        return $this->hasMany(ApprovalLog::class);
    }

    public function isWarga(): bool
    {
        return $this->hasRole('warga');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Warga menggunakan portal Livewire (di luar panel), bukan panel Filament.
        return $this->is_active && ! $this->isWarga();
    }
}
