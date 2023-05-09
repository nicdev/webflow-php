<?php

namespace Nicdev\WebflowSdk\Enums;

enum OrderUpdateFields
{
    case comment;
    case shippingProvider;
    case shippingTracking;
    case shippintTrackingURL;

    public static function toArray(): array
    {
       return array_column(self::cases(), 'name');
    }
}
