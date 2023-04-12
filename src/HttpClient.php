<?php

namespace Nicdev\WebflowSdk;

use Exception;
use GuzzleHttp\Client;

class HttpClient
{
    const BASE_URL = 'https://api.webflow.com';

    public function __construct(
        private $token,
        private $client = null,
        private $headers = [],
        private $result = [],
    ) {
        $this->client = $client ?: new Client([
            'base_uri' => self::BASE_URL,
        ]);
        $this->headers = ['headers' => [
            'Authorization' => 'Bearer '.$this->token,
            'Accept' => 'application/json',
        ]];
    }

    public function get($path, array $query = []): array
    {
        $response = $this->client->get($path, [...$this->headers, 'query' => $query]);

        return $this->respond($response);
    }

    public function post($path): array
    {
        $response = $this->client->post($path, $this->headers);

        return $this->respond($response);
    }

    public function delete($path): array
    {
        $response = $this->client->delete($path, $this->headers);

        return $this->respond($response);
    }

    public function put($path): array
    {
        $response = $this->client->put($path, $this->headers);

        return $this->respond($response);
    }

    public function patch($path): array
    {
        $response = $this->client->patch($path, $this->headers);

        return $this->respond($response);
    }

    public function respond($response): array
    {
        if ($response->getStatusCode() === 200) {
            $this->result = json_decode($response->getBody(), true);
            
            return $this->result;
        }
        
        throw new Exception('Webflow API Error: '.$response->getStatusCode().' '.$response->getReasonPhrase());
    }

    public function lastResult(): array
    {
        return $this->result;
    }
}
