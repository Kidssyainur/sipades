# PRD — Sistem Informasi Pelayanan Desa Karduluk

**Versi dokumen:** 1.0
**Berdasarkan:** Proposal Skripsi — Rancang Bangun Sistem Informasi Pelayanan Desa Karduluk Berbasis Web dengan Fitur Self-Service, Multi-Level Approval, dan Notifikasi WhatsApp
**Target stack:** Laravel 13 (PHP 8.3+), Filament v5, MySQL 8

---

## 1. Ringkasan Eksekutif

Sistem ini menggantikan proses pelayanan surat-menyurat manual di Kantor Desa Karduluk dengan platform web yang memungkinkan warga mengajukan permohonan surat secara mandiri (*self-service*), diverifikasi melalui alur persetujuan berjenjang (*multi-level approval*: Petugas Desa → Sekretaris Desa → Kepala Desa), dan diberitahukan statusnya secara real-time via WhatsApp Gateway. Surat yang disetujui diterbitkan otomatis dalam format PDF dengan nomor resmi dan ditandatangani secara elektronik oleh Kepala Desa.

Dokumen ini menerjemahkan kebutuhan pada BAB I–III proposal menjadi spesifikasi teknis siap-implementasi: struktur database, model Eloquent, daftar package, serta alur logika tiap proses bisnis.

---

## 2. Tech Stack

| Komponen | Pilihan | Catatan |
|---|---|---|
| Framework | Laravel 13 | Rilis 17 Maret 2026, mensyaratkan PHP 8.3+ |
| Bahasa | PHP 8.3 (disarankan 8.3, karena beberapa package mensyaratkan 8.4 — lihat catatan §5) |
| Panel admin/staf | Filament v5 | Untuk dashboard Petugas, Sekretaris Desa, Kepala Desa, Admin |
| Portal warga | Blade + Livewire (di luar panel Filament) | Warga butuh alur registrasi publik dengan OTP yang tidak cocok dengan pola login-panel Filament |
| Database | MySQL 8.x | Sesuai batasan masalah pada proposal |
| Queue | Database driver (bisa upgrade ke Redis) | Untuk job kirim WhatsApp & retry, serta generate PDF |
| RBAC | spatie/laravel-permission + filament-shield | Role: warga, petugas, sekretaris_desa, kepala_desa, admin |
| PDF | barryvdh/laravel-dompdf | Sesuai opsi "DomPDF" pada flowchart penerbitan surat |
| Media/lampiran | spatie/laravel-medialibrary | Upload KTP/KK, tanda tangan elektronik |
| Audit log | spatie/laravel-activitylog | Memenuhi kebutuhan "Laporan & Audit" |
| WhatsApp Gateway | HTTP client kustom (Fonnte/Wablas/StarSender, dsb) | Tidak ada package resmi Laravel untuk gateway lokal Indonesia — dibungkus jadi Service class sendiri |

---

## 3. Aktor Sistem

| Role (kode) | Deskripsi |
|---|---|
| `warga` | Mengajukan surat, memantau status, mengunduh surat terbit |
| `petugas` | Verifikasi Level 1 |
| `sekretaris_desa` | Verifikasi Level 2 |
| `kepala_desa` | Persetujuan final Level 3 + tanda tangan elektronik |
| `admin` | Kelola pengguna, jenis surat, konfigurasi approval, laporan, audit log |

---

## 4. Kebutuhan Fungsional (ringkas, diberi kode untuk referensi pengujian)

| Kode | Kebutuhan |
|---|---|
| FR-01 | Registrasi akun warga dengan validasi NIK terhadap data kependudukan desa + verifikasi OTP email |
| FR-02 | Login berbasis role, redirect ke dashboard sesuai role |
| FR-03 | Warga mengajukan surat secara mandiri (formulir dinamis per jenis surat + upload lampiran) |
| FR-04 | Approval berjenjang 3 level dengan keputusan: setuju / revisi / tolak |
| FR-05 | Notifikasi WhatsApp otomatis di setiap perubahan status |
| FR-06 | Penerbitan surat otomatis dalam PDF dengan nomor surat resmi setelah disetujui Kepala Desa |
| FR-07 | Tracking status pengajuan real-time oleh warga |
| FR-08 | Admin mengelola pengguna, jenis surat, konfigurasi alur approval, laporan & audit log |
| FR-09 | Tanda tangan elektronik Kepala Desa pada tahap persetujuan akhir |
| FR-10 | Unduh surat terbit dengan tautan aman |

## 5. Kebutuhan Non-Fungsional

- Proteksi bawaan terhadap SQL Injection, XSS, CSRF (default Laravel) + RBAC granular per resource/aksi via filament-shield.
- Dapat diakses kapan saja (bukan hanya jam kerja kantor desa).
- Antarmuka responsif untuk berbagai tingkat literasi digital, dapat diakses dari desktop maupun smartphone.
- Retry otomatis untuk pengiriman notifikasi WhatsApp yang gagal.
- Setiap aksi approval terekam di audit log (siapa, kapan, keputusan apa).

**Catatan kompatibilitas PHP:** Laravel 13 minimum PHP 8.3. `spatie/laravel-activitylog` versi 5.x mensyaratkan PHP 8.4. Jika server produksi masih di PHP 8.3, gunakan `spatie/laravel-activitylog:^4.0` (kompatibel Laravel 13 lewat illuminate ^13.0 sebagai dependency yang lebih longgar) — verifikasi versi tersedia saat instalasi karena rilis package terus berjalan.

---

## 6. Instalasi & Package

```bash
# 1. Buat project
composer create-project laravel/laravel sipades-karduluk "^13.0"
cd sipades-karduluk

# 2. Panel admin (Filament v5)
composer require filament/filament:"^5.0"
php artisan filament:install --panels

# 3. RBAC
composer require spatie/laravel-permission:"^6.0"
composer require bezhansalleh/filament-shield:"^4.0"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan shield:install

# 4. Media & lampiran (KTP, KK, tanda tangan elektronik)
composer require spatie/laravel-medialibrary:"^11.0"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"

# 5. Audit log
composer require spatie/laravel-activitylog:"^4.0"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"

# 6. PDF surat
composer require barryvdh/laravel-dompdf:"^3.0"

# 7. Queue (untuk job notifikasi WA & generate PDF)
php artisan queue:table   # jika pakai driver database
php artisan migrate

# 8. Formatting (dev)
composer require laravel/pint --dev
```

Konfigurasi `.env` tambahan:

```env
QUEUE_CONNECTION=database

WA_GATEWAY_DRIVER=fonnte
WA_GATEWAY_ENDPOINT=https://api.fonnte.com/send
WA_GATEWAY_TOKEN=xxxxxxxxxxxxxxxx

DESA_NAMA="Desa Karduluk"
DESA_KECAMATAN="Pragaan"
DESA_KABUPATEN="Sumenep"
```

> Catatan gateway WhatsApp: tidak digunakan package pihak ketiga khusus karena penyedia gateway Indonesia (Fonnte/Wablas/StarSender) bekerja lewat REST API sederhana. Cukup dibungkus sebagai Service class (lihat §12.5) sehingga provider dapat diganti tanpa mengubah kode bisnis.

---

## 7. Struktur Direktori (ringkas)

```
app/
  Enums/
    StatusPengajuan.php
    KeputusanApproval.php
    StatusNotifikasi.php
  Models/
    User.php
    DataKependudukan.php
    JenisSurat.php
    PengajuanSurat.php
    ApprovalLog.php
    SuratTerbit.php
    TemplatePesan.php
    NotifikasiLog.php
    OtpCode.php
    NomorSuratCounter.php
  Services/
    NikValidationService.php
    NomorSuratService.php
    SuratPdfService.php
    WhatsappGatewayService.php
  Jobs/
    KirimNotifikasiWhatsappJob.php
    TerbitkanSuratJob.php
  Livewire/
    Portal/
      RegistrasiForm.php
      OtpVerifikasi.php
      PengajuanSuratForm.php
      TrackingStatus.php
  Filament/
    Resources/
      PengajuanSuratResource.php
      JenisSuratResource.php
      UserResource.php
      SuratTerbitResource.php
      NotifikasiLogResource.php
    Pages/
      ApprovalQueuePage.php
```

---

## 8. Desain Basis Data

### 8.1 Ringkasan Entitas

`users`, `data_kependudukan`, `jenis_surat`, `pengajuan_surat`, `approval_log`, `surat_terbit`, `template_pesan`, `notifikasi_log`, `otp_codes`, `nomor_surat_counters` — ditambah tabel bawaan package (`roles`, `permissions`, `model_has_roles`, `activity_log`, `media`).

Relasi inti: satu `pengajuan_surat` dimiliki satu `user` (warga) dan satu `jenis_surat`; memiliki banyak `approval_log` (satu per level yang telah diputuskan) dan tepat satu `surat_terbit` setelah disetujui final. `notifikasi_log` mencatat setiap pengiriman WA yang dipicu dari perubahan status pengajuan, direferensikan ke `template_pesan` yang dipakai.

### 8.2 Migration

**`2026_01_01_000001_create_users_table.php`** (memodifikasi migration bawaan Laravel)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('nik', 16)->nullable()->unique(); // hanya diisi untuk role warga
            $table->string('no_hp', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

**`2026_01_01_000002_create_data_kependudukan_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_kependudukan', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('nama');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('rt_rw', 10)->nullable();
            $table->string('agama', 30)->nullable();
            $table->string('status_perkawinan', 30)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->string('kewarganegaraan', 5)->default('WNI');
            $table->boolean('sudah_didaftarkan')->default(false); // cegah 1 NIK dipakai >1 akun
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_kependudukan');
    }
};
```

**`2026_01_01_000003_create_jenis_surat_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_surat', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique(); // ex: SK_DOMISILI, SKTM, SK_USAHA
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->json('persyaratan')->nullable();     // ["KTP", "KK", "Surat Pengantar RT"]
            $table->json('field_formulir')->nullable();  // definisi field dinamis formulir
            $table->string('template_view', 100)->nullable(); // nama blade template surat, ex: 'surat.domisili'
            $table->unsignedTinyInteger('estimasi_hari')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_surat');
    }
};
```

**`2026_01_01_000004_create_pengajuan_surat_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_referensi', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat');
            $table->json('data_formulir');
            $table->string('status', 30)->default('diajukan');
            // diajukan | diverifikasi_petugas | direvisi | ditolak
            // disetujui_sekretaris | disetujui_kepala | selesai
            $table->unsignedTinyInteger('current_level')->default(1); // 1=Petugas 2=Sekretaris 3=KepalaDesa
            $table->text('catatan_revisi')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamp('tanggal_pengajuan');
            $table->timestamp('tanggal_selesai')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'current_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};
```

**`2026_01_01_000005_create_approval_log_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')->constrained('pengajuan_surat')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users'); // approver
            $table->unsignedTinyInteger('level'); // 1, 2, 3
            $table->string('role_saat_itu', 30); // snapshot role, jaga histori jika role user berubah nanti
            $table->enum('keputusan', ['setuju', 'revisi', 'tolak']);
            $table->text('catatan')->nullable();
            $table->timestamp('ditandatangani_pada')->nullable(); // diisi khusus level 3 (Kepala Desa)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_log');
    }
};
```

**`2026_01_01_000006_create_surat_terbit_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_terbit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')->unique()->constrained('pengajuan_surat');
            $table->string('nomor_surat', 60)->unique();
            $table->foreignId('diterbitkan_oleh')->constrained('users'); // Kepala Desa
            $table->string('file_path');
            $table->timestamp('tanggal_terbit');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_terbit');
    }
};
```

**`2026_01_01_000007_create_nomor_surat_counters_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nomor_surat_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat');
            $table->unsignedSmallInteger('tahun');
            $table->unsignedInteger('nomor_terakhir')->default(0);
            $table->timestamps();

            $table->unique(['jenis_surat_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nomor_surat_counters');
    }
};
```

**`2026_01_01_000008_create_template_pesan_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_pesan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 40)->unique();
            // ex: PENGAJUAN_DITERIMA, REVISI_DIMINTA, DITOLAK,
            //     DISETUJUI_PETUGAS, DISETUJUI_SEKRETARIS, SURAT_TERBIT
            $table->string('judul');
            $table->text('isi_template'); // placeholder: {nama}, {nomor_referensi}, {jenis_surat}, {catatan}
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_pesan');
    }
};
```

**`2026_01_01_000009_create_notifikasi_log_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('pengajuan_surat_id')->nullable()->constrained('pengajuan_surat')->nullOnDelete();
            $table->foreignId('template_pesan_id')->nullable()->constrained('template_pesan')->nullOnDelete();
            $table->string('no_hp_tujuan', 20);
            $table->text('pesan');
            $table->string('status', 20)->default('pending'); // pending | terkirim | gagal
            $table->unsignedTinyInteger('percobaan')->default(0);
            $table->text('response_gateway')->nullable();
            $table->timestamp('dikirim_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi_log');
    }
};
```

**`2026_01_01_000010_create_otp_codes_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('kode_otp', 6);
            $table->enum('tipe', ['registrasi', 'reset_password']);
            $table->timestamp('kadaluarsa_pada');
            $table->timestamp('digunakan_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};
```

Tabel tambahan otomatis tersedia setelah publish package: `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions` (spatie/laravel-permission), `activity_log` (spatie/laravel-activitylog), `media` (spatie/laravel-medialibrary), `jobs`/`failed_jobs` (queue).

---

## 9. Eloquent Models

### 9.1 Enum status (`app/Enums/StatusPengajuan.php`)

```php
<?php

namespace App\Enums;

enum StatusPengajuan: string
{
    case DIAJUKAN = 'diajukan';
    case DIVERIFIKASI_PETUGAS = 'diverifikasi_petugas';
    case DIREVISI = 'direvisi';
    case DITOLAK = 'ditolak';
    case DISETUJUI_SEKRETARIS = 'disetujui_sekretaris';
    case DISETUJUI_KEPALA = 'disetujui_kepala';
    case SELESAI = 'selesai';

    public function label(): string
    {
        return match ($this) {
            self::DIAJUKAN => 'Diajukan',
            self::DIVERIFIKASI_PETUGAS => 'Diverifikasi Petugas',
            self::DIREVISI => 'Perlu Revisi',
            self::DITOLAK => 'Ditolak',
            self::DISETUJUI_SEKRETARIS => 'Disetujui Sekretaris Desa',
            self::DISETUJUI_KEPALA => 'Disetujui Kepala Desa',
            self::SELESAI => 'Selesai (Surat Terbit)',
        };
    }
}
```

`app/Enums/KeputusanApproval.php` dan `app/Enums/StatusNotifikasi.php` dibuat dengan pola yang sama (`setuju|revisi|tolak` dan `pending|terkirim|gagal`).

### 9.2 `User`

```php
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    protected $fillable = ['name', 'email', 'password', 'nik', 'no_hp', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
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
}
```

### 9.3 `DataKependudukan`

```php
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
```

### 9.4 `JenisSurat`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
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
}
```

### 9.5 `PengajuanSurat`

```php
<?php

namespace App\Models;

use App\Enums\StatusPengajuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PengajuanSurat extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

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
```

### 9.6 `ApprovalLog`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalLog extends Model
{
    protected $table = 'approval_log';

    protected $fillable = [
        'pengajuan_surat_id', 'user_id', 'level', 'role_saat_itu',
        'keputusan', 'catatan', 'ditandatangani_pada',
    ];

    protected function casts(): array
    {
        return ['ditandatangani_pada' => 'datetime'];
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
```

### 9.7 `SuratTerbit`, `TemplatePesan`, `NotifikasiLog`, `NomorSuratCounter`, `OtpCode`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratTerbit extends Model
{
    protected $table = 'surat_terbit';

    protected $fillable = [
        'pengajuan_surat_id', 'nomor_surat', 'diterbitkan_oleh',
        'file_path', 'tanggal_terbit',
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
```

```php
class TemplatePesan extends Model
{
    protected $table = 'template_pesan';

    protected $fillable = ['kode', 'judul', 'isi_template', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function render(array $data): string
    {
        $pesan = $this->isi_template;
        foreach ($data as $key => $value) {
            $pesan = str_replace('{'.$key.'}', (string) $value, $pesan);
        }
        return $pesan;
    }
}
```

```php
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
}
```

```php
class NomorSuratCounter extends Model
{
    protected $table = 'nomor_surat_counters';

    protected $fillable = ['jenis_surat_id', 'tahun', 'nomor_terakhir'];
}
```

```php
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
```

---

## 10. Role & Permission Matrix

Diaktifkan dengan `spatie/laravel-permission`, dan diikat ke resource Filament lewat `filament-shield` (setiap Resource otomatis mendapat permission `view_any`, `view`, `create`, `update`, `delete`, ditambah permission kustom untuk aksi approval).

| Resource / Aksi | warga | petugas | sekretaris_desa | kepala_desa | admin |
|---|:---:|:---:|:---:|:---:|:---:|
| Ajukan surat (portal) | ✅ | – | – | – | – |
| Lihat status pengajuan sendiri | ✅ | – | – | – | – |
| Review antrian Level 1 | – | ✅ | – | – | – |
| Review antrian Level 2 | – | – | ✅ | – | – |
| Review antrian Level 3 + tanda tangan elektronik | – | – | – | ✅ | – |
| Kelola pengguna | – | – | – | – | ✅ |
| Kelola jenis surat & syarat | – | – | – | – | ✅ |
| Konfigurasi alur approval | – | – | – | – | ✅ |
| Laporan & audit log | – | – | – | – | ✅ |

Permission kustom yang perlu ditambahkan manual di seeder (di luar bawaan Shield): `approve_level_1`, `approve_level_2`, `approve_level_3_sign`.

---

## 11. Alur Sistem (Business Logic)

### 11.1 Registrasi & Verifikasi OTP (FR-01)

1. Warga mengisi NIK, nama, no HP, email, password di portal (Livewire `RegistrasiForm`).
2. `NikValidationService::validate($nik)` mengecek `data_kependudukan` — NIK harus ditemukan dan `sudah_didaftarkan = false`. Jika tidak valid, tampilkan error.
3. Jika valid dan email belum dipakai di `users`, generate kode OTP 6 digit, simpan ke `otp_codes` dengan `kadaluarsa_pada = now()->addMinutes(10)`, kirim via Mail (bukan WA — sesuai proposal, OTP registrasi lewat email).
4. Warga memasukkan OTP di `OtpVerifikasi`. Sistem mencocokkan `kode_otp`, memastikan `isValid()`, lalu:
   - Set `digunakan_pada = now()` pada `otp_codes`.
   - Buat `User` baru, assign role `warga`.
   - Set `data_kependudukan.sudah_didaftarkan = true`.
5. Redirect ke halaman login.

### 11.2 Login & Redirect Berbasis Role (FR-02)

Setelah autentikasi berhasil, cek role via `auth()->user()->getRoleNames()->first()` dan arahkan:
- `warga` → portal Livewire (`/portal/dashboard`, **di luar** panel Filament)
- `petugas` / `sekretaris_desa` / `kepala_desa` / `admin` → panel Filament (`/admin`), dengan resource yang tampil difilter otomatis oleh permission dari Shield.

### 11.3 Pengajuan Surat (FR-03)

1. Warga memilih `JenisSurat` → sistem render formulir dinamis dari `field_formulir` (json) beserta daftar `persyaratan`.
2. Validasi field wajib + tipe/ukuran file lampiran (maks, misalnya, 2MB per file, tipe pdf/jpg/png).
3. Simpan draf → tampilkan preview → warga konfirmasi.
4. Setelah konfirmasi (dalam DB transaction):
   - `nomor_referensi` di-generate, format: `REF-{YYYYMMDD}-{urutan 4 digit}` (reset harian, dihitung dari jumlah pengajuan pada tanggal tsb + 1).
   - Simpan `PengajuanSurat` dengan `status = diajukan`, `current_level = 1`, `tanggal_pengajuan = now()`.
   - Lampiran disimpan ke media collection `lampiran` (spatie/laravel-medialibrary).
   - Dispatch `KirimNotifikasiWhatsappJob` dengan template `PENGAJUAN_DITERIMA` ke warga, dan opsional notifikasi ke Petugas Desa bahwa ada antrian baru.
5. Warga dapat memantau via `TrackingStatus` (query `pengajuanSurat->status`, `approvalLogs`).

### 11.4 Approval Multi-Level (FR-04, FR-09)

State machine pada kolom `status` + `current_level`:

```
diajukan (level 1)
   ├─ Petugas setuju   → diverifikasi_petugas (level → 2)
   ├─ Petugas revisi   → direvisi (level tetap 1, warga edit & submit ulang → kembali ke diajukan)
   └─ Petugas tolak    → ditolak (selesai, gagal)

diverifikasi_petugas (level 2)
   ├─ Sekretaris setuju → disetujui_sekretaris (level → 3)
   ├─ Sekretaris revisi → direvisi (level → 1, kembali ke Petugas)
   └─ Sekretaris tolak  → ditolak

disetujui_sekretaris (level 3)
   ├─ Kepala Desa setuju → disetujui_kepala + tanda tangan elektronik → trigger penerbitan surat → selesai
   ├─ Kepala Desa revisi → direvisi (level → 1)
   └─ Kepala Desa tolak  → ditolak
```

Setiap keputusan approval:
1. Divalidasi bahwa `auth()->user()` punya permission approval untuk `current_level` yang sesuai (mis. `approve_level_1` untuk level 1).
2. Insert baris baru ke `approval_log` (level, role_saat_itu, keputusan, catatan). Untuk level 3 + keputusan setuju, isi `ditandatangani_pada = now()` sebagai representasi tanda tangan elektronik.
3. Update `status` dan `current_level` pada `PengajuanSurat` sesuai tabel state di atas.
4. Dispatch `KirimNotifikasiWhatsappJob` dengan template sesuai hasil keputusan (`DISETUJUI_PETUGAS`, `REVISI_DIMINTA`, `DITOLAK`, `DISETUJUI_SEKRETARIS`, dst).
5. Jika keputusan Kepala Desa = setuju, dispatch `TerbitkanSuratJob` (lihat §11.5).

Seluruh perubahan `status`/`approval_log` otomatis tercatat di `activity_log` (spatie/laravel-activitylog) melalui trait `LogsActivity` pada model `PengajuanSurat` dan `ApprovalLog`.

### 11.5 Penerbitan Surat Otomatis (FR-06)

`TerbitkanSuratJob` (queued, dijalankan setelah approval Kepala Desa):

1. `NomorSuratService::generate($jenisSuratId)` — dalam DB transaction, `lockForUpdate()` baris `nomor_surat_counters` untuk `(jenis_surat_id, tahun_berjalan)`, increment `nomor_terakhir`, format menjadi nomor resmi, misalnya `470/{urutan}/DS-KDL/{bulan_romawi}/{tahun}`.
2. `SuratPdfService::generate($pengajuan, $nomorSurat)`:
   - Ambil `template_view` dari `JenisSurat` (misal `surat.domisili`).
   - Render Blade view dengan data warga (`data_kependudukan`), data formulir (`data_formulir`), nomor surat, dan tanggal terbit, menggunakan `Pdf::loadView(...)->save($path)`.
3. Simpan record `SuratTerbit` (nomor_surat, file_path, diterbitkan_oleh, tanggal_terbit).
4. Update `PengajuanSurat.status = selesai`, `tanggal_selesai = now()`.
5. Dispatch `KirimNotifikasiWhatsappJob` template `SURAT_TERBIT` berisi tautan unduh.
6. Tautan unduh menggunakan **signed URL** Laravel (`URL::temporarySignedRoute('surat.unduh', now()->addDays(7), ['surat' => $suratTerbit->id])`) — menghindari kebutuhan kolom token manual dan otomatis kedaluwarsa.

### 11.6 Notifikasi WhatsApp & Retry (FR-05)

`WhatsappGatewayService`:

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsappGatewayService
{
    public function send(string $noHp, string $pesan): array
    {
        $response = Http::withToken(config('services.whatsapp.token'))
            ->timeout(15)
            ->post(config('services.whatsapp.endpoint'), [
                'target' => $noHp,
                'message' => $pesan,
            ]);

        return [
            'sukses' => $response->successful(),
            'body' => $response->body(),
        ];
    }
}
```

`KirimNotifikasiWhatsappJob implements ShouldQueue`:

```php
public int $tries = 3;
public array $backoff = [60, 300, 900]; // 1 menit, 5 menit, 15 menit

public function handle(WhatsappGatewayService $gateway): void
{
    $log = NotifikasiLog::create([...]); // status pending
    $hasil = $gateway->send($this->noHp, $this->pesan);

    $log->update([
        'status' => $hasil['sukses'] ? 'terkirim' : 'gagal',
        'percobaan' => $log->percobaan + 1,
        'response_gateway' => $hasil['body'],
        'dikirim_pada' => $hasil['sukses'] ? now() : null,
    ]);

    if (! $hasil['sukses']) {
        $this->fail(); // memicu retry sesuai $backoff, tercatat di failed_jobs setelah 3x gagal
    }
}
```

### 11.7 Audit Log (bagian FR-08)

Tambahkan trait `Spatie\Activitylog\Traits\LogsActivity` pada model yang perlu diaudit (`PengajuanSurat`, `ApprovalLog`, `User`, `JenisSurat`) dan definisikan `getActivitylogOptions()` untuk memilih kolom yang dicatat (mis. `logOnly(['status', 'current_level'])->logOnlyDirty()` pada `PengajuanSurat`). Admin dapat melihat riwayat lewat Filament Resource khusus yang membaca tabel `activity_log`.

---

## 12. Struktur Panel Filament

Karena empat role staf (`petugas`, `sekretaris_desa`, `kepala_desa`, `admin`) sama-sama membutuhkan pola dashboard/CRUD, disarankan satu panel Filament (`/admin`) dengan visibilitas Resource dan aksi diatur penuh oleh permission dari `filament-shield`, bukan empat panel terpisah — lebih mudah dirawat.

| Resource / Page | Ditampilkan untuk | Fungsi utama |
|---|---|---|
| `PengajuanSuratResource` | petugas, sekretaris_desa, kepala_desa, admin | List + filter status; halaman detail berisi form keputusan approval (custom Filament Action, bukan default edit form) |
| `JenisSuratResource` | admin | CRUD jenis surat, persyaratan, field formulir dinamis |
| `UserResource` | admin | Kelola akun & role |
| `SuratTerbitResource` | admin, kepala_desa | Rekap surat yang sudah terbit |
| `NotifikasiLogResource` | admin | Monitoring pengiriman WA, retry manual jika perlu |
| Halaman `Laporan` | admin | Rekap periode (custom Filament Page dengan widget chart) |
| Resource `activity_log` (read-only) | admin | Audit trail |

Keputusan approval diimplementasikan sebagai **Filament Action kustom** pada `PengajuanSuratResource` (bukan form edit biasa), karena perlu memvalidasi level akses approver dan menjalankan alur di §11.4, bukan sekadar update kolom.

---

## 13. Portal Warga (di luar Filament)

Karena warga butuh alur publik (registrasi, OTP) yang berbeda pola dari panel admin, portal dibangun sebagai rute Blade + komponen Livewire terpisah:

```
routes/portal.php
  GET  /registrasi        → Livewire RegistrasiForm
  GET  /verifikasi-otp    → Livewire OtpVerifikasi
  GET  /portal/login      → gunakan guard default Laravel (bukan panel Filament)
  GET  /portal/dashboard  → ringkasan pengajuan milik warga
  GET  /portal/pengajuan/buat        → Livewire PengajuanSuratForm
  GET  /portal/pengajuan/{id}/status → Livewire TrackingStatus
  GET  /portal/surat/{surat}/unduh   → route bertanda tangan (signed), streaming file PDF
```

Middleware `role:warga` melindungi seluruh grup `/portal/*` (kecuali registrasi & login).

---

## 14. Rencana Pengujian (Black Box Testing)

| Kode FR | Skenario uji | Hasil diharapkan |
|---|---|---|
| FR-01 | Registrasi dengan NIK tidak terdaftar di `data_kependudukan` | Ditolak, pesan error NIK tidak ditemukan |
| FR-01 | Registrasi dengan NIK yang `sudah_didaftarkan = true` | Ditolak, pesan NIK sudah terdaftar |
| FR-03 | Ajukan surat tanpa upload lampiran wajib | Form tidak bisa submit, validasi menampilkan field yang kurang |
| FR-04 | Petugas memilih "revisi" | Status → `direvisi`, warga menerima catatan revisi, dapat submit ulang |
| FR-04 | Role `petugas` mencoba approve di level 2 | Ditolak (403) — permission `approve_level_2` tidak dimiliki |
| FR-05 | Gateway WA mengembalikan error | `NotifikasiLog.status = gagal`, job retry sesuai backoff, tercatat di `failed_jobs` setelah 3x |
| FR-06 | Kepala Desa menyetujui level 3 | `SuratTerbit` terbentuk, nomor surat unik per tahun, file PDF tersimpan, status `selesai` |
| FR-10 | Akses link unduh setelah 7 hari | Signed URL kedaluwarsa → 403 |

---

## 15. Rencana Implementasi (mengikuti tahapan Waterfall pada proposal)

1. **Requirements Analysis** — dokumen ini + validasi ulang field formulir tiap jenis surat bersama Sekretaris Desa.
2. **System Design** — migration §8.2, model §9, Flowchart/DFD/ERD (sudah ada di BAB III proposal) dijadikan acuan review sebelum coding.
3. **Implementation** — urutan disarankan:
   1. Setup project, RBAC, seeder role & permission.
   2. Modul `data_kependudukan` + registrasi/OTP.
   3. Modul `JenisSurat` (CRUD admin) + formulir dinamis warga.
   4. Modul `PengajuanSurat` + upload lampiran.
   5. Modul Approval multi-level + audit log.
   6. Modul penerbitan PDF + penomoran surat.
   7. Modul notifikasi WhatsApp + job/retry.
   8. Laporan & dashboard admin.
4. **Testing** — jalankan skenario §14 per modul selesai (bukan menunggu seluruh sistem selesai).
5. **Maintenance** — pantau `failed_jobs` dan `notifikasi_log.status = gagal` secara berkala; sediakan tombol retry manual di `NotifikasiLogResource`.

---

## 16. Lampiran — Seeder Awal yang Perlu Disiapkan

- **Roles**: `warga`, `petugas`, `sekretaris_desa`, `kepala_desa`, `admin` (+ permission approval kustom §10).
- **Jenis surat awal**: Surat Keterangan Domisili, Surat Keterangan Usaha, SKTM, Surat Pengantar KTP, Surat Pengantar KK, Surat Keterangan Kelahiran, Surat Keterangan Kematian — masing-masing dengan `persyaratan` dan `field_formulir` sesuai kebutuhan riil (didiskusikan dengan Sekretaris Desa saat requirements analysis).
- **Template pesan WA minimal**: `PENGAJUAN_DITERIMA`, `REVISI_DIMINTA`, `DITOLAK`, `DISETUJUI_PETUGAS`, `DISETUJUI_SEKRETARIS`, `SURAT_TERBIT`.
- **Akun awal**: 1 admin, 1 kepala desa, 1 sekretaris desa, minimal 1 petugas — dibuat manual pasca-migrate, bukan lewat portal registrasi warga.