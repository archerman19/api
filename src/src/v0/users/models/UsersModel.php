<?php

use config\Database;

class UsersModel {
	protected PDO $db;
	private string $tableName = 'users';

	public function __construct() {
		$database = new Database();
		$this->db = $database->getConnection();
	}

	/**
	 *
	 * @param array<string> $data
	 * @return bool
	 */
	public function create(array $data) : bool {
		if (
			isset($data['firstname']) &&
			isset($data['lastname']) &&
			isset($data['email']) &&
			isset($data['password']) &&
			!$this->emailExist($data['email'])
		) {
			// Запрос для добавления нового пользователя в БД
			$query = "INSERT INTO " . $this->tableName . "
			SET
				firstname = :firstname,
				lastname = :lastname,
				email = :email,
				password = :password";
   
		   // Подготовка запроса
		   $stmt = $this->db->prepare($query);
   
		   // Привязываем значения
		   $stmt->bindParam(":firstname", $data['firstname']);
		   $stmt->bindParam(":lastname", $data['lastname']);
		   $stmt->bindParam(":email",$data['email']);
   
		   // Для защиты пароля
		   // Хешируем пароль перед сохранением в базу данных
		   $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
		   $stmt->bindParam(":password", $password_hash);
   
		   // Выполняем запрос
		   // Если выполнение успешно, то информация о пользователе будет сохранена в базе данных
		   if ($stmt->execute()) {
			   return true;
		   }
		}

		return false;
	}

	/**
	 * Проверить существование почты
	 */
	public function emailExist(string $mail) : int {
		$stmt = $this->db->prepare('SELECT id FROM users WHERE email = ?');
		$stmt->execute([$mail]);
		$id = $stmt->fetchColumn();
		return (int)$id;
	}

	/**
	 * Получить данные пользователя
	 *
	 * @param int $id
	 * @return mixed
	 */
	public function get(int $id) : mixed {
		$stmt = $this->db->prepare('SELECT id, firstname, lastname, password, email FROM users WHERE id = ?');
		$stmt->execute([$id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
}