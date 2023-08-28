<?php

namespace Api\v0;

use Psr\Http\Message\ResponseInterface as Response;

class Products extends ApiAbstract {

	/**
	 * Получить продукт
	 *
	 * @access(auth)
	 * @return Response
	 */
	public function getProduct(int $id, string $name) : Response{
		$this->data['info'] = "It is product with id $id and name $name";
		return $this->response();
	}
}
