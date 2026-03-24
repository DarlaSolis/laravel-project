<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message to a specific number via Ultramsg (QR-based unofficial API).
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

            // Ultramsg Credentials from .env
            $instanceId = env('ULTRAMSG_INSTANCE_ID');
            $token = env('ULTRAMSG_TOKEN');

            if (!$instanceId || !$token) {
                throw new \Exception('Ultramsg credentials (Instance ID or Token) are not configured in .env.');
            }

            // Ultramsg API endpoint
            $url = "https://api.ultramsg.com/{$instanceId}/messages/chat";

            // Send POST request
            $response = Http::asForm()->post($url, [
                'token' => $token,
                'to' => $phone,
                'body' => $message,
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp message sent successfully to [{$phone}] via Ultramsg.");
                return true;
            } else {
                throw new \Exception("Ultramsg API rejected the request: " . $response->body());
            }

        } catch (\Exception $e) {
            // Log the error
            Log::error("Failed to send WhatsApp message to [{$phone}]. Error: " . $e->getMessage(), [
                'phone' => $phone,
                'message' => $message,
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }
}
