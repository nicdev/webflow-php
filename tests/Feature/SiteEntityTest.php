<?php

use DateTime;
use DateTimeZone;
use Nicdev\WebflowSdk\Entities\Site;
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
        ->method('post')
        ->with('/sites/site_id/publish');

    // Call the publish method and verify the result
    $result = $this->site->publish();
    expect($result)->toBeInstanceOf(Site::class);
});

// Add more test functions for other public methods in the Site class

test('domains', function () {
    // ...
});

test('webhooks', function () {
    // ...
});

test('collections', function () {

});
