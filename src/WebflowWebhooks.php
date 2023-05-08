<?php

namespace Nicdev\WebflowSdk;

use DateTime;
use DateTimeZone;
use Nicdev\WebflowSdk\Entities\Site;
use Nicdev\WebflowSdk\Entities\Webhook;

class WebflowWebhooks
{
    public function __construct(private Webflow $webflow)
    {
    }

    public function list($siteId): array
    {
        return array_map(function ($webhook) {
            return new Webhook(
                $this->webflow,
                _id: $webhook['_id'],
                triggerId: $webhook['triggerType'],
                triggerType: $webhook['triggerId'],
                site: $webhook['site'],
                createdOn: new DateTime(
                    $webhook['createdOn']
                ),
                lastUsed: isset($webhook['lastUsedOn']) ? new DateTime(
                    $webhook['lastUsedOn']
                ) : null,
                filter: isset($webhook['filter']) ? $webhook['filter'] : [],
            );
        }, $this->webflow->get('/sites/' . $siteId . '/webhooks'));
    }

    public function get(string $webhookId): Webhook
    {
        $webhookData = $this->webflow->get('/collections/' . $webhookId);

        return new Webhook(
            $this->webflow, 
            $webhookData['_id'], 
            $webhookData['triggerType'],
            $webhookData['triggerId'],
            $webhookData['site'],
            new DateTime($webhookData['createdOn']), 
            new DateTime($webhookData['lastUsed']),
            $webhookData['filter'],
        );
    }
}
