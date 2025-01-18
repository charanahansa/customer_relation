<?php

namespace App\Traits;

trait CurlTrait {
    
    public function sendCurlRequest($url, $method, $headers = [], $body = null) {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => $headers,
        ]);

        if ($body) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            throw new \Exception("cURL Error: $error");
        }

        return [
            'httpCode' => $httpCode,
            'response' => json_decode($response, true),
        ];
    }
}
