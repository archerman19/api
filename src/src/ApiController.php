<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ApiController {
	public static function handleRequest(Request $request, Response $response) {
		$pathParts = explode('/', $request->getAttribute('path'));
		$version = $pathParts[0];
		$module = ucfirst($pathParts[1]);
		$method = $pathParts[2];
		$params = $request->getQueryParams();
		$classname = "Api\\$version\\$module";

		return call_user_func_array([new $classname($request, $response), $method], $params);
	}
}