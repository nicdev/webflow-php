<?php

namespace Nicdev\WebflowSdk\Entities;

use Nicdev\WebflowSdk\Webflow;

class Product
{
    public function __construct(
        private Webflow $webflow,
        readonly array $product
    ) {
    }
}
