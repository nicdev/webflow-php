<?php

use DateTime;
use DateTimeZone;
use Nicdev\WebflowSdk\Entities\Site;
use Nicdev\WebflowSdk\Entities\Webhook;
use Nicdev\WebflowSdk\Webflow;

beforeEach(function () {
    $this->webflow = $this->getMockBuilder(Webflow::class)
        ->disableOriginalConstructor()
        ->getMock();

    $this->site = new Site(
        webflow: $this->webflow,
        _id: 'site_id',
        createdOn: new DateTime(),
        name: 'Test Site',
        shortName: 'test-site',
        timezone: new DateTimeZone('UTC')
    );
});

it('can publish the site', function () {
    // Prepare the Webflow mock
    $this->webflow->expects($this->once())
        ->method('publishSite')
        ->with('site_id');

    // Call the publish method and verify the result
    $result = $this->site->publish();
    expect($result)->toBeInstanceOf(Site::class);
});

// Add more test functions for other public methods in the Site class

it('can retrieve a collection of domains', function () {
    // Prepare the Webflow mock
    $this->webflow->expects($this->exactly(2))
        ->method('listDomains')
        ->with('site_id');

    // Returns current domains
    $result = $this->site->domains;
    expect($result)->toBeArray();

    // Fetches domains
    $result = $this->site->domains();
    expect($result)->toBeArray();

    // Return already loaded domains
    $result = $this->site->domains;
    expect($result)->toBeArray();
});

test('it gets webhooks for a site', function () {
    // Prepare the Webflow mock
    $this->webflow->expects($this->exactly(2))
        ->method('listWebhooks');
    
    // Returns current domains
    $result = $this->site->webhooks;
    expect($result)->toBeArray();

    // Fetches domains
    $result = $this->site->webhooks();
    expect($result)->toBeArray();

    // Return already loaded domains
    $result = $this->site->webhooks;
    expect($result)->toBeArray();
});

test('collections', function () {
    // Prepare the Webflow mock
    $this->webflow->expects($this->exactly(2))
        ->method('listCollections');
        
    // Returns current domains
    $result = $this->site->collections;
    expect($result)->toBeArray();

    // Fetches domains
    $result = $this->site->collections();
    expect($result)->toBeArray();

    // Return already loaded domains
    $result = $this->site->collections;
    expect($result)->toBeArray();
});
