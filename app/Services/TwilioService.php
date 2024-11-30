<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\SmsMessage;
use Twilio\Rest\Client;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;
    protected $messagingServiceSid;
    protected $settings;

    public function __construct()
    {
        $this->settings = Setting::first();
        $this->checkTwilioCredentials();
    }

    protected function checkTwilioCredentials(): void
    {
        if (!$this->settings?->twilio_account_sid ||
            !$this->settings?->twilio_auth_token ||
            !$this->settings?->messaging_service_sid) {

            throw new Exception('Twilio credentials are not configured');
        }

        $this->client = new Client(
            $this->settings->twilio_account_sid,
            $this->settings->twilio_auth_token
        );

        $this->messagingServiceSid = $this->settings->messaging_service_sid;
    }

    public function formatPhoneNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);

        if (str_starts_with($number, '0')) {
            $number = substr($number, 1);
        }

        if (!str_starts_with($number, '+233')) {
            $number = '+233' . $number;
        }

        return $number;
    }

    public function sendMessage(SmsMessage $message)
    {
        try {
            // Prepare message parameters
            $messageParams = [
                "messagingServiceSid" => $this->messagingServiceSid,
                "body" => $message->message_content
            ];

            // Send message using Twilio client
            $response = $this->client->messages->create(
                $this->formatPhoneNumber($message->recipient_number),
                $messageParams
            );

            // Update message with Twilio's status
            $message->update([
                'status' => $response->status,
                'twilio_message_sid' => $response->sid,
                'delivery_log' => array_merge($message->delivery_log ?? [], [
                    [
                        'status' => $response->status,
                        'timestamp' => now()->toDateTimeString(),
                        'message' => 'Message processed by Twilio',
                        'twilio_status' => $response->status,
                        'error_code' => $response->errorCode ?? null,
                        'error_message' => $response->errorMessage ?? null,
                        'direction' => $response->direction,
                        'price' => $response->price ?? null,
                        'price_unit' => $response->priceUnit ?? null,
                    ]
                ])
            ]);

            return true;

        } catch (Exception $e) {
            // Update message with failed status
            $message->update([
                'status' => 'failed',
                'delivery_log' => array_merge($message->delivery_log ?? [], [
                    [
                        'status' => 'failed',
                        'timestamp' => now()->toDateTimeString(),
                        'message' => $e->getMessage(),
                        'error_code' => $e->getCode(),
                    ]
                ])
            ]);

            throw $e;
        }
    }
}
