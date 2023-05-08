<?php

namespace Nicdev\WebflowSdk\Entities;

use DateTime;
use DateTimeZone;
use Nicdev\WebflowSdk\Webflow;

class Collection
{
    public function __construct(
        private Webflow $webflow,
        protected string $_id,
        readonly DateTime $lastUpdated,
        readonly DateTime $createdOn,
        readonly string $name,
        readonly string $slug,
        readonly string $singularName,
        readonly array $fields = [],
    ) {
    }
}
