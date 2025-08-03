# BulkSMSIraq PHP SDK (V5)

BulkSMSIraq.com offers a robust and reliable OTP (One-Time Password) API solution tailored for businesses and developers in Iraq. Our platform enables fast and secure delivery of OTPs via WhatsApp, SMS, and Telegram, making it ideal for user verification, two-factor authentication (2FA), and transactional messaging. Whether you need an Iraq WhatsApp OTP API, a bulk SMS OTP gateway, or a fallback-enabled verification flow, BulkSMSIraq’s API is built to scale. Developers can easily integrate our RESTful API using PHP or any modern tech stack to send custom or auto-generated OTP codes, verify user responses, manage fallback channels (e.g., WhatsApp to SMS or Telegram), and deliver templated WhatsApp messages. With high delivery rates, multilingual support (English, Arabic, Kurdish), and low latency, BulkSMSIraq.com is the go-to solution for secure identity verification across Iraq.

## Installation

You can install the SDK via Composer:

```bash
composer require akamomer/bulksmsiraq-sdk-v5
```

## Example Usage

```php
<?php
require 'vendor/autoload.php';
use BulkSMSIraq\BulkSmsIraqApiClient;

// Example Usage
// NOTE: Replace 'YOUR_API_KEY_HERE' with your actual API key.
// The recipient and senderId are example values from your Postman collection.
$apiKey = 'YOUR_API_KEY_HERE';
$client = new BulkSmsIraqApiClient($apiKey);

$recipient = '9647xxxxxxxxx';
$senderId = 'SenderID';
$customOtp = '362514';
$otpRequestId = null;

try {
    // --- Folder 1 & 3: OTP with Fallback Examples ---
    echo "--- Folder 1 & 3: OTP with Fallback Examples ---\n";
    echo "Sending custom OTP via WhatsApp with SMS fallback (EN)...\n";
    $response = $client->sendCustomOtpWithSmsFallback($recipient, $senderId, $customOtp, 'en');
    $otpRequestId = $response['data']['request_id'] ?? null;
    print_r($response);

    echo "\nSending auto OTP via WhatsApp with Telegram fallback (AR)...\n";
    $response = $client->sendAutoOtpWithTelegramFallback($recipient, $senderId, 'ar');
    $otpRequestId = $response['data']['request_id'] ?? null;
    print_r($response);

    // --- Folder 2: OTP without Fallback Examples (New) ---
    echo "\n--- Folder 2: OTP without Fallback Examples (New) ---\n";
    echo "Sending OTP via WhatsApp with no fallback (EN)...\n";
    $response = $client->sendOtpWhatsAppWithoutFallback($recipient, $senderId, $customOtp, 'en');
    print_r($response);

    echo "\nSending OTP via SMS with no fallback (AR)...\n";
    $response = $client->sendOtpSmsWithoutFallback($recipient, $senderId, $customOtp, 'ar');
    print_r($response);

    echo "\nSending OTP via Telegram with no fallback (KU)...\n";
    $response = $client->sendOtpTelegramWithoutFallback($recipient, $senderId, $customOtp, 'ku');
    print_r($response);

    // --- Folder 3: Verify OTP Example ---
    if ($otpRequestId) {
        $otpCodeToVerify = '123456';
        echo "\n--- Folder 3: Verify Auto OTP Example ---\n";
        echo "Verifying OTP with ID '$otpRequestId'...\n";
        // Example with expire set to true
        $response = $client->verifyOtp($recipient, $otpCodeToVerify, $otpRequestId, true);
        print_r($response);
    }

    // --- Folder 4: General API Examples ---
    echo "\n--- Folder 4: General API Examples ---\n";
    echo "Sending a general SMS message...\n";
    $response = $client->sendSms($recipient, $senderId, 'Hello from the Bulk SMS Iraq API!');
    print_r($response);

    // NOTE: This will fail without a valid, pre-approved WhatsApp template.
    echo "\nSending a WhatsApp template message (may fail if template is not approved)...\n";
    $response = $client->sendWhatsAppTemplate($recipient, $senderId, '(template)(param1)(param2)(param3)');
    print_r($response);

} catch (GuzzleException $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
    echo "Request URL: " . $e->getRequest()->getUri() . "\n";
    echo "Response Body: " . $e->getResponse()?->getBody()->getContents() . "\n";
}
```

## About Us

[BulkSMSIraq.com](https://bulksmsiraq.com) provides reliable and secure OTP delivery services through WhatsApp, SMS, and Telegram. Ideal for two-factor authentication and critical user notifications.
