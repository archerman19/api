<?php

namespace Api\v0;

class Products {
	protected bool $success = true;
	protected array $data;

	protected function toJson() {
		return json_encode(get_object_vars($this));
	}

	public function getProduct(int $id, string $name) {
		$this->data['response'] = "It is product with id $id and name $name";
		return $this->toJson();
	}
}
