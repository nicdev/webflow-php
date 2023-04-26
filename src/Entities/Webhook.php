<?php

namespace Nicdev\WebflowSdk\Entities;

use DateTime;
use Nicdev\WebflowSdk\Enums\WebhookTypes;
use Nicdev\WebflowSdk\Webflow;

class Webhook
{
    public function __construct(
        private Webflow $webflow,
        protected string $triggerType,
        protected string $site,
        protected string $url,
        protected DateTime|null $createdOn = null,
        protected ?string $_id = null,
        protected ?string $triggerId = null,
        protected ?array $filter = []
    ) {
    }

    public function delete(): array
    {
        return $this->webflow->delete('/sites/'.$this->site.'/webhooks/'.$this->_id);
    }

    public function create(string $triggerType, string $url, array $filter = []): Webhook
    {
        $webhookData = $this->webflow->createWebhook($this->site, $triggerType, $url, $filter);
        
        $this->_id = $webhookData['_id'];
        $this->triggerType = $webhookData['triggerType'];
        $this->triggerId = $webhookData['triggerId'];
        $this->site = $webhookData['site'];
        $this->url = $webhookData['url'];
        $this->createdOn = new DateTime($webhookData['createdOn']);
        $this->filter = $webhookData['filter'] ?? null;

        return $this;
    }

    public function save(): Webhook
    {
        $webhookData = $this->webflow->createWebhook($this->site, $this->triggerType, $this->url, $this->filter);
        
        $this->_id = $webhookData['_id'];
        $this->triggerType = $webhookData['triggerType'];
        $this->triggerId = $webhookData['triggerId'];
        $this->site = $webhookData['site'];
        $this->url = $webhookData['url'];
        $this->createdOn = new DateTime($webhookData['createdOn']);
        $this->filter = $webhookData['filter'] ?? null;

        return $this;
    }
}
