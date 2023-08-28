<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use entity\User;

class ApiController {
	protected Request $request;
	protected Response $response;

	public function __construct(Request $request, Response $response) {
		$this->request = $request;
		$this->response = $response;
	}
	
	public function handleRequest() : Response {
		$pathParts = explode('/', $this->request->getAttribute('path'));
		$version = $pathParts[0];
		$module = ucfirst($pathParts[1]);
		$method = $pathParts[2];
		$params = $this->request->getQueryParams();
		$className = "Api\\$version\\$module";

		$access = $this->getAccess($className, $method);

		if ($access === 'auth') {
			$token = $this->request->getHeader('token');
			$isAuth = (new User)->checkValidToken($token[0]);
			if ($isAuth === false) {
				$this->response->getBody()->write(
					json_encode([
					'data' => [],
					'error' => ['Отказано в доступе']
					])
				);
				return $this->response->withHeader('Content-Type', 'application/json')->withStatus(405);
			}
		}

		return call_user_func_array([new $className($this->request, $this->response), $method], $params);
	}

	private function getAccess(string $classname, string $method) : string {
		$result = '';

		if (!class_exists($classname)) {
			return $result;
		}

		$class = new ReflectionClass($classname);
		if (!$class->hasMethod($method)) {
			return $result;
		}

		$classMethod = $class->getMethod($method);
		foreach (explode("\n", $classMethod->getDocComment()) as $doc) {
			if (preg_match('/@access(.*)$/is', $doc, $match)) {
				preg_match_all('/(\$?\w+)/', $match[1], $match, PREG_SET_ORDER);
				$result = array_shift($match)[0];
			}
		}

		return $result;
	}
}