<?php

require 'vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->get('/api[/{path:.*}]', function (Request $request, Response $response, $args) {
	$apiResponse = ApiController::handleRequest($request, $response);
	$response->getBody()->write($apiResponse);
	$finalResponse = $response->withHeader('Content-Type', 'application/json');
    return $finalResponse;
});

$app->addErrorMiddleware(false, true, true);
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