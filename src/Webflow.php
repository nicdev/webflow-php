<?php

namespace Nicdev\WebflowSdk;

use Exception;
use Nicdev\WebflowSdk\Enums\WebhookTypes;

/**
 * Class Webflow
 *
 * A class for interacting with the Webflow API.
 */
class Webflow extends HttpClient
{
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

    /**
     * List all sites associated with the authenticated user.
     *
     * @return array The response from the API.
     */
    public function listSites(): array
    {
        return $this->get('/sites');
    }

    /**
     * Fetch a specific site by its ID.
     *
     * @param  string  $siteId The ID of the site to fetch.
     * @return array The response from the API.
     */
    public function getSite(string $siteId): array
    {
        return $this->get('/sites/'.$siteId);
    }

    /**
     * Publish a specific site by its ID.
     *
     * @param  string  $siteId The ID of the site to publish.
     * @return array The response from the API.
     */
    public function publishSite(string $siteId): array
    {
        return $this->post('/sites/'.$siteId.'/publish');
    }

    /**
     * List all domains associated with a specific site by its ID.
     *
     * @param  string  $siteId The ID of the site to list domains for.
     * @return array The response from the API.
     */
    public function listDomains(string $siteId): array
    {
        return $this->get('/sites/'.$siteId.'/domains');
    }

    /**
     * List all webhooks associated with a specific site by its ID.
     *
     * @param  string  $siteId The ID of the site to list webhooks for.
     * @return array The response from the API.
     */
    public function listWebhooks(string $siteId): array
    {
        return $this->get('/sites/'.$siteId.'/webhooks');
    }

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
            throw new Exception('Invalid trigger type provided');
        }
        $this->post('/sites/'.$siteId.'/webhooks', [...$filter, 'triggerType' => $triggerType, 'url' => $url]);
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
    public function listCollections(string $siteId): array
    {
        return $this->get('/sites/'.$siteId.'/collections');
    }

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
        $offset = ($page - 1) * 100;

        return $this->get('/collections/'.$collectionId.'/items', ['limit' => 100, 'offset' => $offset]);
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
        $fields['_draft'] = isset($fields['_draft']) ? $fields['_draft'] : false;
        $fields['_archived'] = isset($fields['_archived']) ? $fields['_archived'] : false;
        $url = $live ? '/collections/'.$collectionId.'/items/'.$itemId.'?live=true' : '/collections/'.$collectionId.'/items/'.$itemId;

        return $this->put($url, ['fields' => $fields]);
    }

    /**
     * Patch and item by its ID.
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

        return $this->put($url, ['fields' => $fields]);
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
}
