<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Nicdev\WebflowSdk\HttpClient;

// use Mockery;

// use Illuminate\Support\HttpClient;
beforeEach(function () {
    $this->container = [];
    $history = Middleware::history($this->container);
    $this->mockHandler = new MockHandler();
    $handlerStack = HandlerStack::create($this->mockHandler);
    $handlerStack->push($history);

    // Create a Guzzle client with the mock handler
    $client = new Client(['handler' => $handlerStack]);

    // Create an instance of the httpClient using the mocked Guzzle client
    $this->httpClient = new HttpClient(token: 'foo', client: $client);
});

it('intializes a client with the proper settings', function () {
    expect($this->httpClient::BASE_URL)->toBe('https://api.webflow.com');
});

it('can make HTTP GET requests to Webflow API', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->httpClient->get('/');
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('GET');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept'        => ['application/json'],
    ]);
});

it('can make HTTP POST requests to Webflow API', function () {
    $this->mockHandler->append(new Response(200, [], json_encode([])));
    $data = $this->httpClient->post('/');
    expect($data)->toBeArray();
    expect($this->container[0]['request']->getMethod())->toBe('POST');
    expect($this->container[0]['request']->getHeaders())->toMatchArray([
        'Authorization' => ['Bearer foo'],
        'Accept'        => ['application/json'],
    ]);
});
