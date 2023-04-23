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

it('lists the orders for a site', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->webflowApiClient->listOrders('foo');
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('GET');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/sites/foo/orders');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
});

it('lists the orders for a site, second page', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->webflowApiClient->listOrders('foo', 2);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('GET');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/sites/foo/orders');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
    expect($this->container[0]['request']->getUri()->getQuery())->toBe('limit=100&offset=100');
});

it('gets an order', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->webflowApiClient->getOrder('foo', 'bar');
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('GET');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/sites/foo/orders/bar');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
});

it('updates an order', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $orderFields = [
        'comment' => 'Foo Bar',
    ];
    $data = $this->webflowApiClient->updateOrder('foo', 'bar', $orderFields);
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('PATCH');
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/sites/foo/orders/bar');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
    ray(json_decode($this->container[0]['request']->getBody()->getContents(), true));
    // expect($this->container[0]['request']->getBody()->getContents())->toBe(json_encode($orderFields));
});
