<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Kstmostofa\LaravelWhatsApp\Exceptions\SidecarException;
use Kstmostofa\LaravelWhatsApp\Facades\WhatsApp;
use Kstmostofa\LaravelWhatsApp\Web\SidecarManager;

class WhatsappGatewayService
{
    /**
     * Kirim pesan WhatsApp melalui laravel-whatsapp Web Sidecar backend.
     */
    public function send(string $noHp, string $pesan, string $sessionId = 'main'): array
    {
        $formattedNoHp = $this->formatNoHp($noHp);

        try {
            $response = WhatsApp::web($sessionId)->messages()->sendText($formattedNoHp, $pesan);

            return [
                'sukses' => true,
                'status_code' => 200,
                'body' => is_string($response) ? $response : json_encode($response),
                'json' => is_array($response) ? $response : null,
            ];
        } catch (\Throwable $e) {
            return [
                'sukses' => false,
                'status_code' => 500,
                'body' => $e->getMessage(),
                'json' => null,
            ];
        }
    }

    /**
     * Kirim pesan OTP WhatsApp menggunakan Template Resmi SIPADES.
     */
    public function sendOtpMessage(string $noHp, string $nama, string $kodeOtp, string $tujuan = 'Autentikasi Akun'): array
    {
        $pesan = "🏛️ *SIPADES DESA KARDULUK*\n"
            . "_Sistem Informasi Pelayanan Desa Karduluk_\n\n"
            . "Halo *{$nama}*,\n\n"
            . "Kode OTP Anda untuk *{$tujuan}* adalah:\n\n"
            . "🔑 *{$kodeOtp}*\n\n"
            . "⚠️ _Jangan bagikan kode OTP ini kepada siapa pun, termasuk pihak desa. Kode ini berlaku selama 10 menit._\n\n"
            . "Terima kasih,\n"
            . "*Pemerintah Desa Karduluk*";

        return $this->send($noHp, $pesan);
    }

    /**
     * Cek status koneksi live server sidecar laravel-whatsapp.
     */
    public function checkConnectionStatus(string $sessionId = 'main'): array
    {
        try {
            $state = WhatsApp::web($sessionId)->state();
            $status = strtolower($state['status'] ?? 'unknown');

            $isReady = ($status === 'ready');

            return [
                'online' => $isReady,
                'status' => strtoupper($status),
                'pesan' => match ($status) {
                    'ready' => 'Sidecar WhatsApp Web online dan terhubung (Ready).',
                    'qr' => 'Silakan scan QR Code WhatsApp di bawah ini.',
                    'initializing' => 'Menginisialisasi browser WhatsApp Web...',
                    'authenticated' => 'Autentikasi berhasil, memuat sesi...',
                    'disconnected' => 'Sesi WhatsApp terputus (Disconnected).',
                    'auth_failure' => 'Autentikasi gagal. Silakan reset/destroy sesi dan scan ulang.',
                    'error' => 'Sidecar WhatsApp mengalami error. Coba Reset Pairing (Hapus Sesi) lalu scan ulang.',
                    default => 'Status koneksi sidecar: ' . strtoupper($status),
                },
                'response' => $state,
            ];
        } catch (SidecarException $e) {
            if ($e->getCode() === 404) {
                return [
                    'online' => false,
                    'status' => 'OFFLINE',
                    'pesan' => 'Sesi WhatsApp belum dibuat. Klik "Start / Pairing QR" untuk memulai.',
                    'response' => null,
                ];
            }

            return [
                'online' => false,
                'status' => 'UNREACHABLE',
                'pesan' => 'Sidecar WhatsApp tidak dapat dihubungi: ' . $e->getMessage(),
                'response' => null,
            ];
        } catch (\Throwable $e) {
            return [
                'online' => false,
                'status' => 'ERROR',
                'pesan' => 'Gagal mengecek status WhatsApp: ' . $e->getMessage(),
                'response' => null,
            ];
        }
    }

    /**
     * Ambil data URI QR Code dari sidecar untuk sesi aktif.
     */
    public function getQrCode(string $sessionId = 'main'): ?string
    {
        try {
            $qrData = WhatsApp::web($sessionId)->qr();

            return $qrData['qr'] ?? null;
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Pastikan server sidecar HTTP sudah berjalan.
     *
     * Urutan pengecekan:
     * 1. Endpoint /health dianggap sumber kebenaran — bila hidup, tidak peduli
     *    siapa yang menjalankannya (container Docker, supervisor, dsb).
     * 2. Bila mati dan sidecar dikelola eksternal, jangan spawn duplikat.
     * 3. Bila mati dan dikelola Laravel, jalankan proses Node.js otomatis.
     */
    public function ensureSidecarRunning(): bool
    {
        if ($this->isSidecarHealthy()) {
            return true;
        }

        if (config('laravel-whatsapp.web.sidecar.managed_externally', false)) {
            $host = config('laravel-whatsapp.web.host');
            $port = config('laravel-whatsapp.web.port');

            throw new SidecarException(
                "Sidecar WhatsApp eksternal tidak dapat dihubungi di http://{$host}:{$port}. " .
                'Pastikan service sidecar (mis. container `whatsapp`) berjalan.'
            );
        }

        /** @var SidecarManager $manager */
        $manager = app(SidecarManager::class);

        if (! $manager->isRunning()) {
            if (! $manager->isInstalled()) {
                $manager->install();
            }

            try {
                $manager->start();
            } catch (\Throwable $e) {
                // Proses lain mungkin baru saja menyalakan sidecar (race).
                // Hanya lempar error bila endpoint benar-benar masih mati.
                if (! $this->isSidecarHealthy()) {
                    throw $e;
                }
            }
        }

        return $this->isSidecarHealthy() || $manager->isRunning();
    }

    /**
     * Cek langsung endpoint /health sidecar (tanpa melihat PID file).
     */
    public function isSidecarHealthy(): bool
    {
        $host = (string) config('laravel-whatsapp.web.host');
        $port = (int) config('laravel-whatsapp.web.port');
        $token = (string) config('laravel-whatsapp.web.token');

        try {
            $request = Http::acceptJson()->timeout(3);

            if ($token !== '') {
                $request = $request->withToken($token);
            }

            $response = $request->get("http://{$host}:{$port}/health");

            return $response->successful() && $response->json('ok') === true;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Format nomor HP agar standar internasional (08xx -> 628xx).
     */
    public function formatNoHp(string $noHp): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $noHp);

        if (str_starts_with($cleaned, '0')) {
            return '62' . substr($cleaned, 1);
        }

        return $cleaned;
    }
}
