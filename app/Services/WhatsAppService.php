<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message to a specific number via the official Meta WhatsApp Cloud API.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The content of the message.
     * @return bool Returns true if the message was sent successfully.
     */
    public function sendMessage(string $phone, string $message): bool
    {
        try {
            // Validate that we have a phone number and message
            if (empty($phone) || empty($message)) {
                throw new \Exception('Phone number and message are required.');
            }

            // Meta App Credentials from .env
            $token = env('WHATSAPP_META_TOKEN');
            $phoneId = env('WHATSAPP_META_PHONE_ID');
            $version = env('WHATSAPP_META_VERSION', 'v17.0');

            if (!$token || !$phoneId) {
                throw new \Exception('WhatsApp Meta credentials (Token or Phone ID) are not configured in .env.');
            }

            // Official Meta API endpoint
            $url = "https://graph.facebook.com/{$version}/{$phoneId}/messages";

            // Send POST request
            $response = Http::withToken($token)
                ->post($url, [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone,
                    'type' => 'text',
                    'text' => [
                        'body' => $message,
                    ],
                ]);

            if ($response->successful()) {
                Log::info("WhatsApp message sent successfully to [{$phone}] via Meta API.");
                return true;
            } else {
                throw new \Exception("Meta API rejected the request: " . $response->body());
            }

        } catch (\Exception $e) {
            // Log the error to ensure it doesn't break the application flow but we still have a record of it
            Log::error("Failed to send WhatsApp message to [{$phone}]. Error: " . $e->getMessage(), [
                'phone' => $phone,
                'message' => $message,
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }
}
