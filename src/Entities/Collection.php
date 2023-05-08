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
}
