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
