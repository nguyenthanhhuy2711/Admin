<?php
if (!function_exists('callAPI')) {
    function callAPI($endpoint, $method = 'GET', $data = [], $contentType = 'json')
    {
        $baseUrl = "https://cuddly-exotic-snake.ngrok-free.app/";
        $url = $baseUrl . $endpoint;

        $headers = [];
        $payload = '';

        if ($contentType === 'json') {
            $headers[] = "Content-Type: application/json";
            $payload = json_encode($data);
        } elseif ($contentType === 'form') {
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
            $payload = http_build_query($data);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true); // Bắt lỗi HTTP
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);       // Timeout an toàn

        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return ['detail' => curl_error($ch)];
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}
