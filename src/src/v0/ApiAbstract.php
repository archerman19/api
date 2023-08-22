<?php
namespace Api\v0;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class ApiAbstract {
	protected array $data = [];
	protected array $error = [];
	protected Request $request;
	protected Response $response;

	public function __construct(Request $request, Response $response) {
		$this->request = $request;
		$this->response = $response;
	}

	protected function response(int $statusCode = 200) {
		$this->response->getBody()->write(
			json_encode([
			'data' => $this->data,
			'error' => $this->error
			])
		);
		return $this->response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
	}
}