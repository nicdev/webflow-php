<?php

namespace Nicdev\WebflowSdk;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{
    const BASE_URL = 'https://api.webflow.com';

    /**
     * Create a new HttpClient instance.
     *
     * @param  string  $token The API token to use.
     * @param  \GuzzleHttp\Client|null  $client The Guzzle HTTP client to use.
     * @param  array  $headers An array of headers to include in all requests.
     * @param  array  $result The result of the last API request.
     * @param  array  $history An array of HTTP transactions made by the client.
     */
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
            $this->client = new Client([
                'base_uri' => self::BASE_URL,
                ...self::makeHandler(),
            ]);
        }

        $this->headers = ['headers' => [
            'Authorization' => 'Bearer '.$this->token,
            'Accept' => 'application/json',
        ]];
    }

    /**
     * Send a GET request to the API.
     *
     * @param  string  $path The path of the API endpoint.
     * @param  array  $query An optional array of query parameters.
     * @return array The decoded JSON response from the API.
     */
    public function get($path, array $query = []): array
    {
        $response = $this->client->get($path, [...$this->headers, 'query' => $query]);

        return $this->respond($response);
    }

    /**
     * Send a POST request to the API.
     *
     * @param  string  $path The path of the API endpoint.
     * @param  array  $payload An optional array of data to include in the request body.
     * @return array The decoded JSON response from the API.
     */
    public function post($path, $payload = []): array
    {
        $response = $this->client->post($path, [...$this->headers, 'json' => $payload]);

        return $this->respond($response);
    }

    /**
     * Send a DELETE request to the API.
     *
     * @param  string  $path The path of the API endpoint.
     * @return array The decoded JSON response from the API.
     */
    public function delete($path): array
    {
        $response = $this->client->delete($path, $this->headers);

        return $this->respond($response);
    }

    /**
     * Send a PUT request to the API.
     *
     * @param  string  $path The path of the API endpoint.
     * @return array The decoded JSON response from the API.
     */
    public function put($path, $payload): array
    {
        $response = $this->client->put($path, [...$this->headers, 'json' => $payload]);

        return $this->respond($response);
    }

    /**
     * Send a PATCH request to the API.
     *
     * @param  string  $path The path of the API endpoint.
     * @return array The decoded JSON response from the API.
     */
    public function patch($path, $payload): array
    {
        $response = $this->client->patch($path, [...$this->headers, 'json' => $payload]);

        return $this->respond($response);
    }

    /**
     * Process the response from the API and return the decoded JSON data.
     *
     * @param  \Psr\Http\Message\ResponseInterface  $response The HTTP response from the API.
     * @return array The decoded JSON data from the response body.
     */
    public function respond(ResponseInterface $response): array
    {
        $this->result = json_decode($response->getBody(), true);
        if ($response->getStatusCode() === 200) {
            return $this->result;
        }

        return [];
    }

    /**
     * Create the Guzzle HTTP handler stack with a history middleware.
     *
     * @return array An array with the Guzzle HTTP handler stack.
     */
    private function makeHandler(): array
    {
        $historyMiddleware = Middleware::history($this->history);
        $stack = HandlerStack::create();
        $stack->push($historyMiddleware);

        return ['handler' => $stack];
    }

    /**
     * Get the result of the last API request.
     *
     * @return array The decoded JSON data from the last API response.
     */
    public function lastResult(): array
    {
        return $this->result;
    }

    /**
     * Get the last HTTP request sent by the client.
     *
     * @return \Psr\Http\Message\RequestInterface|null The last HTTP request sent by the client, or null if there is no history.
     */
    public function lastRequest(): ?\Psr\Http\Message\RequestInterface
    {
        $request = end($this->history)['request'] ?? null;
        if ($request) {
            return $request;
        }
    }

    /**
     * Check if there is a next page of results available.
     *
     * @return bool True if there is a next page of results, false otherwise.
     */
    public function hasNextPage(): bool
    {
        return isset($this->result['total'], $this->result['limit'], $this->result['offset']) &&
            ($this->result['total'] > $this->result['limit'] + $this->result['offset']);
    }
}
