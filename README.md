# Webflow PHP SDK

This PHP SDK allows you to interact with the Webflow API easily. The Webflow class provides methods to manage sites, domains, webhooks, and collections.

## BEWARE! This is a super early version in active development. So please be careful if you decide to use it ✌️

## Installation

Install the SDK via Composer:

```sh
composer require nicdev/webflow-sdk
```

## Usage

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
$siteId = 'your-site-id';
$site = $webflow->fetchSite($siteId);
```

### Publish a specific site by its ID

```php
$siteId = 'your-site-id';
$webflow->publishSite($siteId);
```

### List all domains associated with a specific site by its ID

```php
$siteId = 'your-site-id';
$domains = $webflow->listDomains($siteId);
```

### List all webhooks associated with a specific site by its ID

```php
$siteId = 'your-site-id';
$webhooks = $webflow->listWebhooks($siteId);
```

### Fetch a specific webhook associated with a specific site by their IDs

```php
$siteId = 'your-site-id';
$webhookId = 'your-webhook-id';
$webhook = $webflow->getWebhook($siteId, $webhookId);
```

### Create a webhook for a specific site

```php
use Nicdev\WebflowSdk\Enums\WebhookTypes;

$siteId = 'your-site-id';
$triggerType = WebhookTypes::SITE_PUBLISH;
$url = 'https://your-webhook-url.com';
$filter = []; // Optional filter array

$webflow->createWebhook($siteId, $triggerType, $url, $filter);
```

### Delete a webhook for a specific site

```php
$siteId = 'your-site-id';
$webhookId = 'your-webhook-id';

$webflow->deleteWebhook($siteId, $webhookId);
```

### List all collections for a specific site

```php
$siteId = 'your-site-id';
$collections = $webflow->listCollections($siteId);
```

### Fetch a specific collection by its ID

```php
$collectionId = 'your-collection-id';
$collection = $webflow->fetchCollection($collectionId);
```

### List items for a specific collection by its ID

```php
$collectionId = 'your-collection-id';
$page = 1; // Optional page number

$items = $webflow->listItems($collectionId, $page);
```

### Create an item in a specific collection by its ID

```php
$collectionId = 'your-collection-id';
$fields = [
    'field-name' => 'field-value',
    // ...
];
$live = false; // Optional publish

$item = $webflow->createItem($collectionId, $fields, $live);
```

### Get an item by its ID

```php
$collectionId = 'your-collection-id';
$itemId = 'your-item-id';

$item = $webflow->getItem(string $collectionId, string $itemId)
```

### Publish one or more items by their ID

```php
$collectionId = 'your-collection-id';
$itemIds = ['your-item-id', 'your-other-item-id'];
    
$webflow->publishItems($collection, $itemIds);
```

### Update an item by its ID

```php
$collectionId = 'your-collection-id';
$itemId = 'your-item-id';
$fields = $fields = [
    'field-name' => 'field-value',
    // ...
];
$live = false; // Optional publish 

$webflow->updateItem(string $collectionId, string $itemId, array $fields, $live)
```

### Patch an item by its ID
_I don't see a real difference between the update and patch methods but they have been matched to their respective endpoints. For more information see [the documentation](https://developers.webflow.com/reference/update-item)._

```php
$collectionId = 'your-collection-id';
$itemId = 'your-item-id';
$fields = $fields = [
    'field-name' => 'field-value',
    // ...
];
$live = false; // Optional publish 

$webflow->updateItem(string $collectionId, string $itemId, array $fields, $live)
```

### Delete or un-publish an item by its ID

```php
$collectionId = 'your-collection-id';
$itemId = 'your-item-id';
$live = true; // Optional. When set to true the item will be un-published but kept in the collection

$webflow->deleteItem(string $collectionId, string $itemId, $live = false)
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
This SDK is licensed under the MIT License. See LICENSE for more information.