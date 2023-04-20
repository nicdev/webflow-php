<?php

namespace Nicdev\WebflowSdk\Enums;

enum WebhookTypes
{
    case form_submission;
    case site_publish;
    case ecomm_new_order;
    case ecomm_order_changed;
    case ecomm_inventory_changed;
    case memberships_user_account_added;
    case memberships_user_account_updated;
    case memberships_user_account_deleted;
    case collection_item_created;
    case collection_item_changed;
    case collection_item_deleted;
    case collection_item_unpublished;

    public static function toArray(): array
    {
       return array_column(self::cases(), 'name');
    }
}
