<?php

use Nicdev\WebflowSdk\WebflowClient;

class Sites
{
    public function __construct(protected WebflowClient $client)
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
