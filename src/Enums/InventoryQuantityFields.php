<?php

namespace Nicdev\WebflowSdk\Enums;

enum InventoryQuantityFields
{
    case quantity;
    case updateQuantity;
    case inventoryType;

    public static function toArray(): array
    {
       return array_column(self::cases(), 'name');
    }
}
