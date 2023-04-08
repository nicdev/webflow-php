<?php

namespace Nicdev\WebflowSdk;

use Nicdev\WebflowSdk\Endpoints\Sites;

class Webflow
{
    public function __construct(private $token, private $client = null)
    {
        $this->client = $client ?: new HttpClient($token);
    }

    public function listSites()
    {
        $sites = new Sites($this->client);

        return $sites->list();
    }

    public function fetchSite(string $siteId)
    {
        $sites = new Sites($this->client);

        return $sites->site($siteId);
    }

    public function publishSite(string $siteId)
    {
        $sites = new Sites($this->client);

        return $sites->publish($siteId);
    }

    public function fetchSiteDomains(string $siteId)
    {
        $sites = new Sites($this->client);

        return $sites->domains($siteId);
    }
}
