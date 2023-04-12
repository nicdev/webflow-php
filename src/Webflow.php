<?php

namespace Nicdev\WebflowSdk;

class Webflow extends HttpClient
{
    public function __construct(private $token, private $client = null)
    {
        parent::__construct($token, $client);
    }

    public function listSites()
    {
        return $this->get('/sites');
    }

    public function fetchSite(string $siteId)
    {
        return $this->get('/sites/'.$siteId);
    }

    public function publishSite(string $siteId)
    {
       return $this->post('/sites/'.$siteId.'/publish');
    }

    public function fetchSiteDomains(string $siteId)
    {
        return $this->get('/sites/'.$siteId.'/domains');
    }

    public function listWebhooks(string $siteId)
    {
        return $this->get('/sites/'.$siteId.'/webhooks');
    }

    // @TODO validate trigger types, set up filter stuff
    public function createWebhook(string $siteId, string $triggerType, string $url, array $filter = [])
    {
        $this->client->post('/webhooks/'.$siteId);
    }
}
