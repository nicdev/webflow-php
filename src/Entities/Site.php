<?php

namespace Nicdev\WebflowSdk\Entities;

use DateTime;
use DateTimeZone;
use Nicdev\WebflowSdk\Webflow;

/**
 * Represents a Webflow site entity.
 */
class Site
{
    protected array $domains;

    protected array $webhooks;

    protected array $collections;

    protected array $orders;

    protected array $products;

    /**
     * Site constructor.
     *
     * @param  Webflow  $webflow Webflow instance
     * @param  string  $_id Site ID
     * @param  DateTime  $createdOn Site creation date
     * @param  string  $name Site name
     * @param  string  $shortName Site short name
     * @param  DateTimeZone  $timezone Site timezone
     * @param  string|null  $database Site database, nullable
     */
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

    /**
     * Get site property value by its name.
     *
     * @param  string  $name Property name
     * @return mixed
     *
     * @throws \Exception
     */
    public function __get($name)
    {
        return match ($name) {
            'domains' => isset($this->domains) ? $this->domains : $this->domains(),
            'webhooks' => isset($this->webhooks) ? $this->webhooks : $this->webhooks(),
            'collections' => isset($this->collections) ? $this->collections : $this->collections(),
            'orders' => isset($this->orders) ? $this->orders : $this->orders(),
            'products' => isset($this->products) ? $this->products : $this->products(),
            default => throw new \Exception("Property {$name} does not exist on " . $this::class)
        };
    }

    /**
     * Publishes the site.
     */
    public function publish(): Site
    {
        $this->webflow->publishSite($this->_id);

        return $this;
    }

    /**
     * Retrieves site domains.
     *
     * @return array
     */
    public function domains()
    {
        $this->domains = $this->webflow->listDomains($this->_id);

        return $this->domains;
    }

    /**
     * Retrieves site webhooks.
     *
     * @param  string|null  $webhookId Optional webhook ID
     * @return array
     */
    public function webhooks($webhookId = null)
    {
        $webhooks = $webhookId ? [$this->webflow->getWebhook($this->_id, $webhookId)] : $this->webflow->listWebhooks($this->_id);

        $webhookEntities = array_map(function ($webhook) {
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

        if ($webhookId) {
            return $webhookEntities[0];
        }

        $this->webhooks = $webhookEntities;
        return $this->webhooks;
    }

    /**
     * Retrieves site collections.
     *
     * @param  string|null  $collectionId Optional collection ID
     * @return array
     */
    public function collections($collectionId = null)
    {
        $collections = $collectionId ? [$this->webflow->getCollection($collectionId)] : $this->webflow->listCollections($this->_id);

        $collectionEntities = array_map(function ($collection) {
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

        if ($collectionId) {
            return $collectionEntities[0];
        }

        $this->collections = $collectionEntities;
        return $this->collections;
    }

    /**
     * Retrieves site orders.
     *
     * @param  string|null  $orderId Optional order ID
     * @return array
     */
    public function orders($orderId = null)
    {
        $orders = $orderId ? [$this->webflow->getOrder($this->_id, $orderId)] : $this->webflow->listOrders($this->_id);

        $orderEntities = array_map(function ($order) {
            return new Order(
                $this->webflow,
                $order['orderId'],
                $order
            );
        }, $orders);

        if($orderId) {
            return $orderEntities[0];
        }

        $this->orders = $orderEntities;
        return $this->orders;
    }

    /**
     * Retrieves site products.
     *
     * @param  string|null  $productId Optional product ID
     * @return array
     */
    public function products($productId = null)
    {
        $products = $productId ? [$this->webflow->getProduct($this->_id, $productId)] : $this->webflow->listProducts($this->_id);

        $productEntities = array_map(function ($product) {
            return new Product(
                $this->webflow,
                $product
            );
        }, $products);

        if($productId) {
            return $productEntities[0];
        }
        $this->products = $productEntities;
        return $this->products;
    }
}
