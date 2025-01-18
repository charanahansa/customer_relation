<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait BulkSmsTrait {

    use CurlTrait;

    private $accessToken;
    private $refreshToken;

    public function login(){

        $url = env('BULK_SMS_API_BASE_URL') . '/login';
        $headers = [
            'Content-Type: application/json',
            'Accept: */*',
            'X-API-VERSION: v1',
        ];

        $body = [
            'username' => env('BULK_SMS_USERNAME'),
            'password' => env('BULK_SMS_PASSWORD'),
        ];

        $response = $this->sendCurlRequest($url, 'POST', $headers, $body);

        if ($response['httpCode'] === 200) {
            $this->accessToken = $response['response']['accessToken'];
            $this->refreshToken = $response['response']['refreshToken'];
        } else {
            Log::error('Login failed', ['response' => $response]);
            throw new \Exception('Login to Bulk SMS API failed.');
        }
    }

    public function renewToken(){

        $url = env('BULK_SMS_API_BASE_URL') . '/token/accessToken';
        $headers = [
            'Content-Type: application/json',
            'Accept: */*',
            'X-API-VERSION: v1',
            'Authorization: Bearer ' . $this->refreshToken,
        ];

        $response = $this->sendCurlRequest($url, 'GET', $headers);

        if ($response['httpCode'] === 200) {

            $this->accessToken = $response['response']['accessToken'];

        } else {

            Log::error('Token renewal failed', ['response' => $response]);
            $this->login(); // Retry login if token renewal fails
        }
    }

    public function sendSms($campaignName, $mask, $numbers, $content) {

        if (!$this->accessToken) {
            $this->login();
        }

        $url = env('BULK_SMS_API_BASE_URL') . '/sendsms';
        $headers = [
            'Content-Type: application/json',
            'Accept: */*',
            'X-API-VERSION: v1',
            'Authorization: Bearer ' . $this->accessToken,
        ];

        $body = [
            'campaignName' => $campaignName,
            'mask' => $mask,
            'numbers' => $numbers,
            'content' => $content,
        ];

        $response = $this->sendCurlRequest($url, 'POST', $headers, $body);

        if ($response['httpCode'] === 200) {

            return $response['response'];

        } elseif ($response['httpCode'] === 401) {

            $this->renewToken();
            return $this->sendSms($campaignName, $mask, $numbers, $content);
            
        } else {

            Log::error('SMS sending failed', ['response' => $response]);
            throw new \Exception('Failed to send SMS.');
        }
    }
}
