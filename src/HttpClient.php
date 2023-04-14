<?php

namespace Nicdev\WebflowSdk;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class HttpClient
{
    const BASE_URL = 'https://api.webflow.com';

    public function __construct(
        private $token,
        private $client = null,
        private $headers = [],
        private $result = [],
        private $history = [],
    ) {
        if ($client) {
            $this->client = $client;
        } else {
            // $historyMiddleware = Middleware::history($this->history);
            // $stack = HandlerStack::create();
            // $stack->push($historyMiddleware);

            $this->client = new Client([
                'base_uri' => self::BASE_URL,
                ...self::makeHandler(),
            ]);
        }

        $this->headers = ['headers' => [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ]];
    }

    public function get($path, array $query = []): array
    {
        ray('called get');
        ray($query);
        $response = $this->client->get($path, [...$this->headers, 'query' => $query]);

        return $this->respond($response);
    }

    public function post($path, $payload): array
    {
        // echo json_encode([...$this->headers, 'json' => $payload]);
        // die();
        $response = $this->client->post($path, [...$this->headers, 'json' => $payload]);

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
        $this->result = json_decode($response->getBody(), true);
        if ($response->getStatusCode() === 200) {
            return $this->result;
        }
    }

    private function makeHandler(): array
    {
        $historyMiddleware = Middleware::history($this->history);
        $stack = HandlerStack::create();
        $stack->push($historyMiddleware);

        return ['handler' => $stack];
    }

    public function lastResult(): array
    {
        return $this->result;
    }

    public function lastRequest()
    {
        $request = end($this->history)['request'] ?? null;
        if ($request) {
            return $request;
        }
    }

    public function hasNextPage()
    {
        return isset($this->result['total'], $this->result['limit'], $this->result['offset']) &&
            ($this->result['total'] > $this->result['limit'] + $this->result['offset']);
    }
}
