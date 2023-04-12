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
        $this->post('/webhooks/'.$siteId);
    }

    public function deleteWebhook(string $siteId, string $webhookId)
    {
        $this->delete('/webhooks/'.$siteId.'/'.$webhookId);
    }

    public function listCollections(string $siteId)
    {
        return $this->get('/sites/'.$siteId.'/collections');
    }

    public function fetchCollection(string $collectionId)
    {
        return $this->get('/collections/'.$collectionId);
    }

    public function listItems(string $collectionId, int $page = 0)
    {
        $offset = $page * 100;
        return $this->get('/collections/'.$collectionId.'/items', ['limit' => 100, 'offset' => $offset]);
    }
}
