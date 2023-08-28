<?php

namespace config;

use PDO;

class Database {
	public PDO $conn;

	public function getConnection() : PDO {
		$this->conn = new PDO(
			"mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
			$_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']
		);

		return $this->conn;
	}

}