<?php

namespace config;

use PDO;
use PDOException;

class Database
{
    public $conn;

    public function getConnection()
    {
        $this->conn = null;

		$this->conn = new PDO(
			"mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
			$_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']
		);

        return $this->conn;
    }

}