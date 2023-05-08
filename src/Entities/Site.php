<?php

namespace Nicdev\WebflowSdk\Entities;

use DateTime;
use DateTimeZone;
use Nicdev\WebflowSdk\Webflow;
use Nicdev\WebflowSdk\WebflowCollections;
use Nicdev\WebflowSdk\WebflowWebhooks;

class Site
{
    protected array $domains;

    public function __construct(
        private Webflow $webflow,
        readonly string $_id,
        readonly DateTime $createdOn,
        readonly string $name,
        readonly string $shortName,
        readonly DateTimeZone $timezone,
        readonly string|null $database = null
    ) {
    }

    public function __get($name)
    {
        return match ($name) {
            'domains' => isset($this->domains) ? $this->domains : $this->domains(),
            'webhooks' => isset($this->webhooks) ? $this->webhooks : $this->webhooks(),
            'collections' => isset($this->collections) ? $this->collections : $this->collections(),
            default => throw new \Exception("Property {$name} does not exist on ".$this::class)
        };
    }

    public function publish(): array
    {
        return $this->webflow->post('/sites/'.$this->_id.'/publish');
    }

    public function domains()
    {
        $this->domains = $this->webflow->get('/sites/'.$this->_id.'/domains');

        return $this->domains;
    }

    public function webhooks($webhookId = null)
    {
        $webhooks = $webhookId ? [$this->webflow->getWebhook($this->_id, $webhookId)] : $this->webflow->listWebhooks($this->_id);

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
        }, $webhooks);
    }

    public function collections($collectionId = null)
    {
        $collections = $collectionId ? [$this->webflow->getCollection($collectionId)] : $this->webflow->listCollections($this->_id);
        
        return array_map(function ($collection) {
            return new Collection(
                $this->webflow,
                $collection['_id'],
                new DateTime($collection['lastUpdated']),
                new DateTime($collection['createdOn']),
                $collection['name'],
                $collection['slug'],
                $collection['singularName']
            );
        }, $collections);
    }
}
