<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmService
{
    public static function sendToAdmins(string $title, string $body): void
    {
        $tokens = \App\Models\User::whereNotNull('fcm_token')
        ->pluck('fcm_token');

        if ($tokens->isEmpty()) return;

        $messaging = (new Factory)
            ->withServiceAccount(storage_path('app/firebase-credentials.json'))
            ->createMessaging();

        foreach ($tokens as $token) {
            try {
                $message = CloudMessage::withTarget('token', $token)
                    ->withNotification(Notification::create($title, $body));

                $messaging->send($message);
            } catch (\Throwable $e) {
                \Log::error('FCM error: ' . $e->getMessage());
            }
        }
    }
}