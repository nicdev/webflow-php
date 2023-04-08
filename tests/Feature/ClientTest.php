<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Nicdev\WebflowSdk\WebflowClient;

// use Mockery;

// use Illuminate\Support\HttpClient;


it('intializes a client with the proper settings', function () {
    $client = new WebflowClient();
    
    expect($client::BASE_URL)->toBe('https://api.webflow.com');
});


it('can make HTTP GET requests to Webflow API', function () {
    // Create a mock handler to simulate HTTP responses
    $container = [];
    $history = Middleware::history($container);
    $mockHandler = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], json_encode(['sites' => []]))
    ]);
    $handlerStack = HandlerStack::create($mockHandler);
    $handlerStack->push($history);

    // Create a Guzzle client with the mock handler
    // $client = new Client(['handler' => $mockHandler]);
    $client = new Client(['handler' => $handlerStack]);

    // Create an instance of the WebflowApiClient using the mocked Guzzle client
    $webflowApiClient = new WebflowClient($client);

    // Make a GET request to the Webflow API
    $data = $webflowApiClient->get('/sites');

    //ray($container);
    expect($container[0]['request']->getMethod())->toBe('GET');
    
    // Assert that the response is an array
    expect($data)->toBeArray();
});

