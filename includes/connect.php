<?php
if (!function_exists('callAPI')) {
    function callAPI($endpoint, $method = 'GET', $data = [], $contentType = 'json')
    {
        $baseUrl = "https://cuddly-exotic-snake.ngrok-free.app/";
        $url = $baseUrl . $endpoint;

        $headers = '';
        $content = '';

        if ($contentType === 'json') {
            $headers = "Content-Type: application/json";
            $content = json_encode($data);
        } elseif ($contentType === 'form') {
            $headers = "Content-Type: application/x-www-form-urlencoded";
            $content = http_build_query($data);
        }

        $options = [
            "http" => [
                "method"  => $method,
                "header"  => $headers,
                "timeout" => 10
            ]
        ];

        if ($method === 'POST' || $method === 'PUT') {
            $options["http"]["content"] = $content;
        }

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        return $response ? json_decode($response, true) : [];
    }
}
