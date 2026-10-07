<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function sendMessage(string $target, string $message)
    {
        // KITA TEMPEL LANGSUNG TOKENNYA DI SINI UNTUK MEMBYPASS BUG LARAVEL
        // Pastikan token di dalam tanda kutip tunggal ('...')
        $token = '45RQcHvqFLPWDwHe2UoF'; 

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);
            
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Fonnte Error: ' . $e->getMessage());
            return false;
        }
    }
}