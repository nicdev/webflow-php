<?php

namespace Nicdev\WebflowSdk;

use Nicdev\WebflowSdk\Entities\Site;
use Nicdev\WebflowSdk\Enums\InventoryQuantityFields;
use Nicdev\WebflowSdk\Enums\OrderUpdateFields;
use Nicdev\WebflowSdk\Enums\WebhookTypes;
use Nicdev\WebflowSdk\WebflowSites;

/**
 * Class Webflow
 *
 * A class for interacting with the Webflow API.
 */
class Webflow extends HttpClient
{
    protected int $pageSize = 100;

    private $site; 

    /**
     * Webflow constructor.
     *
     * @param  string  $token The API token for authentication.
     * @param  mixed  $client An optional HTTP client instance.
     */
    public function __construct(private $token, private $client = null)
    {
        parent::__construct($token, $client);
    }

    public function __get($property): mixed
    {
        return match ($property) {
            'site' => $this->site,
            default => throw new \Exception('Invalid property'),
        };
    }

    /**
     * Set page size for paginated requests.
     */
    public function setPageSize(int $pageSize): Webflow
    {
        if ($pageSize > 100 || $pageSize < 1) {
            throw new \Exception('Page size must be between 1 and 100');
        }

        $this->pageSize = $pageSize;

        return $this;
    }

    /**
     * Get the current user's information.
     *
     * @return array The response from the API.
     */
    public function user(): array
    {
        return $this->get('/user');
    }

    /**
     * Get the authenticated user's authorization information.
     *
     * @return array The response from the API.
     */
    public function authInfo(): array
    {
        return $this->get('/info');
    }

    public function sites(): WebflowSites
    {
        return new WebflowSites($this);
    }

    /**
     * List all sites associated with the authenticated user.
     *
     * @return array The response from the API.
     */
    // public function listSites(): array
    // {
    //     return $this->get('/sites');
    // }

    /**
     * List all domains associated with a specific site by its ID.
     *
     * @param  string  $siteId The ID of the site to list domains for.
     * @return array The response from the API.
     */
    // public function listDomains(string $siteId): array
    // {
    //     return $this->get('/sites/'.$siteId.'/domains');
    // }

    /**
     * List all webhooks associated with a specific site by its ID.
     *
     * @param  string  $siteId The ID of the site to list webhooks for.
     * @return array The response from the API.
     */
    // public function listWebhooks(string $siteId): array
    // {
    //     return $this->get('/sites/'.$siteId.'/webhooks');
    // }

    /**
     * Fetch a specific webhook associated with a specific site by their IDs.
     *
     * @param  string  $siteId The ID of the site that the webhook is associated with.
     * @param  string  $webhookId The ID of the webhook to fetch.
     * @return array The response from the API.
     */
    public function getWebhook(string $siteId, string $webhookId): array
    {
        return $this->get('/sites/'.$siteId.'/webhooks/'.$webhookId);
    }

    /**
     * Create a webhook for a specific site.
     *
     * @param  string  $siteId The ID of the site to create a webhook for.
     * @param  string  $triggerType The type of trigger for the webhook.
     * @param  string  $url The URL for the webhook.
     * @param  array  $filter An optional array of filters for the webhook.
     */
    public function createWebhook(string $siteId, string $triggerType, string $url, array $filter = []): array
    {
        if (! in_array($triggerType, WebhookTypes::toArray())) {
            throw new \Exception('Invalid trigger type provided');
        }

        return $this->post('/sites/'.$siteId.'/webhooks', ['filter' => $filter, 'triggerType' => $triggerType, 'url' => $url]);
    }

    /**
     * Delete a webhook for a specific site.
     *
     * @param  string  $siteId The ID of the site to delete the webhook from.
     * @param  string  $webhookId The ID of the webhook to delete.
     */
    public function deleteWebhook(string $siteId, string $webhookId): array
    {
        return $this->delete('/sites/'.$siteId.'/webhooks/'.$webhookId);
    }

    /**
     * List all collections for a specific site.
     *
     * @param  string  $siteId The ID of the site to list collections for.
     * @return array The response from the API.
     */
    // public function listCollections(string $siteId): array
    // {
    //     return $this->get('/sites/'.$siteId.'/collections');
    // }

    /**
     * Fetch a specific collection by its ID.
     *
     * @param  string  $collectionId The ID of the collection to fetch.
     * @return array The response from the API.
     */
    public function getCollection(string $collectionId): array
    {
        return $this->get('/collections/'.$collectionId);
    }

    /**
     * List items for a specific collection by its ID.
     *
     * @param  string  $collectionId The ID of the collection to list items for.
     * @param  int  $page The page number of items to retrieve.
     * @return array The response from the API.
     */
    public function listItems(string $collectionId, int $page = 1): array
    {
        $offset = ($page - 1) * $this->pageSize;

        return $this->get('/collections/'.$collectionId.'/items', ['limit' => $this->pageSize, 'offset' => $offset]);
    }

    /**
     * Fetch a specific item by its ID.
     *
     * @param  string  $itemId The ID of the item to fetch.
     * @return array The response from the API.
     */
    public function getItem(string $collectionId, string $itemId): array
    {
        return $this->get('/collections/'.$collectionId.'/items/'.$itemId);
    }

    /**
     * Create an item in a specific collection by its ID.
     *
     * @param  string  $collectionId The ID of the collection to create the item in.
     * @param  array  $fields An array of fields to create the item with.
     * @param  bool  $live Whether or not to publish the created item.
     * @return array The response from the API.
     */
    public function createItem(string $collectionId, array $fields, $live = false): array
    {
        $fields['_draft'] = isset($fields['_draft']) ? $fields['_draft'] : false;
        $fields['_archived'] = isset($fields['_archived']) ? $fields['_archived'] : false;
        $url = $live ? '/collections/'.$collectionId.'/items?live=true' : '/collections/'.$collectionId.'/items';

        return $this->post($url, ['fields' => $fields]);
    }

    /**
     * Publish one ore more items by their ID.
     *
     * @param  string  $collectionId The ID of the collection that the item(s) belong to.
     * @param  array  $itemIds An array of item IDs to publish.
     */
    public function publishItems(string $collectionId, array $itemIds): array
    {
        return $this->put('/collections/'.$collectionId.'/items/publish', ['itemIds' => $itemIds]);
    }

    /**
     * Update and item by its ID.
     *
     * @param  string  $collectionId The ID of the collection that the item belongs to.
     * @param  string  $itemId The ID of the item to update.
     * @param  array  $fields An array of fields to update the item with.
     * @param  bool  $live whether or not to create the item should be published.
     */
    public function updateItem(string $collectionId, string $itemId, array $fields, $live = false): array
    {
        $url = $live ? '/collections/'.$collectionId.'/items/'.$itemId.'?live=true' : '/collections/'.$collectionId.'/items/'.$itemId;

        return $this->put($url, ['fields' => $fields]);
    }

    /**
     * Patch an item by its ID.
     *
     * @param  string  $collectionId The ID of the collection that the item belongs to.
     * @param  string  $itemId The ID of the item to update.
     * @param  array  $fields An array of fields to update the item with.
     * @param  bool  $live whether or not to create the item should be published.
     *
     * @note: I don't see a real difference between the update and patch methods
     * but they have been matched to their respective endpoints.
     */
    public function patchItem(string $collectionId, string $itemId, array $fields, $live = false): array
    {
        $fields['_draft'] = isset($fields['_draft']) ? $fields['_draft'] : false;
        $fields['_archived'] = isset($fields['_archived']) ? $fields['_archived'] : false;
        $url = $live ? '/collections/'.$collectionId.'/items/'.$itemId.'?live=true' : '/collections/'.$collectionId.'/items/'.$itemId;

        return $this->patch($url, ['fields' => $fields]);
    }

    /**
     * Delete an item by its ID.
     *
     * @param  string  $collectionId The ID of the collection that the item belongs to.
     * @param  string  $itemId The ID of the item to delete.
     * @param  bool  $live passing the live parameter will unpublish the item while keeping it in the collection
     */
    public function deleteItem(string $collectionId, string $itemId, $live = false): array
    {
        $url = $live ? '/collections/'.$collectionId.'/items/'.$itemId.'?live=true' : '/collections/'.$collectionId.'/items/'.$itemId;

        return $this->delete($url);
    }

    /**
     * List products/SKUs for a specific site by its ID.
     *
     * @param  string  $siteId The ID of the site.
     * @param  int  $page The page number of items to retrieve
     * @return array The response from the API.
     */
    public function listProducts(string $siteId, int $page = 1): array
    {
        $offset = ($page - 1) * $this->pageSize;

        return $this->get('/sites/'.$siteId.'/products', ['limit' => $this->pageSize, 'offset' => $offset]);
    }

    /** Adding a new Product involves creating both a Product Item and a SKU Item,
     * since a Product Item has to have, at minimum, a SKU Item.
     *
     * @param  string  $siteId The ID of the site.
     * @param  array  $product An array of fields to create the product with.
     * @param  array  $sku An array of fields to create the sku with.
     */
    public function createProductAndSku(string $siteId, array $product, array $sku = null): array
    {
        $product['_draft'] = isset($product['_draft']) ? $product['_draft'] : false;
        $product['_archived'] = isset($product['_archived']) ? $product['_archived'] : false;
        if ($sku) {
            $sku['_draft'] = isset($sku['_draft']) ? $sku['_draft'] : false;
            $sku['_archived'] = isset($sku['_archived']) ? $sku['_archived'] : false;
        }

        $payload = $sku ?
            ['product' => $product, 'sku' => $sku] :
            ['product' => $product];

        return $this->post('/sites/'.$siteId.'/products', $payload);
    }

    /**
     * Get a specific product by its ID.
     *
     * @param  string  $siteId The ID of the site.
     * @param  string  $productId The ID of the product to fetch.
     */
    public function getProduct(string $siteId, string $productId): array
    {
        return $this->get('/sites/'.$siteId.'/products/'.$productId);
    }

    /**
     * Update a specific product by its ID.
     *
     * @param  string  $siteId The ID of the site.
     * @param  string  $productId The ID of the product to update.
     * @param  array  $product An array of fields to update the product with.
     */
    public function updateProduct(string $siteId, string $productId, array $fields): array
    {
        return $this->patch('/sites/'.$siteId.'/products/'.$productId, ['fields' => $fields]);
    }

    /**
     * Create a SKU for a product
     *
     * @param  string  $siteId The ID of the site.
     * @param  string  $productId The ID of the product to create the SKU for.
     * @param  array  $sku An array of fields to create the sku with.
     */
    public function createSku(string $siteId, string $productId, array $fields): array
    {
        $fields['_draft'] = isset($fields['_draft']) ? $fields['_draft'] : false;
        $fields['_archived'] = isset($fields['_archived']) ? $fields['_archived'] : false;

        return $this->post('/sites/'.$siteId.'/products/'.$productId.'/skus', ['skus' => ['fields' => $fields]]);
    }

    /**
     * Update a SKU for a product by its ID.
     *
     * @param  string  $siteId The ID of the site.
     * @param  string  $productId The ID of the product to update the SKU for.
     * @param  string  $skuId The ID of the SKU to update.
     * @param  array  $sku An array of fields to update the sku with.
     */
    public function updateSku(string $siteId, string $productId, string $skuId, array $fields): array
    {
        return $this->patch('/sites/'.$siteId.'/products/'.$productId.'/skus/'.$skuId, ['sku' => ['fields' => $fields]]);
    }

    /**
     * Inventory for a specific item by its ID.
     *
     * @param  string  $collectionId The ID of the collection that the item belongs to (likely to be the SKUs collection.)
     * @param  string  $skuId The ID of the sku item to fetch the inventory for.
     */
    public function getInventory(string $collectionId, string $skuId): array
    {
        return $this->get('/collections/'.$collectionId.'/items/'.$skuId.'/inventory');
    }

    /**
     * Update inventory for a specific item by its ID.
     *
     * @param  string  $collectionId The ID of the collection that the item belongs to (likely to be the SKUs collection.)
     * @param  string  $skuId The ID of the sku item to update the inventory for.
     * @param  array  $inventory An array of fields to update the inventory with
     * * inventoryType: infinite/finite
     * * updateQuantity: integer // Adds this quantity to currently store quantity. Can be negative.
     * * quantity: integer // Sets the quantity to this number. Takes precendence over updateQuantity.
     */
    public function updateInventory(string $collectionId, string $skuId, array $fields): array
    {
        array_map(function ($fieldName) {
            if (! in_array($fieldName, InventoryQuantityFields::toArray())) {
                throw new \Exception('Only the fields '.implode(', ', InventoryQuantityFields::toArray()).' are allowed to be updated.');
            }
        }, array_keys($fields));

        return $this->patch('/collections/'.$collectionId.'/items/'.$skuId.'/inventory', ['fields' => $fields]);
    }

    /**
     * Get orders for a specific site by its ID.
     *
     * @param  string  $siteId The ID of the site.
     * @param  int  $page The page number of items to retrieve
     * @return array The response from the API.
     */
    public function listOrders(string $siteId, int $page = 1): array
    {
        $offset = ($page - 1) * $this->pageSize;

        return $this->get('/sites/'.$siteId.'/orders', ['limit' => $this->pageSize, 'offset' => $offset]);
    }

    /**
     * Get a specific order by its ID.
     *
     * @param  string  $siteId The ID of the site.
     * @param  string  $orderId The ID of the order to fetch.
     * @return array The response from the API.
     */
    public function getOrder(string $siteId, string $orderId): array
    {
        return $this->get('/sites/'.$siteId.'/orders/'.$orderId);
    }

    /**
     * Update a specific order by its ID.
     *
     * @param  string  $siteId The ID of the site.
     * @param  string  $orderId The ID of the order to update.
     * @param  array  $fields An array of fields to update the order with.
     */
    public function updateOrder(string $siteId, string $orderId, array $fields): array
    {
        array_map(function ($fieldName) {
            if (! in_array($fieldName, OrderUpdateFields::toArray())) {
                throw new \Exception('Only the fields '.implode(', ', OrderUpdateFields::toArray()).' are allowed to be updated.');
            }
        }, array_keys($fields));

        return $this->patch('/sites/'.$siteId.'/orders/'.$orderId, ['fields' => $fields]);
    }

    /**
     * Fulfill and order by its ID.
     *
     * @param  string  $siteId The ID of the site.
     * @param  string  $orderId The ID of the order to fulfill.
     * @param  bool  $notifyCustomer Whether or not to notify the customer of the fulfillment.
     */
    public function fulfillOrder(string $siteId, string $orderId, bool $notifyCustomer = false): array
    {
        return $this->post('/sites/'.$siteId.'/orders/'.$orderId.'/fulfill', ['sendOrderFulfilledEmail' => $notifyCustomer]);
    }

    /**
     * Unfulfill an order by its ID.
     *
     * @param  string  $siteId The ID of the site.
     * @param  string  $orderId The ID of the order to unfulfill.
     */
    public function unfulfillOrder(string $siteId, string $orderId): array
    {
        return $this->post('/sites/'.$siteId.'/orders/'.$orderId.'/unfulfill');
    }

    /**
     * Refund an order by its ID. Reverses a Stripe charge and refunds an order back to a
     * customer. It will also set the order's status to refunded.
     *
     * @param  string  $siteId The ID of the site.
     * @param  string  $orderId The ID of the order to refund.
     */
    public function refundOrder(string $siteId, string $orderId): array
    {
        return $this->post('/sites/'.$siteId.'/orders/'.$orderId.'/refund');
    }

    /**
     * Get ecommerce settings for a specific site by its ID.
     */
    public function getEcommerceSettings(string $siteId): array
    {
        return $this->get('/sites/'.$siteId.'/ecommerce/settings');
    }
}
