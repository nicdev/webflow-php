<?php

namespace Nicdev\WebflowSdk;

use GuzzleHttp\Client;


class WebflowClient
{
    const BASE_URL = 'https://api.webflow.com';

    public function __construct(
        private $apiKey,
        private $client = new Client([
            'base_uri' => self::BASE_URL
        ])
    ) {
        $this->apiKey = $apiKey;
    }

    public function get($path): array
    {
        $response = $this->client->get($path);
        return json_decode($response->getBody(), true);
    }

    public function post($path): array
    {
        $response = $this->client->post($path);
        return json_decode($response->getBody(), true);
    }
}
