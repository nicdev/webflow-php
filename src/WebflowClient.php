<?php

namespace Nicdev\WebflowSdk;

use GuzzleHttp\Client;


class WebflowClient {
    private $client;
    CONST BASE_URL = 'https://api.webflow.com';

    public function __construct($httpClient = null) {
        $this->client = $httpClient ?: new Client([
            'base_uri' => self::BASE_URL
        ]);
    }

    public function get($path) : array
    {
        $response = $this->client->get($path);
        return json_decode($response->getBody(), true);
    }
}

// // Example usage
// $apiKey = 'your_api_key_here';
// $client = new WebflowApiClient($apiKey);
// $data = $client->get('/sites');
// var_dump($data);
