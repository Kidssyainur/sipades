# Dokumentasi & Panduan Integrasi WhatsApp Gateway (SIPADES)

Dokumen ini menjelaskan arsitektur, konfigurasi, cara pengujian, serta pengoperasian WhatsApp Gateway pada **Sistem Informasi Pelayanan Desa Karduluk (SIPADES)** menggunakan package `kstmostofa/laravel-whatsapp`.

---

## 1. Arsitektur Integrasi

Sistem ini mendukung **Dual-Backend WhatsApp**:
1. **Web Sidecar (`whatsapp-web.js`)**: Backend utama untuk nomor HP personal/desa. Mendukung QR pairing, pengiriman pesan gratis kapan saja, grup, serta penerimaan pesan real-time.
2. **Meta Cloud API**: Backend opsional untuk pesan terverifikasi berskala besar (jika dikonfigurasi).

```
┌─────────────────────────────────────────────────────────┐
│                     Panel Filament / App                │
│  (WhatsappGatewaySettings, KirimNotifikasiWhatsappJob)  │
└───────────────────────────┬─────────────────────────────┘
                            │ (WhatsApp Facade)
                            ▼
        ┌───────────────────────────────────────┐
        │ App\Services\WhatsappGatewayService   │
        └───────────────────┬───────────────────┘
                            │
             HTTP / REST (Bearer Auth)
                            │
                            ▼
 ┌───────────────────────────────────────────────────────────┐
 │        WhatsApp Web Sidecar (Node.js Process)             │
 │                http://127.0.0.1:3000                      │
 └──────────────────────────┬────────────────────────────────┘
                            │ Puppeteer / Chromium
                            ▼
                 WhatsApp Web (QR Paired)
```

---

## 2. Variabel Lingkungan (`.env`)

Pengaturan WhatsApp Gateway dikonfigurasi melalui `.env`:

```dotenv
# Aktifkan WhatsApp Web Sidecar
WHATSAPP_WEB_ENABLED=true
WHATSAPP_WEB_HOST=127.0.0.1
WHATSAPP_WEB_PORT=3000
WHATSAPP_WEB_TOKEN=sipades_wa_sidecar_secret_token_2026
WHATSAPP_WEB_TIMEOUT=60
WHATSAPP_WEB_NPM_BINARY=npm.cmd # Khusus Windows (agar tidak terhalang ExecutionPolicy PowerShell)

# Persistence & UI
WHATSAPP_PERSIST_INCOMING=true
WHATSAPP_UI_ENABLED=false
```

---

## 3. Perintah Artisan (CLI Commands)

Package menyediakan namespace `whatsapp:*` untuk mengelola lifecycle sidecar:

| Perintah | Fungsi |
|---|---|
| `php artisan whatsapp:sidecar:install` | Mengunduh dependensi Node.js (`sidecar/node_modules`) |
| `php artisan whatsapp:sidecar:start` | Memulai proses Node.js sidecar di background |
| `php artisan whatsapp:sidecar:status` | Memeriksa status penginstalan & koneksi sidecar |
| `php artisan whatsapp:sidecar:stop` | Menghentikan proses sidecar |
| `php artisan whatsapp:web:listen main` | Mendengarkan event SSE dari sidecar dan memicu event Laravel |
| `php artisan whatsapp:health` | Memeriksa kesehatan backend sidecar & Cloud API |

---

## 4. Cara Menjalankan & Pair WhatsApp Pertama Kali

1. **Instal Dependensi Sidecar (One-time):**
   ```bash
   php artisan whatsapp:sidecar:install
   ```

2. **Jalankan Database Migration:**
   ```bash
   php artisan migrate
   ```

3. **Buka Panel Filament:**
   - Akses menu **WhatsApp Gateway** &rarr; **Status & Pairing WhatsApp**.
   - Klik **Start / Pairing QR**.
   - Scan QR code yang muncul di layar dengan aplikasi WhatsApp HP Desa (**Setelan** &rarr; **Perangkat Tertaut** &rarr; **Tautkan Perangkat**).
   - Indikator status akan berubah menjadi <span style="color:green;font-weight:bold;">ONLINE / READY</span>.

4. **Pengujian Kirim Pesan:**
   - Masukkan Nomor HP Tujuan (misal `08123456789` / `628123456789`).
   - Tulis isi pesan test.
   - Klik **Kirim Pesan Pengujian**.

---

## 5. Penggunaan dalam Code / Developer Guide

### Menggunakan `WhatsappGatewayService`
```php
use App\Services\WhatsappGatewayService;

$gateway = app(WhatsappGatewayService::class);

// Kirim pesan WhatsApp (otomatis formatting 08xx -> 628xx)
$response = $gateway->send('08123456789', 'Halo! Permohonan surat Anda telah disetujui.');

if ($response['sukses']) {
    // Pesan berhasil terkirim
}
```

### Mengirim via Queue Job (`KirimNotifikasiWhatsappJob`)
```php
use App\Jobs\KirimNotifikasiWhatsappJob;

KirimNotifikasiWhatsappJob::dispatch(
    noHp: $warga->no_hp,
    pesan: 'Status pengajuan surat Anda: DISETUJUI',
    userId: $warga->id,
    pengajuanSuratId: $pengajuan->id
);
```

---

## 6. Hasil Pengujian (Automated Testing)

Pengujian otomatis telah dibuat dan dipastikan **LULUS 100%**:

```bash
php artisan test
```

### Hasil Test Suite:
- `Tests\Unit\WhatsappGatewayServiceTest`:
  - `test_format_no_hp_converts_local_08_to_628` &rarr; PASSED
  - `test_check_connection_status_returns_online_when_ready` &rarr; PASSED
  - `test_send_returns_success_response` &rarr; PASSED
- `Tests\Feature\WhatsappGatewaySettingsPageTest`:
  - `test_whatsapp_gateway_settings_page_can_be_rendered` &rarr; PASSED
  - `test_can_send_test_whatsapp_message_from_settings_page` &rarr; PASSED

---

## 7. Supervisor Configuration (Produksi)

Pada server produksi Linux, jalankan listener event & sidecar di bawah **Supervisor** (`/etc/supervisor/conf.d/sipades-whatsapp.conf`):

```ini
[program:sipades-wa-listener]
process_name=%(program_name)s
command=php /var/www/sipades/artisan whatsapp:web:listen main
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/sipades/storage/logs/wa-listener.log
```
