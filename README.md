# Webflow PHP SDK

**This is open source software and not in any official way supported by Webflow.**

This PHP SDK allows you to interact with the Webflow API easily. The Webflow class provides methods to manage sites, domains, webhooks, collections, products, etc.

There are two main ways to use this library. Directly as an API wrapper through the `Webflow` class. This will provide you with methods analogous to those provided by the [REST API ](https://developers.webflow.com/reference) (e.g. [List Sites](https://developers.webflow.com/reference/list-sites) is `listSites()`). All methods return arrays and most endpoints are covered.

[See the API client wrapper documentation](#client-api-wrapper)

The second way is through the use of entities. When you request a site (or list of sites) you'll get back `Nicdev\WebflowSdk\Entities\Site` which can be interacted with to get its collections, and other associated entities, as well as publish and interact with the site itself.

[See the Entities documentation](#entities)

## BEWARE! This is a super early version in active development. So please be careful if you decide to use it ✌️

## Installation

Install the SDK via Composer:

```sh
composer require nicdev/webflow-sdk
```

# Usage

# Client (API Wrapper)
To use this SDK, first create a new instance of the `Webflow` class with your API token.

```php
use Nicdev\WebflowSdk\Webflow;

$token = 'your-webflow-api-token';
$webflow = new Webflow($token);
```

### Get the current user's information

```php
$user = $webflow->user();
```

### Get the authenticated user's authorization information

```php
$authInfo = $webflow->authInfo();
```

### List all sites associated with the authenticated user

```php
$sites = $webflow->listSites();
```

### Fetch a specific site by its ID

```php
$site = $webflow->getSite($siteId);
```

### Publish a specific site by its ID

```php
$webflow->publishSite($siteId);
```

### List all domains associated with a specific site by its ID

```php
$domains = $webflow->listDomains($siteId);
```

### List all webhooks associated with a specific site by its ID

```php
$webhooks = $webflow->listWebhooks($siteId);
```

### Fetch a specific webhook associated with a specific site by their IDs

```php
$webhook = $webflow->getWebhook($siteId, $webhookId);
```

### Create a webhook for a specific site

```php
use Nicdev\WebflowSdk\Enums\WebhookTypes;

$triggerType = WebhookTypes::SITE_PUBLISH;
$url = 'https://your-webhook-url.com';
$filter = []; // Optional filter array

$webflow->createWebhook($siteId, $triggerType, $url, $filter);
```

### Delete a webhook for a specific site

```php
$webflow->deleteWebhook($siteId, $webhookId);
```

### List all collections for a specific site

```php
$collections = $webflow->listCollections($siteId);
```

### Fetch a specific collection by its ID

```php
$collection = $webflow->fetchCollection($collectionId);
```

### List items for a specific collection by its ID

```php
$page = 1; // Optional page number

$items = $webflow->listItems($collectionId, $page);
```

### Create an item in a specific collection by its ID

```php
$fields = [
    'field-name' => 'field-value',
    // ...
];
$live = false; // Optional live mode

$item = $webflow->createItem($collectionId, $fields, $live);
```
### Get an item by its ID

```php
$item = $webflow->getItem($collectionId, $itemId)
```

### Publish one or more items by their ID

```php
$itemIds = ['your-item-id', 'your-other-item-id'];
    
$webflow->publishItems($collection, $itemIds);
```

### Update an item by its ID

```php
$fields = $fields = [
    'field-name' => 'field-value',
    // ...
];
$live = false; // Optional publish 

$webflow->updateItem($collectionId, $itemId, $fields, $live)
```

### Patch an item by its ID
_I don't see a real difference between the update and patch methods but they have been matched to their respective endpoints. For more information see [the documentation](https://developers.webflow.com/reference/update-item)._

```php
$fields = $fields = [
    'field-name' => 'field-value',
    // ...
];
$live = false; // Optional publish 

$webflow->updateItem($collectionId, $itemId, $fields, $live)
```

### Delete or un-publish an item by its ID

```php
$live = true; // Optional. When set to true the item will be un-published but kept in the collection

$webflow->deleteItem($collectionId, $itemId, $live)
```

### List products/SKUs for a specific site by its ID.


```php
$page = 1; // Optional page number
$webflow->listProducts($siteId, $page);
```

### Create a Product and SKU

```php
$product = [
    'slug' = 'foo-bar',
    'name' => 'Foo Bar',
];
$sku = [
    'slug' => 'foo-bar-small',
    'name' => 'Foo Bar (S)',
    'price' => [
        'value' => 990,
        'unit' => 'USD'
    ]
]; // Optional
$webflow->createProductAndSku($siteId, $product, $sku)
```

### Get Products and SKUs

```php
$webflow->getProduct($site, $product);
```

### Update a Product

```php
$fields = [
    'name' => 'New Foo Bar',
    '_archived' => true
];

$webflow->updateProduct($siteId, $productId, $fields);
```

### Create a SKU

```php
$sku = [
    'slug' => 'foo-bar-Medium',
    'name' => 'Foo Bar (M)',
    'price' => [
        'value' => 1190,
        'unit' => 'USD'
    ]
];
$webflow->createSku($siteId, $product, $sku);
```

### Update a SKU

```php
$sku = [
    'slug' => 'foo-bar-Medium',
    'name' => 'Foo Bar (M) Discounted!!',
    'price' => [
        'value' => 1290,
        'unit' => 'USD'
    ]
];

$webflow->updateSku($siteId, $productId, $skuId, $sku);
```

### Inventory for a specific item

```php
$collectionId = 'your-collection-id'; //likely to be the SKUs collection.
$webflow->getInventory($collectionId, $skuId)
```

### Update Inventory
```php
$fields = [
    'inventory_type' => 'infinite'
];

$webflow->updateInventory($collectionId, $skuId, $fields);
```

### List orders

```php
$page = 1; // Optional page number

$items = $webflow->listOrders($collectionId, $page);
```

### Get an Order

```php
$webflow->getOrdr($siteId, $orderId)
```

### Update an Order

```php
$fields = [
    'comment' => 'Adding a comment to this order'
];

$webflow->updateOrder$siteId, $orderId, $fields);
```

### Fulfill an Order

```php
$notifyCustomer = true; // Optional, defaults to false

$webflow->fullfillOrder($siteId, $orderId, $notifyCustomer);
```

### Un-fulfill an Order

```php
$webflow->unfulfillOrder($siteId, $orderId);
```

### Refund an Order

```php
$webflow->refundOrder($siteId, $orderId);
```

### Get Ecommerce settings for a Site

```php
$webflow->getEcommerceSettings($siteId);
```

## Entities

To use this SDK, first create a new instance of the `Webflow` class with your API token.

```php
use Nicdev\WebflowSdk\Webflow;

$token = 'your-webflow-api-token';
$webflow = new Webflow($token);
```

### Get sites 

```php
$sites = $webflow->sites; // [Site $site1, Site $site2...]
// or
$sites = $webflow->sites()->list(); // [Site $site1, Site $site2...]
```

### Fetch a specific site by its ID

```php
$site = $webflow->sites($siteId) // Site $site;
// or
$site = $webflow->sites()->get($siteId) // Site $site;
```

# Get site's collections

```php
$site->collections; // [Collection $collection1, Collection $collection2, ...]
// or
$site->collections(); // [Collection $collection1, Collection $collection2, ...]
```

### Fetch a specific collection by its ID

```php
$site->collections($collectionId) // Collection $collection;
```

### Fetch a collection's items

```php
$site->collections($collectionId)->items(); // [Item $item1, Item $item2, ...]
// or
$site->collections($collectionId)->items; // [Item $item1, Item $item2, ...]
```

### Fetch a site's webhooks

```php
$site->webhooks(); // [Webhook $webhook1, Webhook $webhook2, ...]
// or
$site->webhooks; // [Webhook $webhook1, Webhook $webhook2, ...]
```

### Fetch a site's products

```php
$site->products(); // [Product $product1, Product $product2, ...]
// or
$site->products; // [Product $product1, Product $product2, ...]
```

### Fetch a site's orders

```php
$site->webhooks(); // [Order $order1, Order $order2, ...]
// or
$site->webhooks; // [Order $order1, Order $order2, ...]
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
This SDK is licensed under the MIT License. See LICENSE for more information.
