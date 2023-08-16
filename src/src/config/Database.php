<?php

namespace config;

use PDO;
use PDOException;

class Database
{
    private $host = "db";
    private $db_name = "main";
    private $username = "root";
    private $password = "adergunov";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;

		$this->conn = new PDO(
			"mysql:host=" . $this->host . ";dbname=" . $this->db_name,
			$this->username, $this->password
		);

        return $this->conn;
    }
}