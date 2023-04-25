<?php

namespace Nicdev\WebflowSdk;

use DateTime;
use DateTimeZone;
use Nicdev\WebflowSdk\Entities\Site;

class WebflowSites {
    public function __construct(private Webflow $webflow)
    {
    }

    public function list(): array
    {
        return $this->webflow->get('/sites');
    }

    public function get(string $siteId): Site
    {
        $siteData = $this->webflow->get("/sites/{$siteId}");

        return new Site(
            $this->webflow, 
            $siteData['_id'], 
            new DateTime($siteData['createdOn']),
            $siteData['name'], 
            $siteData['shortName'], 
            new DateTimeZone($siteData['timezone']), 
            $siteData['database']
        );
    }
}