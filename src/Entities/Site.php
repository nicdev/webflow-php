<?php

namespace Nicdev\WebflowSdk\Entities;

use DateTime;
use DateTimeZone;
use Nicdev\WebflowSdk\Webflow;

class Site
{
    protected array $domains;

    protected array $webhooks;

    protected array $collections;

    public function __construct(
        private Webflow $webflow,
        readonly string $_id,
        readonly DateTime $createdOn,
        readonly string $name,
        readonly string $shortName,
        readonly DateTimeZone $timezone,
        readonly string $database
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

    public function webhooks()
    {
        $webhooks = $this->webflow->get('/sites/'.$this->_id.'/webhooks');
        $this->webhooks = array_map(function ($webhook) {
            return new Webhook(
                $this->webflow,
                $webhook['_id'],
                $webhook['triggerType'],
                $webhook['triggerId'],
                $webhook['site'],
                $webhook['url'],
                new DateTime(
                    $webhook['createdOn']
                ),
                $webhook['filter'],
            );
        }, $webhooks);

        return $this->webhooks;
    }

    public function webhook(string $webhookId): Webhook
    {
        $webhookData = $this->webflow->get('/sites/'.$this->_id.'/webhooks/'.$webhookId);

        return new Webhook(
            $this->webflow,
            $webhookData['_id'],
            $webhookData['triggerType'],
            $webhookData['triggerId'],
            $webhookData['site'],
            $webhookData['url'],
            new DateTime(
                $webhookData['createdOn']
            ),
            $webhookData['filter'],
        );
    }

    public function collections()
    {
        $this->collections = $this->webflow->get('/sites/'.$this->_id.'/collections');

        return $this->collections;
    }
}
