<?php

namespace core;

use config\Database;
use PDO;

class BaseModel {

	protected PDO $db;

	private static string $tableName = '';

	public function __construct() {
		$database = new Database();
		$this->db = $database->getConnection();
	}

	/**
	 * Получить данные сущности
	 *
	 * @param int $id
	 * @return mixed
	 */
	public function get(int $id) : mixed {
		$stmt = $this->db->prepare("SELECT * FROM $this->tableName WHERE id = ?");
		$stmt->execute([$id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Добавление сущности
	 *
	 * @param array<string> $params
	 * @return integer
	 */
	public function add(array $params) : int {
		$columns = [];
		$values = [];
		foreach ($params as $column => $value) {
			$columns[] = $column;
			$values[] = $value;
		}

		$columns[] = 'created';
		$columns[] = 'modified';
		$values[] = date('Y-m-d H:m:s');
		$values[] = date('Y-m-d H:m:s');

		foreach ($values as $key => $value) {
			if (is_string($value)) {
				$values[$key] = "'$value'";
			}
		}
		$values = implode(', ', $values);

		$columns = implode(', ', $columns);

		$query = "INSERT INTO $this->tableName ($columns) VALUES ($values)";
		$this->db->query($query, PDO::PARAM_INT);

		return $this->db->lastInsertId('id');
	}

	/**
	 * Обновление сущности
	 *
	 * @param int $id
	 * @param array<string> $params
	 * @return bool
	 */
	public function update(int $id, array $params) : bool {
		$expression = '';
		foreach ($params as $column => $value) {
			$value = is_string($value) ? "'$value'" : $value;
			$expression = $expression . $column . '=' . $value . ', ';
		}
		$date = date('Y-m-d H:m:s', time());
		$expression = $expression . "modified='$date'";
		$query = "UPDATE $this->tableName SET $expression WHERE id = $id";
		$stmt = $this->db->query($query, PDO::PARAM_INT);

		return (bool) $stmt->rowCount();
	}

	/**
	 * Удалить сущность
	 *
	 * @param int $id
	 * @return bool
	 */
	public function delete(int $id) : bool {
		$query = "DELETE FROM $this->tableName WHERE id = $id";
		$stmt = $this->db->query($query, PDO::PARAM_INT);

		return (bool) $stmt->rowCount();
	}
}