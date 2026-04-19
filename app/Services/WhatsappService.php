<?php

namespace App\Services;

use App\Models\WhatsappConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    public static function sendMessage($target, $message)
    {
        $config = WhatsappConfig::first();

        if (!$config || !$config->token) {
            Log::error('Whatsapp Config or Token not found.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $config->token,
            ])->asMultipart()->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $result = $response->json();

            if ($response->successful() && ($result['status'] ?? false)) {
                return true;
            }

            Log::error('Fonnte API Error: ', $result ?? []);
            return false;
        } catch (\Exception $e) {
            Log::error('Whatsapp Service Exception: ' . $e->getMessage());
            return false;
        }
    }
}
