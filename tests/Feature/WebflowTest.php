<?php

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Nicdev\WebflowSdk\HttpClient;
use Nicdev\WebflowSdk\Webflow;

// use Mockery;

// use Illuminate\Support\HttpClient;
beforeEach(function () {
    $this->webflow = new Webflow('foo');
    $reflect = new \ReflectionClass($this->webflow);
    $this->pageSizeProperty = $reflect->getProperty('pageSize');
    $this->pageSizeProperty->setAccessible(true);
});

it('sets the pages size', function () { 
    
    expect($this->pageSizeProperty->getValue($this->webflow))->toBe(100);

    $newSize = rand(1, 100);
    $this->webflow->setPageSize($newSize);
    
    expect($this->pageSizeProperty->getValue($this->webflow))->toBe($newSize);
});

it('can\'t set the page size to a negative number', function () {
    $this->webflow->setPageSize(-1);
})->throws(Exception::class);

it('can\'t set the page size to 0', function () {
    $this->webflow->setPageSize(0);
})->throws(Exception::class);

it('can\'t set the page size to over 100', function () {
    $this->webflow->setPageSize(101);
})->throws(Exception::class);

