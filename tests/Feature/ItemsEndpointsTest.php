<?php

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

it('lists the items for a collection', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->webflowApiClient->listItems('foo');
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('GET');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/collections/foo/items');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
});

it('gets an item', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->webflowApiClient->getItem('foo', 'bar');
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('GET');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/collections/foo/items/bar');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
});

it('creates an item', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $itemFields = [
        'foo' => 'bar',
        'slug' => 'foo-bar',
        'name' => 'Foo Bar',
    ];
    $data = $this->webflowApiClient->createItem('foo', $itemFields);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('POST');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/collections/foo/items');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
    expect(json_decode($this->container[0]['request']->getBody().'', true))->toMatchArray([
        'fields' => [
            'foo' => 'bar',
            'slug' => 'foo-bar',
            'name' => 'Foo Bar',
            '_archived' => false,
            '_draft' => false,
        ],
    ]);
});

it('creates a published item', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $itemFields = [
        'foo' => 'bar',
        'slug' => 'foo-bar',
        'name' => 'Foo Bar',
    ];
    $data = $this->webflowApiClient->createItem('foo', $itemFields, true);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('POST');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/collections/foo/items');
    expect($this->container[0]['request']->getUri()->getQuery())->toBe('live=true');
});

it('publishes items', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->webflowApiClient->publishItems('foo', ['foo', 'bar']);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('PUT');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/collections/foo/items/publish');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
    expect(json_decode($this->container[0]['request']->getBody().'', true))->toMatchArray(
        [
            'itemIds' => ['foo', 'bar'],
        ]
    );
});

it('updates an item', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $itemFields = [
        'foo' => 'bar',
        'slug' => 'foo-bar',
        'name' => 'Foo Bar',
    ];
    $data = $this->webflowApiClient->updateItem('foo', 'bar', $itemFields);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('PUT');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/collections/foo/items/bar');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
    expect(json_decode($this->container[0]['request']->getBody().'', true))->toMatchArray([
        'fields' => [
            'foo' => 'bar',
            'slug' => 'foo-bar',
            'name' => 'Foo Bar',
        ],
    ]);
});

it('publishes an item through the update method', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->webflowApiClient->updateItem('foo', 'bar', [], true);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('PUT');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/collections/foo/items/bar');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
    expect($this->container[0]['request']->getUri()->getQuery())->toBe('live=true');
});

it('patches an item', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $itemFields = [
        'foo' => 'bar',
        'slug' => 'foo-bar',
        'name' => 'Foo Bar',
    ];
    $data = $this->webflowApiClient->patchItem('foo', 'bar', $itemFields);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('PATCH');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/collections/foo/items/bar');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);

    expect(json_decode($this->container[0]['request']->getBody().'', true))->toMatchArray([
        'fields' => [
            'foo' => 'bar',
            'slug' => 'foo-bar',
            'name' => 'Foo Bar',
            '_draft' => false,
            '_archived' => false,
        ],
    ]);
});

it('deletes an item', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->webflowApiClient->deleteItem('foo', 'bar');
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('DELETE');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/collections/foo/items/bar');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
});

it('unpublishes an item', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->webflowApiClient->deleteItem('foo', 'bar', true);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('DELETE');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/collections/foo/items/bar');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
    expect($this->container[0]['request']->getUri()->getQuery())->toBe('live=true');
});
