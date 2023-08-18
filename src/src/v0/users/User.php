<?php

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key; 
class User
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;

	public static function createFromArray(array $data) {
		$user = new self();
		$user->id = $data['id'];
		$user->firstname = $data['firstname'];
		$user->lastname = $data['lastname'];
		$user->email = $data['email'];
		$user->password = $data['password'];

		return $user;
	}

	public function checkValidToken(string $token) : bool {
		try {
			$decoded = JWT::decode($token, new Key('my_app', 'HS256'));
			$userId = $decoded->data->id;
			return (bool) (new UsersModel())->get((int)$userId);
		} catch (Exception $e) {
			return false;
		}
	}
}