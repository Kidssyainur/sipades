<x-mail::message>
# Verifikasi Pendaftaran Akun

Halo {{ $nama }},

Terima kasih telah mendaftar di Portal Pelayanan Desa Karduluk. Gunakan kode berikut untuk memverifikasi akun Anda:

<x-mail::panel>
# {{ $kodeOtp }}
</x-mail::panel>

Kode ini berlaku selama **10 menit**. Jangan bagikan kode ini kepada siapa pun.

Jika Anda tidak merasa mendaftar, abaikan email ini.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
