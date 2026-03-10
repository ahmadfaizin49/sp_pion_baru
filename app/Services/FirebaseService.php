<?php

namespace App\Services;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    /**
     * Kirim notifikasi ke satu token
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): void
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body));

        if (!empty($data)) {
            $message = $message->withData($data);
        }

        Firebase::messaging()->send($message);
    }

    /**
     * Kirim notifikasi ke banyak token
     */
    public function sendToTokens(array $tokens, string $title, string $body, array $data = []): void
    {
        foreach ($tokens as $token) {
            $this->sendToToken($token, $title, $body, $data);
        }
    }
}
