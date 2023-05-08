<?php

namespace Nicdev\WebflowSdk\Entities;

use DateTime;
use Nicdev\WebflowSdk\Webflow;

class Webhook
{
    public function __construct(
        private Webflow $webflow,
        readonly string $_id,
        readonly string $triggerType,
        readonly string $triggerId,
        readonly string $site,
        readonly DateTime $createdOn,
        readonly DateTime|null $lastUsed = null,
        readonly array $filter = [],
    ) {
    }
}
