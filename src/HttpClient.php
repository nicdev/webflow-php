<?php

namespace Nicdev\WebflowSdk;

use GuzzleHttp\Client;

class HttpClient
{
    const BASE_URL = 'https://api.webflow.com';

    public function __construct(
        private $token,
        private $client = new Client([
            'base_uri' => self::BASE_URL,
        ]),
        private $headers = []
    ) {
        $this->headers = ['headers' => [
            'Authorization' => 'Bearer '.$this->token,
            'Accept' => 'application/json',
        ]];
    }

    public function get($path): array
    {
        $response = $this->client->get($path, $this->headers);

        return json_decode($response->getBody(), true);
    }

    public function post($path): array
    {
        $response = $this->client->post($path, $this->headers);

        return json_decode($response->getBody(), true);
    }
}
