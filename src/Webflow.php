<?php

namespace Nicdev\WebflowSdk;

use Exception;
use Nicdev\WebflowSdk\Entities\Site;
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
     * List all sites.
     *
     * @return mixed The response from the API.
     */
    public function listSites()
    {
        return $this->get('/sites');
    }

    /**
     * Fetch a specific site.
     *
     * @param  string  $siteId The ID of the site to fetch.
     * @return mixed The response from the API.
     */
    public function fetchSite(string $siteId)
    {
        //return $this->get('/sites/'.$siteId);
        $siteData = $this->get('/sites/'.$siteId);
        return new Site($siteData, $this);
    }

    /**
     * Publish a specific site.
     *
     * @param  string  $siteId The ID of the site to publish.
     * @return mixed The response from the API.
     */
    public function publishSite(string $siteId)
    {
       return $this->post('/sites/'.$siteId.'/publish');
    }

    /**
     * Fetch the domains of a specific site.
     *
     * @param  string  $siteId The ID of the site to fetch domains from.
     * @return mixed The response from the API.
     */
    public function listDomains(string $siteId)
    {
        return $this->get('/sites/'.$siteId.'/domains');
    }

    /**
     * List all webhooks for a specific site.
     *
     * @param  string  $siteId The ID of the site to list webhooks for.
     * @return mixed The response from the API.
     */
    public function listWebhooks(string $siteId)
    {
        return $this->get('/sites/'.$siteId.'/webhooks');
    }

    /**
     * Create a webhook for a specific site.
     *
     * @param  string  $siteId The ID of the site to create a webhook for.
     * @param  string  $triggerType The type of trigger for the webhook.
     * @param  string  $url The URL for the webhook.
     * @param  array  $filter An optional array of filters for the webhook.
     *
     * @TODO Validate trigger types, set up filter stuff.
     */
    public function createWebhook(string $siteId, string $triggerType, string $url, array $filter = [])
    {
        if (!in_array($triggerType, WebhookTypes::toArray())) {
            throw new Exception("Invalid trigger type provided");
        }
        $this->post('/sites/'.$siteId.'/webhooks', [...$filter, 'triggerType' => $triggerType, 'url' => $url]);
    }

    /**
     * Delete a webhook for a specific site.
     *
     * @param  string  $siteId The ID of the site to delete the webhook from.
     * @param  string  $webhookId The ID of the webhook to delete.
     */
    public function deleteWebhook(string $siteId, string $webhookId)
    {
        $this->delete('/sites/'.$siteId.'/webhooks/'.$webhookId);
    }

    /**
     * List all collections for a specific site.
     *
     * @param  string  $siteId The ID of the site to list collections for.
     * @return mixed The response from the API.
     */
    public function listCollections(string $siteId)
    {
        return $this->get('/sites/'.$siteId.'/collections');
    }

    public function fetchCollection(string $collectionId)
    {
        return $this->get('/collections/'.$collectionId);
    }

    public function listItems(string $collectionId, int $page = 1)
    {
        $offset = ($page - 1) * 100;

        return $this->get('/collections/'.$collectionId.'/items', ['limit' => 100, 'offset' => $offset]);
    }

    /**
     * Create an item in a specific collection.
     * @param  string  $collectionId The ID of the collection to create the item in.
     * @param  array  $fields An array of fields to create the item with.
     */
    public function createItem(string $collectionId, array $fields, $live = false)
    {
        $fields['_draft'] = isset($fields['_draft']) ? $fields['_draft'] : false;
        $fields['_archived'] = isset($fields['_archived']) ? $fields['_archived'] : false;
        $url = $live ? '/collections/'.$collectionId.'/items?live=true' : '/collections/'.$collectionId.'/items';
        return $this->post($url, ['fields' => $fields]);
    }
}
