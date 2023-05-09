<?php

namespace Nicdev\WebflowSdk\Entities;

use DateTime;
use Nicdev\WebflowSdk\Webflow;

class Item
{
    public function __construct(
        private Webflow $webflow,
        readonly string $_id,
        readonly bool $draft,
        readonly bool $archived,
        public array $fields,
    ) {
    }

}
