<?php

namespace App\Services;

use Kstmostofa\LaravelWhatsApp\Exceptions\SidecarException;
use Kstmostofa\LaravelWhatsApp\Facades\WhatsApp;

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
     * Cek status koneksi live server sidecar laravel-whatsapp.
     */
    public function checkConnectionStatus(string $sessionId = 'main'): array
    {
        try {
            $state = WhatsApp::web($sessionId)->state();
            $status = $state['status'] ?? 'unknown';

            $isReady = ($status === 'ready');

            return [
                'online' => $isReady,
                'status' => strtoupper($status),
                'pesan' => $isReady
                    ? 'Sidecar WhatsApp Web online dan terhubung (Ready).'
                    : 'Status koneksi sidecar: ' . strtoupper($status),
                'response' => $state,
            ];
        } catch (SidecarException $e) {
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
