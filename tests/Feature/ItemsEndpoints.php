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
    expect($this->container[0]['request']->getUri()->getPath())->toBe('/sites/foo/collections');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept' => ['application/json'],
    ]);
});
