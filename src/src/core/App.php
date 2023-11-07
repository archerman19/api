<?php

namespace core;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Dotenv\Dotenv;

class App {
	/**
	 * Запуск приложения
	 *
	 * @return void
	 */
	public function start() : void {
		date_default_timezone_set('Europe/Samara');
		$this->initEnv();

		$app = AppFactory::create();

		$app->any('/api[/{path:.*}]', function (Request $request, Response $response) {
			return (new \ApiController($request, $response))->handleRequest();
		});

		$this->initMiddleware($app);

		$app->run();
	}

	/**
	 * Инициализация переменных окружения
	 *
	 * @return void
	 */
	public function initEnv() : void {
		Dotenv::createUnsafeImmutable(__DIR__ . '/../../')->load();
	}

	/**
	 * Инициализация промежуточного ПО
	 *
	 * @param \Slim\App $app
	 * @return void
	 */
	private function initMiddleware(\Slim\App $app) : void {
		$displayErrorDetails = (bool) $_ENV['DISPLAY_ERROR_DETAILS'];

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