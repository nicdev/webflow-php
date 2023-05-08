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

    protected WebflowWebhooks $webhooks;

    protected WebflowCollections $collections;

    public function __construct(
        private Webflow $webflow,
        readonly string $_id,
        readonly DateTime $createdOn,
        readonly string $name,
        readonly string $shortName,
        readonly DateTimeZone $timezone,
        readonly string|null $database = null
    ) {
        $this->collections = new WebflowCollections($this->webflow, $this->_id);
        $this->webhooks = new WebflowWebhooks($this->webflow, $this->_id);
    }

    public function __get($name)
    {
        return match ($name) {
            'domains' => isset($this->domains) ? $this->domains : $this->domains(),
            'webhooks' => isset($this->webhooks) ? $this->webhooks : $this->webhooks(),
            'collections' => isset($this->collections) ? $this->collections : $this->collections(),
            default => throw new \Exception("Property {$name} does not exist on " . $this::class)
        };
    }

    public function publish(): array
    {
        return $this->webflow->post('/sites/' . $this->_id . '/publish');
    }

    public function domains()
    {
        $this->domains = $this->webflow->get('/sites/' . $this->_id . '/domains');

        return $this->domains;
    }

    public function webhooks($webhookId = null)
    {
        return $webhookId ? [$this->webhooks->get($webhookId)] : $this->webhooks->list();
    }

    public function collections($collectionId = null)
    {
        if ($collectionId) {
            return $this->collections->get($collectionId);
        }
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
        }, $this->collections->list($this->_id));

        return $this->collections;
    }
}
