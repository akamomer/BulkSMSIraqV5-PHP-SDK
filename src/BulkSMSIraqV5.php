<?php

namespace BulkSMSIraq;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * A PHP client for the Bulk SMS Iraq V5 API, covering all available endpoints.
 *
 * This class handles authentication and requests for sending OTP messages with
 * and without fallback, as well as for general SMS and WhatsApp template messages.
 */
/**
 * A PHP client for the Bulk SMS Iraq V5 API, covering all available endpoints.
 *
 * This class handles authentication and requests for sending OTP messages with
 * and without fallback, as well as for general SMS and WhatsApp template messages.
 */
class BulkSmsIraqApiClient
{
    /**
     * The base URL for the Standingtech API.
     * @var string
     */
    private const BASE_URI = 'https://gateway.standingtech.com/api/v5/';

    /**
     * The Guzzle HTTP client instance.
     * @var Client
     */
    private Client $client;

    /**
     * The API key used for authorization.
     * @var string
     */
    private string $apiKey;

    /**
     * BulkSmsIraqApiClient constructor.
     *
     * @param string $apiKey Your API key from Standingtech.
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => self::BASE_URI,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    // --- OTP API with Fallback (Folder 1 & 3) ---

    /**
     * Sends a custom OTP message via a primary channel with a fallback.
     *
     * @param string $recipient The recipient's phone number.
     * @param string $senderId The sender ID to be used.
     * @param string $message The OTP code to send or 'auto' for automatic generation.
     * @param string $channel The primary channel ('whatsapp').
     * @param string $fallback The fallback channel ('sms' or 'telegram').
     * @param string $lang The language of the message ('en', 'ar', 'ku').
     * @return array The API response as an associative array.
     * @throws GuzzleException If the API request fails.
     */
    private function sendOtpWithFallback(
        string $recipient,
        string $senderId,
        string $message,
        string $channel,
        string $fallback,
        string $lang
    ): array {
        $body = [
            'recipient' => $recipient,
            'sender_id' => $senderId,
            'channel' => $channel,
            'message' => $message,
            'fallback' => $fallback,
            'lang' => $lang,
        ];

        try {
            $response = $this->client->post('otp/send', [
                'json' => $body,
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

    /**
     * Sends a custom OTP message via WhatsApp with a fallback to SMS.
     *
     * @param string $recipient The recipient's phone number.
     * @param string $senderId The sender ID.
     * @param string $message The custom OTP code to send.
     * @param string $lang The language ('en', 'ar', 'ku').
     * @return array The API response.
     * @throws GuzzleException
     */
    public function sendCustomOtpWithSmsFallback(string $recipient, string $senderId, string $message, string $lang = 'en'): array
    {
        return $this->sendOtpWithFallback($recipient, $senderId, $message, 'whatsapp', 'sms', $lang);
    }

    /**
     * Sends an automatically generated OTP via WhatsApp with a fallback to SMS.
     *
     * @param string $recipient The recipient's phone number.
     * @param string $senderId The sender ID.
     * @param string $lang The language ('en', 'ar', 'ku').
     * @return array The API response.
     * @throws GuzzleException
     */
    public function sendAutoOtpWithSmsFallback(string $recipient, string $senderId, string $lang = 'en'): array
    {
        return $this->sendOtpWithFallback($recipient, $senderId, 'auto', 'whatsapp', 'sms', $lang);
    }

    /**
     * Sends a custom OTP message via WhatsApp with a fallback to Telegram.
     *
     * @param string $recipient The recipient's phone number.
     * @param string $senderId The sender ID.
     * @param string $message The custom OTP code to send.
     * @param string $lang The language ('en', 'ar', 'ku').
     * @return array The API response.
     * @throws GuzzleException
     */
    public function sendCustomOtpWithTelegramFallback(string $recipient, string $senderId, string $message, string $lang = 'en'): array
    {
        return $this->sendOtpWithFallback($recipient, $senderId, $message, 'whatsapp', 'telegram', $lang);
    }

    /**
     * Sends an automatically generated OTP via WhatsApp with a fallback to Telegram.
     *
     * @param string $recipient The recipient's phone number.
     * @param string $senderId The sender ID.
     * @param string $lang The language ('en', 'ar', 'ku').
     * @return array The API response.
     * @throws GuzzleException
     */
    public function sendAutoOtpWithTelegramFallback(string $recipient, string $senderId, string $lang = 'en'): array
    {
        return $this->sendOtpWithFallback($recipient, $senderId, 'auto', 'whatsapp', 'telegram', $lang);
    }


    // --- OTP without Fallback (Folder 2) ---

    /**
     * Internal method to handle the OTP send logic for no fallback channels.
     *
     * @param string $recipient The recipient's phone number.
     * @param string $senderId The sender ID.
     * @param string $message The OTP code to send.
     * @param string $channel The channel to use ('whatsapp', 'sms', or 'telegram').
     * @param string $lang The language code.
     * @return array The API response as an associative array.
     * @throws GuzzleException
     */
    private function sendOtpWithoutFallbackInternal(string $recipient, string $senderId, string $message, string $channel, string $lang): array
    {
        $body = [
            'recipient' => $recipient,
            'sender_id' => $senderId,
            'channel' => $channel,
            'message' => $message,
            'fallback' => 'none',
            'lang' => $lang,
        ];

        try {
            $response = $this->client->post('otp/send', [
                'json' => $body,
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw $e;
        }
    }
    
    /**
     * Sends an OTP message via WhatsApp with no fallback.
     *
     * @param string $recipient The recipient's phone number.
     * @param string $senderId The sender ID.
     * @param string $message The OTP code to send.
     * @param string $lang The language ('en', 'ar', 'ku').
     * @return array The API response.
     * @throws GuzzleException
     */
    public function sendOtpWhatsAppWithoutFallback(string $recipient, string $senderId, string $message, string $lang = 'en'): array
    {
        return $this->sendOtpWithoutFallbackInternal($recipient, $senderId, $message, 'whatsapp', $lang);
    }
    
    /**
     * Sends an OTP message via SMS with no fallback.
     *
     * @param string $recipient The recipient's phone number.
     * @param string $senderId The sender ID.
     * @param string $message The OTP code to send.
     * @param string $lang The language ('en', 'ar', 'ku').
     * @return array The API response.
     * @throws GuzzleException
     */
    public function sendOtpSmsWithoutFallback(string $recipient, string $senderId, string $message, string $lang = 'en'): array
    {
        return $this->sendOtpWithoutFallbackInternal($recipient, $senderId, $message, 'sms', $lang);
    }
    
    /**
     * Sends an OTP message via Telegram with no fallback.
     *
     * @param string $recipient The recipient's phone number.
     * @param string $senderId The sender ID.
     * @param string $message The OTP code to send.
     * @param string $lang The language ('en', 'ar', 'ku').
     * @return array The API response.
     * @throws GuzzleException
     */
    public function sendOtpTelegramWithoutFallback(string $recipient, string $senderId, string $message, string $lang = 'en'): array
    {
        return $this->sendOtpWithoutFallbackInternal($recipient, $senderId, $message, 'telegram', $lang);
    }


    // --- OTP Verification (Folder 3) ---

    /**
     * Verifies an OTP code.
     *
     * @param string $recipient The recipient's phone number.
     * @param string $code The OTP code to verify.
     * @param string $id The request ID received from the send OTP response.
     * @param bool $expire If true, checks if the code is within the 30-minute expiration window.
     * @return array The API response as an associative array.
     * @throws GuzzleException If the API request fails.
     */
    public function verifyOtp(string $recipient, string $code, string $id, bool $expire = false): array
    {
        $body = [
            'recipient' => $recipient,
            'code' => $code,
            'id' => $id,
        ];

        if ($expire) {
            $body['expire'] = 'yes';
        }

        try {
            $response = $this->client->post('otp/verify', [
                'json' => $body,
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

    // --- General API (Folder 4) ---

    /**
     * Sends a general SMS message.
     *
     * @param string $recipient The recipient's phone number.
     * @param string $senderId The sender ID.
     * @param string $message The message content.
     * @return array The API response.
     * @throws GuzzleException
     */
    public function sendSms(string $recipient, string $senderId, string $message): array
    {
        $body = [
            'recipient' => $recipient,
            'sender_id' => $senderId,
            'message' => $message,
        ];

        try {
            $response = $this->client->post('sms/send', [
                'json' => $body,
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

    /**
     * Sends a WhatsApp message using a pre-approved template.
     *
     * NOTE: This API requires a pre-approved template name and parameters.
     * The message should be formatted like (template)(param1)(param2).
     *
     * @param string $recipient The recipient's phone number.
     * @param string $senderId The sender ID.
     * @param string $message The formatted template message.
     * @return array The API response.
     * @throws GuzzleException
     */
    public function sendWhatsAppTemplate(string $recipient, string $senderId, string $message): array
    {
        $body = [
            'recipient' => $recipient,
            'sender_id' => $senderId,
            'message' => $message,
        ];

        try {
            $response = $this->client->post('whatsapp/send', [
                'json' => $body,
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw $e;
        }
    }
}
