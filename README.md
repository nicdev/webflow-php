# Webflow SDK
This is a PHP SDK for interacting with the Webflow API. It is built on top of the Guzzle HTTP client and provides several convenience methods for common Webflow API requests.

## BEWARE! This is a super early version in active development. There isn't even a tag on this repo. So please be careful if you decide to use it ✌️

## Installation
You can install the SDK via Composer:

```sh
composer require nicdev/webflow-sdk
```

## Usage
To use the SDK, simply create a new Webflow instance with your Webflow API token:

```php
$webflow = new Nicdev\WebflowSdk\Webflow('your_api_token');
You can then call any of the available methods, passing in any required parameters:

// List all sites
$sites = $webflow->listSites();

// Fetch a site by ID
$site = $webflow->fetchSite('site_id');

// Publish a site
$webflow->publishSite('site_id');

// Fetch a site's domains
$domains = $webflow->listDomains('site_id');

// List all webhooks for a site
$webhooks = $webflow->listWebhooks('site_id');

// Create a webhook
$webflow->createWebhook('site_id', 'trigger_type', 'url', $filter);

// Delete a webhook
$webflow->deleteWebhook('site_id', 'webhook_id');

// List all collections for a site
$collections = $webflow->listCollections('site_id');

// Fetch a collection by ID
$collection = $webflow->fetchCollection('collection_id');

// List all items for a collection
$items = $webflow->listItems('collection_id', $page);
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
This SDK is licensed under the MIT License. See LICENSE for more information.