# Laporan Pengujian Fungsional & Browser E2E - SIPADES Desa Karduluk

Document Version: **1.0.0**  
Tanggal Pengujian: **29 Juli 2026**  
Lingkungan Pengujian: **Local Development (PHP 8.2 / SQLite / Chrome Headless & Live Browser)**  
Metode Pengujian: **Blackbox & End-to-End (E2E) Functional Testing**  

---

## Tabel Laporan Pengujian (Testing Matrix)

| ID | Kasus yang Dites | Inputan / Data Digunakan | Output yang Diharapkan | Output yang Dikeluarkan | Status Keberhasilan |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-01** | Autentikasi Login Portal Warga | Email: `warga@karduluk.desa.id`<br>Password: `password` | Berhasil login dan masuk ke Beranda Portal Warga (`/portal/dashboard`) | Diarahkan ke `/portal/dashboard` dengan banner nama warga & NIK | **BERHASIL (PASS)** |
| **TC-02** | Navigasi Dashboard & Statistik Warga | Klik menu **Beranda** (`/portal/dashboard`) | Menampilkan kartu statistik total pengajuan, proses, selesai, & shortcut | Tampil 3 statistik card, grid 4 shortcut layanan, dan tabel pengajuan terbaru | **BERHASIL (PASS)** |
| **TC-03** | Katalog Jenis Surat & Form Pengajuan | Klik menu **Ajukan Surat** (`/portal/pengajuan/buat`) | Menampilkan 7 jenis surat lengkap dengan estimasi hari & syarat | Katalog 7 jenis surat tampil lengkap dengan syarat & form dinamis | **BERHASIL (PASS)** |
| **TC-04** | Riwayat Pengajuan Saya & Filter Status | Klik menu **Pengajuan Saya** (`/portal/pengajuan`), pilih filter status | Menampilkan tabel pengajuan warga tersaring akurat sesuai filter | Data pengajuan tersaring akurat sesuai filter status yang dipilih | **BERHASIL (PASS)** |
| **TC-05** | Detail Pengajuan & Lacak Status Warga | Klik **Detail / Lacak** pada pengajuan (`/portal/pengajuan/{id}/status`) | Tampil visualisasi stepper progress level 1-3 & rincian berkas | Stepper progress visual, rincian berkas, dan log catatan approval tampil | **BERHASIL (PASS)** |
| **TC-06** | Arsip Surat Terbit & Unduh PDF Warga | Klik **Surat Terbit** (`/portal/surat-terbit`) -> Klik **Unduh PDF (Signed)** | Membuka & mengunduh PDF surat resmi terbit via Signed URL tanpa error 404 | File PDF surat resmi terunduh bersih dengan nomor surat & pengesahan TTE | **BERHASIL (PASS)** |
| **TC-07** | Rendering TTE & Base64 QR Code PDF | Membuka file PDF surat terbit yang telah diunduh | Footer PDF memuat kotak pengesahan TTE resmi dan gambar QR Code | Tampil kotak hijau TTE RESMI TERVERIFIKASI + gambar QR Code & TTE ID | **BERHASIL (PASS)** |
| **TC-08** | Halaman Publik Verifikasi QR Code TTE | Akses URL publik `/verifikasi-surat/{tte_token}` | Menampilkan halaman keabsahan resmi dokumen surat desa | Tampil halaman verifikasi publik dengan badge hijau "✓ Dokumen Resmi Terverifikasi" | **BERHASIL (PASS)** |
| **TC-09** | Profil Saya & Identitas Kependudukan SIAK | Klik menu **Profil Saya** (`/portal/profil`), ubah No. HP WhatsApp | Menampilkan data kependudukan SIAK & form pembaruan kontak/password | Data SIAK tampil akurat, No. HP WhatsApp & Password berhasil diperbarui | **BERHASIL (PASS)** |
| **TC-10** | Autentikasi Login Filament Admin Panel | Email: `admin@karduluk.desa.id`<br>Password: `password` di `/admin/login` | Login berhasil dan diarahkan ke Dashboard Admin Panel (`/admin`) | Diarahkan ke Dashboard Admin dengan tema Emerald/Slate & branding SIPADES | **BERHASIL (PASS)** |
| **TC-11** | Dashboard Admin & Chart Widgets | Akses URL `/admin` | Tampil 4 widget antrian approval level 1-3 & Donut Chart jenis surat | Widget antrian riil dan Donut Chart terender bersih tanpa error SvgNotFound | **BERHASIL (PASS)** |
| **TC-12** | Status Live Go-WA Gateway | Buka menu **Status & Tes Go-WA Gateway** -> Klik **Cek Status Live** | Badge status menampilkan ONLINE / TERHUBUNG & detail parameter | Badge `ONLINE / TERHUBUNG` tampil dengan info URL `http://203.145.34.217:3000/` | **BERHASIL (PASS)** |
| **TC-13** | Tes Pengiriman Pesan WA Direct | No. HP: `6281234567890`<br>Pesan: `Pesan pengujian SIPADES` | Pesan terkirim via API Go-WA & log tersimpan di `notifikasi_log` | Tampil notifikasi sukses dan log tersimpan di database dengan status `terkirim` | **BERHASIL (PASS)** |
| **TC-14** | Action Kirim Ulang (Retry) Log Notifikasi | Pada menu **Log Notifikasi**, klik tombol **Kirim Ulang (Retry)** | Pesan diproses ulang melalui Go-WA Gateway & log ter-update | Modal konfirmasi muncul, request dikirim ulang dan log ter-update | **BERHASIL (PASS)** |
| **TC-15** | Pengaturan Approval Dinamis Jenis Surat | Pada menu **Jenis Surat**, edit alur approval (1, 2, atau 3 level) | Admin dapat menentukan jumlah level approval & toggle TTE per surat | Opsi jumlah level approval & toggle TTE Kades tersimpan & memengaruhi alur | **BERHASIL (PASS)** |

---

## Ringkasan Eksekutif

- **Total Kasus Uji**: 15 Test Cases
- **Jumlah Lulus (PASS)**: 15 Test Cases (100%)
- **Jumlah Gagal (FAIL)**: 0 Test Cases (0%)
- **Kesimpulan**: Seluruh fitur sistem pelayanan desa (SIPADES) telah berfungsi secara optimal, aman, dan sesuai spesifikasi `@PRD.md`.
