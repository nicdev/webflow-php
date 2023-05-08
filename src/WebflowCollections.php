<?php

namespace Nicdev\WebflowSdk;

use DateTime;
use DateTimeZone;
use Nicdev\WebflowSdk\Entities\Collection;
use Nicdev\WebflowSdk\Entities\Site;

class WebflowCollections
{
    public function __construct(private Webflow $webflow)
    {
    }

    public function list($siteId): array
    {
        return $this->webflow->get('/sites/' . $siteId . '/collections');
    }

    public function get(string $collectionId): Collection
    {
        $collectionData = $this->webflow->get('/collections/' . $collectionId);

        return new Collection(
            $this->webflow, 
            $collectionData['_id'], 
            new DateTime($collectionData['lastUpdated']), 
            new DateTime($collectionData['createdOn']), 
            $collectionData['name'], 
            $collectionData['slug'], 
            $collectionData['singularName']);
    }
}
