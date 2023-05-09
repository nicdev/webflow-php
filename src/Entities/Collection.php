<?php

namespace Nicdev\WebflowSdk\Entities;

use DateTime;
use Nicdev\WebflowSdk\Webflow;

class Collection
{
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

    public function items(string $itemId = null): Item|array
    {
        $items = $itemId ? $this->webflow->getItem($this->_id, $itemId)['items'] : $this->webflow->listItems($this->_id)['items'];
        
        return array_map(function ($item) {
            return new Item(
                $this->webflow,
                _id: $item['_id'],
                draft: $item['_draft'],
                archived: $item['_archived'],
                fields: array($item)
            );
        }, $items);
    }
}
