<?php

namespace Nicdev\WebflowSdk\Entities;

use DateTime;
use Nicdev\WebflowSdk\Webflow;

class Collection {
    readOnly DateTime $createdOn;
    readOnly DateTime $lastUpdated;
    readOnly string $name;
    readOnly string $slug;
    readOnly string $singularName;
    readOnly string $id;
    readOnly string $_id;

    public function __construct(private array $collectionData, private Webflow $webflow) {
        $this->createdOn = new DateTime($collectionData['createdOn']);
        $this->lastUpdated = new DateTime($collectionData['lastUpdated']);
        $this->name = $collectionData['name'];
        $this->slug = $collectionData['slug'];
        $this->singularName = $collectionData['singularName'];
        $this->id = $collectionData['_id'];
        $this->_id = $collectionData['_id'];
    }

    public function publish() {
        return $this->webflow->publishSite($this->id);
    }

    public function domains() {
        return $this->webflow->listDomains($this->id);
    }

    // @TODO implement collection entity and return collection objects
    public function collections() {
        return $this->webflow->listCollections($this->id);
    }
}