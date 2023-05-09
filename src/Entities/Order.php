<?php

namespace Nicdev\WebflowSdk\Entities;

use Nicdev\WebflowSdk\Webflow;

class Order
{
    public function __construct(
        private Webflow $webflow,
        readonly string $orderId,
        readonly array $fields
    ) {
    }
}
