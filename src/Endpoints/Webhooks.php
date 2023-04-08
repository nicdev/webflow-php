<?php

namespace Nicdev\WebflowSdk\Endpoints;

use Nicdev\WebflowSdk\HttpClient;

class Webhooks
{
    public function __construct(protected $client)
    {
        $this->client = $client;
    }

    public function list(string $siteId): array
    {
        return $this->client->get('/sites/' . $siteId . '/webhooks');
    }

    // @TODO validate trigger types, set up filter stuff
    public function create(string $siteId, string $triggerType, string $url, array $filter = []): array
    {
        return $this->client->post('/webhooks/' . $siteId);
    }

}
