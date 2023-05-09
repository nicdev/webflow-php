<?php

namespace Nicdev\WebflowSdk\Entities;

use DateTime;
use DateTimeZone;
use Exception;
use Nicdev\WebflowSdk\Webflow;

class Product
{
    public function __construct(
        private Webflow $webflow,
        readonly array $product
    ) {
    }
}
