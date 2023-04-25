<?php

namespace Nicdev\WebflowSdk\Entities;

use Nicdev\WebflowSdk\Webflow;

 class Site
 {
    private $webflow;

    private $data;

    public function __construct(Webflow $webflow = null, $siteId)
    {
        $this->webflow = $webflow ?? new Webflow('');

        $this->data = $this->webflow->getSite($siteId);
    }

    public function __get($property)
    {
        return match ($property) {
            'domains' => $this->domains(),
            'webhooks' => $this->webhooks(),
            'collections' => $this->collections(),
            default => $this->data[$property] ?? null,
        };
    }

    public function publish()
    {
        $this->webflow->publishSite($this->data['_id']);
        $this->data = [$this->webflow->getSite($this->data['_id']), ...$this->data];

        return $this;
    }

    public function domains()
    {
        $this->data['domains'] = $this->webflow->listDomains($this->data['_id']);

        return $this->data['domains'];
    }

    public function webhooks()
    {
        $this->data['webhooks'] = $this->webflow->listWebhooks($this->data['_id']);

        return $this->data['webhooks'];
    }

    public function collections()
    {
        $this->data['collections'] = $this->webflow->listCollections($this->data['_id']);

        return $this->data['collections'];
    }
 }
