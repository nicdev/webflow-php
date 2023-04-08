<?php

namespace Nicdev\WebflowSdk\Endpoints;

use Nicdev\WebflowSdk\HttpClient;

class Sites
{
    public function __construct(protected $client)
    {
        $this->client = $client;
    }

    public function list(): array
    {
        return $this->client->get('/sites');
    }

    public function site(string $siteId): array
    {
        return $this->client->get('/sites/' . $siteId);
    }

    public function publish(string $siteId): array
    {
        return $this->client->post('/sites/' . $siteId . '/publish');
    }

    public function domains(string $siteId): array
    {
        return $this->client->get('/sites/' . $siteId . '/domains');
    }
}
