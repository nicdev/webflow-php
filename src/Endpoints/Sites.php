<?php

use Nicdev\WebflowSdk\HttpClient;

class Sites
{
    public function __construct(protected HttpClient $client)
    {
        $this->client = $client;
    }

    public function list(): array
    {
        return $this->client->get('/sites');
    }

    // public function post(): array
    // {
    // }
}
