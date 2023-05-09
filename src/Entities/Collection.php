<?php

/**
 * This class represents a Collection entity in the Webflow SDK.
 */

namespace Nicdev\WebflowSdk\Entities;

use DateTime;
use Nicdev\WebflowSdk\Webflow;

class Collection
{
    /**
     * Collection constructor.
     *
     * @param  Webflow  $webflow An instance of the Webflow SDK.
     * @param  string  $_id The unique identifier of the collection.
     * @param  DateTime  $lastUpdated The date and time the collection was last updated.
     * @param  DateTime  $createdOn The date and time the collection was created.
     * @param  string  $name The name of the collection.
     * @param  string  $slug The slug of the collection.
     * @param  string  $singularName The singular name of the collection.
     * @param  array  $fields the entire Collection from the raw API response
     */
    public function __construct(
        private Webflow $webflow,
        readonly string $_id,
        readonly DateTime $lastUpdated,
        readonly DateTime $createdOn,
        readonly string $name,
        readonly string $slug,
        readonly string $singularName,
        readonly array $fields = [],
    ) {
    }

    /**
     * Retrieve a single item or a list of items from the collection.
     *
     * @param  string|null  $itemId The unique identifier of the item. If not provided, the method returns all items in the collection.
     * @return Item|array An Item instance if $itemId is provided, otherwise an array of Item instances.
     */
    public function items(string $itemId = null): Item|array
    {
        $items = $itemId ? $this->webflow->getItem($this->_id, $itemId)['items'] : $this->webflow->listItems($this->_id)['items'];

        return array_map(function ($item) {
            return new Item(
                $this->webflow,
                _id: $item['_id'],
                draft: $item['_draft'],
                archived: $item['_archived'],
                fields: [$item]
            );
        }, $items);
    }
}
