<?php

namespace App\Services;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    /**
     * Kirim notifikasi ke satu token
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): void
    {
        try {
            $message = CloudMessage::new()
                ->withTarget('token', $token)
                ->withNotification(Notification::create($title, $body));

            if (!empty($data)) {
                $message = $message->withData($data);
            }

            Firebase::messaging()->send($message);
        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
            // Token sudah tidak valid atau tidak ditemukan di project FCM ini
            Log::warning("FCM Token NotFound: {$token}. Error: " . $e->getMessage());
            // Bisa tambahkan logika untuk menghapus token dari DB jika perlu
            // \App\Models\User::where('fcm_token', $token)->update(['fcm_token' => null]);
        } catch (\Exception $e) {
            // General exception jikalau ada error network dll
            Log::error("FCM Send Error: " . $e->getMessage());
        }
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
