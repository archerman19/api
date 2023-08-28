<?php

namespace entity;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class User {
    public int $id;
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $password;

	/**
	 *
	 * @param array<mixed> $data
	 * @return self
	 */
	public static function createFromArray(array $data) : self {
		$user = new self();
		$user->id = (int)$data['id'];
		$user->firstname = $data['firstname'];
		$user->lastname = $data['lastname'];
		$user->email = $data['email'];
		$user->password = $data['password'];

		return $user;
	}

	/**
	 * Проверка токена
	 *
	 * @param string $token
	 * @return boolean
	 */
	public function checkValidToken(string $token) : bool {
		try {
			$key = $_ENV['JWT_KEY'];
			$decoded = JWT::decode($token, new Key($key, 'HS256'));
			$userId = $decoded->data->id;
			return (bool) (new \UsersModel())->get((int)$userId);
		} catch (\Exception $e) {
			return false;
		}
	}
}