<?php

require __DIR__ . '/../vendor/autoload.php';

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Slim\Factory\AppFactory;

// Create a PSR-17 factory
$psr17Factory = new Psr17Factory();

// Create a ServerRequestCreator
$serverRequestCreator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

// Create the Slim App
AppFactory::setResponseFactory($psr17Factory);
$app = AppFactory::create();

// Define routes
$app->get('/products', function ($request, $response, $args) {
    $response->getBody()->write(json_encode(['message' => 'Products endpoint']));
    return $response->withHeader('Content-Type', 'application/json');
});

// Run the app with the created ServerRequest
$request = $serverRequestCreator->fromGlobals();
$app->run($request);
