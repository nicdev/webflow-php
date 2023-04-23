<?php

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Nicdev\WebflowSdk\Webflow;

beforeEach(function () {
    $this->container = [];
    $history = Middleware::history($this->container);
    $this->mockHandler = new MockHandler();
    $handlerStack = HandlerStack::create($this->mockHandler);
    $handlerStack->push($history);

    // Create a Guzzle client with the mock handler
    $client = new Client(['handler' => $handlerStack]);

    // Create an instance of the WebflowApiClient using the mocked Guzzle client
    $this->webflowApiClient = new Webflow(token: 'foo', client: $client);
});

it('lists the products for a site', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->webflowApiClient->listProducts('foo');
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('GET');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/sites/foo/products');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
});

it('lists the products for a site, second page', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->webflowApiClient->listProducts('foo', 2);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('GET');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/sites/foo/products');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
    expect($this->container[0]['request']->getUri()->getQuery())->toBe('limit=100&offset=100');
});

it('creates a product with a default SKU', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $productFields = [
        'name' => 'Foo Bar',
        'slug' => 'foo-bar',
    ];
    $skuFields = [
        'name' => 'Baz Qux',
        'slug' => 'baz-qux',
    ];
    $data = $this->webflowApiClient->createProductAndSku('foo', $productFields, $skuFields);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('POST');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/sites/foo/products');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);

    expect(json_decode($this->container[0]['request']->getBody()->getContents(), true))->toMatchArray([
        'product' => [
            'name' => 'Foo Bar',
            'slug' => 'foo-bar',
            '_archived' => false,
            '_draft' => false,
        ],
        'sku' => [
            'name' => 'Baz Qux',
            'slug' => 'baz-qux',
            '_archived' => false,
            '_draft' => false,
        ],
    ]);
});

it('gets a product', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->webflowApiClient->getProduct('foo', 'bar');
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('GET');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/sites/foo/products/bar');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
});

it('updates a product', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $productFields = [
        'foo' => 'bar',
        'slug' => 'foo-bar',
        'name' => 'Foo Bar',
        'color' => 'red',
    ];
    $data = $this->webflowApiClient->updateProduct('foo', 'bar', $productFields);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('PATCH');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/sites/foo/products/bar');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
    expect(json_decode($this->container[0]['request']->getBody()->getContents(), true))->toMatchArray([
        'fields' => [
            'foo' => 'bar',
            'slug' => 'foo-bar',
            'name' => 'Foo Bar',
            'color' => 'red',
        ],
    ]);
});

it('creates a SKU for a product', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $skuFields = [
        'foo' => 'bar',
        'slug' => 'foo-bar',
        'name' => 'Foo Bar',
        'size' => 'small',
    ];
    $data = $this->webflowApiClient->createSku('foo', 'bar', $skuFields);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('POST');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/sites/foo/products/bar/skus');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
    expect(json_decode($this->container[0]['request']->getBody()->getContents(), true))->toMatchArray([
        'skus' => [
            'fields' => [
                'foo' => 'bar',
                'slug' => 'foo-bar',
                'name' => 'Foo Bar',
                'size' => 'small',
                '_archived' => false,
                '_draft' => false,
            ],
        ],
    ]);
});

it('updates a sku', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $skuFields = [
        'color' => 'green',
    ];
    $data = $this->webflowApiClient->updateSku('foo', 'bar', 'baz', $skuFields);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('PATCH');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/sites/foo/products/bar/skus/baz');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
    expect(json_decode($this->container[0]['request']->getBody()->getContents(), true))->toMatchArray([
        'sku' => [
            'fields' => [
                'color' => 'green',
            ],
        ],
    ]);
});

it('Gets invetory for a SKU', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->webflowApiClient->getInventory('foo', 'bar');
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('GET');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/collections/foo/items/bar/inventory');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
});

it('Updates inventory for a SKU', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $inventoryFields = [
        'inventoryType' => 'finite',
        'quantity' => 10,
    ];
    $data = $this->webflowApiClient->updateInventory('foo', 'bar', $inventoryFields);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('PATCH');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/collections/foo/items/bar/inventory');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
    expect(json_decode($this->container[0]['request']->getBody()->getContents(), true))->toMatchArray([
        'fields' => [
            'inventoryType' => 'finite',
            'quantity' => 10,
        ],
    ]);
});

it('Can\'t update inventory for a SKU using incorrect fields', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $inventoryFields = [
        'type' => 'finite',
        'quantity' => 10,
    ];
    $data = $this->webflowApiClient->updateInventory('foo', 'bar', $inventoryFields);
})->throws(Exception::class);
