<?php

namespace Nicdev\WebflowSdk\Entities;

use DateTime;
use Nicdev\WebflowSdk\Enums\WebhookTypes;
use Nicdev\WebflowSdk\Webflow;

class Webhook {

    public function __construct(private Webflow $webflow,    
    readonly string $_id,
    readonly string $triggerType,
    readonly string $triggerId,
    readonly string $site,
    readonly string $url,
    readonly DateTime $createdOn,
    readonly array|null $filter)
    {
    }

    public function delete(): array
    {
        return $this->webflow->delete('/sites/' .$this->site .'/webhooks/' . $this->_id);
    }

    public function create(string $triggerType, string $url, array $filter = []): array
    {
        if (! in_array($triggerType, WebhookTypes::toArray())) {
            throw new \Exception('Invalid trigger type provided');
        }

        $webhookData = $this->webflow->post('/sites/'.$this->site.'/webhooks', ['filter' => $filter, 'triggerType' => $triggerType, 'url' => $url]);
        $this->_id = $webhookData['_id'];
        $this->triggerType = $webhookData['$triggerType'];
        $this->$triggerId = $webhookData['$triggerId'];
        $this->site = $webhookData['$site'];
        $this->url = $webhookData['$url'];
        $this->createdOn = new DateTime($webhookData['$createdOn']);
        $this->filter = $webhookData['$filter'] ?? null;

        return $this;
    }
}