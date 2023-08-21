<?php

namespace core;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Dotenv\Dotenv;

class App {
	public function start() {
		$this->initEnv();

		$app = AppFactory::create();

		$app->any('/api[/{path:.*}]', function (Request $request, Response $response, $args) {
			$apiResponse = (new \ApiController($request, $response))->handleRequest();
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

		$this->_initMiddleware($app);

		$app->run();
	}

	public function initEnv() {
		Dotenv::createUnsafeImmutable(__DIR__ . '/../../')->load();
	}

	private function _initMiddleware(\Slim\App $app) {
		$displayErrorDetails = $_ENV['DISPLAY_ERROR_DETAILS'];

		$callableResolver = $app->getCallableResolver();
		$responseFactory = $app->getResponseFactory();

		$serverRequestCreator = ServerRequestCreatorFactory::create();
		$request = $serverRequestCreator->createServerRequestFromGlobals();

		$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
		$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
		register_shutdown_function($shutdownHandler);

		$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
		$errorMiddleware->setDefaultErrorHandler($errorHandler);
	}
}