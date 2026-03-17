<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message to a specific number via the local Node.js microservice.
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

            // Node.js microservice endpoint
            $url = env('WHATSAPP_NODE_SERVICE_URL', 'http://localhost:3000/send-message');

            // Send POST request
            $response = Http::post($url, [
                'phone' => $phone,
                'message' => $message,
            ]);

            if ($response->successful() && $response->json('success') === true) {
                Log::info("WhatsApp message sent successfully to [{$phone}] via Node service.");
                return true;
            } else {
                throw new \Exception("Node service rejected the request: " . $response->body());
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
