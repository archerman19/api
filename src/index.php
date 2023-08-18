<?php

require 'vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

// Настройка отображения подробностей ошибок
$displayErrorDetails = true;

$app = AppFactory::create();

$app->any('/api[/{path:.*}]', function (Request $request, Response $response, $args) {
	$apiResponse = (new ApiController($request, $response))->handleRequest();
	if (isset($apiResponse['status']) && $apiResponse['status'] === 405) {
		$response->getBody()->write($apiResponse['data']);
		return $response
			->withStatus(405)
			->withHeader('Content-Type', 'application/json')
		;
	}
	$response->getBody()->write($apiResponse);
	return $response->withHeader('Content-Type', 'application/json');
});

$callableResolver = $app->getCallableResolver();
$responseFactory = $app->getResponseFactory();

$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

// Добавление промежуточного ПО обработки ошибок
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

$app->run();

function debug($variable, $trace = false, $die = true) {
	if (php_sapi_name() == 'cli') {
		print_r($variable);
	} else {
		@header('Content-Type: text/html; charset=UTF-8');
		echo "<html><body><pre>";
		print_r($variable);
		if ($trace) {
			echo '<hr/>';
			debug_print_backtrace();
		}
		echo "</pre></body></html>";
	}
	if ($die) {
		die();
	}
}