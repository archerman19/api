<?php
namespace Api\v0;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class ApiAbstract {
	protected int $statusCode = 200;
	protected array $data = [];
	protected array $error = [];
	protected Request $request;
	protected Response $response;

	public function __construct(Request $request, Response $response) {
		$this->request = $request;
		$this->response = $response;
	}

	protected function toJson() {
		return json_encode([
			'statusCode' => $this->statusCode,
			'data' => $this->data,
			'error' => $this->error
		]);
	}
}