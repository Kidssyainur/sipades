<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsappGatewayService
{
    /**
     * Kirim pesan WhatsApp melalui Go-WA REST API Gateway (go-whatsapp-web-multidevice).
     */
    public function send(string $noHp, string $pesan): array
    {
        $baseUrl = rtrim(config('services.gowa.url', 'http://203.145.34.217:3000/'), '/');
        $deviceId = config('services.gowa.device_id', 'eeec6262-4b8c-4cf2-be9d-9c8ad02631b6');
        $username = config('services.gowa.username', 'admin');
        $password = config('services.gowa.password', 'Jitu008001');
        $timeout = (int) config('services.gowa.timeout', 30);

        $formattedNoHp = $this->formatNoHp($noHp);
        $endpoint = $baseUrl . '/send/message';

        $client = Http::timeout($timeout)
            ->acceptJson()
            ->withBasicAuth($username, $password)
            ->withHeaders([
                'X-Device-Id' => $deviceId,
            ]);

        // Standard JSON payload go-whatsapp-web-multidevice
        $response = $client->post($endpoint, [
            'phone' => $formattedNoHp,
            'message' => $pesan,
        ]);

        return [
            'sukses' => $response->successful(),
            'status_code' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json(),
        ];
    }

    /**
     * Cek status koneksi live server Go-WA.
     */
    public function checkConnectionStatus(): array
    {
        $baseUrl = rtrim(config('services.gowa.url', 'http://203.145.34.217:3000/'), '/');
        $deviceId = config('services.gowa.device_id', 'eeec6262-4b8c-4cf2-be9d-9c8ad02631b6');
        $username = config('services.gowa.username', 'admin');
        $password = config('services.gowa.password', 'Jitu008001');
        $timeout = (int) config('services.gowa.timeout', 5);

        try {
            $response = Http::timeout($timeout)
                ->acceptJson()
                ->withBasicAuth($username, $password)
                ->withHeaders([
                    'X-Device-Id' => $deviceId,
                ])
                ->get($baseUrl . '/app/devices');

            if ($response->successful()) {
                return [
                    'online' => true,
                    'status' => 'CONNECTED',
                    'pesan' => 'Server Go-WA online dan terhubung.',
                    'response' => $response->json(),
                ];
            }

            return [
                'online' => false,
                'status' => 'HTTP_ERROR_' . $response->status(),
                'pesan' => 'Server merespons status: ' . $response->status(),
                'response' => $response->body(),
            ];
        } catch (\Throwable $e) {
            return [
                'online' => false,
                'status' => 'DISCONNECTED',
                'pesan' => 'Gagal terhubung ke server Go-WA: ' . $e->getMessage(),
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
