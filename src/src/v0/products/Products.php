<?php

namespace Api\v0;

class Products extends ApiAbstract {

	/**
	 * Получить продукт
	 *
	 * @access(auth)
	 * @return string
	 */
	public function getProduct(int $id, string $name) {
		$this->data['response'] = "It is product with id $id and name $name";
		return $this->toJson();
	}
}
