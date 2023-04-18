<?php

namespace Nicdev\WebflowSdk\Entities;

use DateTime;
use Nicdev\WebflowSdk\Webflow;

class Site {
    readOnly DateTime $createdOn;
    readOnly DateTime|null $lastPublished;
    readOnly string $name;
    readOnly string|null $previewUrl;
    readOnly string $shortName;
    readOnly string $timezone;
    readOnly string $database;
    readOnly string $id;
    readOnly string $_id;

    public function __construct(private array $siteData, private Webflow $webflow) {
        $this->createdOn = new DateTime($siteData['createdOn']);
        $this->lastPublished = isset($siteData['lastPublished']) ? new DateTime($siteData['lastPublished']) : null;
        $this->name = $siteData['name'];
        $this->previewUrl = isset($siteData['previewUrl']) ? $siteData['previewUrl'] : null;
        $this->shortName = $siteData['shortName'];
        $this->timezone = $siteData['timezone'];
        $this->database = $siteData['database'];
        $this->id = $siteData['_id'];
        $this->_id = $siteData['_id'];
    }

    public function publish() {
        return $this->webflow->publishSite($this->id);
    }

    // @TODO implement or are they not a first class entity?
    public function domains() {
        return $this->webflow->listDomains($this->id);
    }

    public function collections() {
        $response = $this->webflow->listCollections($this->id);
        return array_map(function($collectionData) {
            return new Collection($collectionData, $this->webflow);
        }, $response);
    }

    
}