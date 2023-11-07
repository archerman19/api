<?php

namespace Api\v0;

use ProductsModel;
use Psr\Http\Message\ResponseInterface as Response;

class Products extends ApiAbstract {

	/**
	 * Добавление нового продукта
	 */
	public function createProduct(string $name, int $cost, string $description) : Response {
		$this->data['id'] = (new ProductsModel())->add([
			'name' => $name,
			'cost' => $cost,
			'description' => $description
		]);

		return $this->response();
	}

	/**
	 * Обновление данных продукта
	 *
	 * @param int $id
	 * @param array<string> $params
	 * @return Response
	 */
	public function updateProduct(int $id, array $params) : Response {
		$this->data['success'] = (new ProductsModel())->update($id, $params);

		return $this->response();
	}

	/**
	 * Получить продукт
	 *
	 * @access(auth)
	 * @return Response
	 */
	public function getProduct(int $id) : Response {
		$this->data = (new ProductsModel())->get($id);

		return $this->response();
	}
}
