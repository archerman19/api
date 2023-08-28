<?php

namespace Api\v0;

use UsersModel;
use Firebase\JWT\JWT;
use entity\User;
use Psr\Http\Message\ResponseInterface as Response;

class Users extends ApiAbstract {

	/**
	 * Создать пользователя
	 *
	 * @return Response
	 */
	public function createUser() : Response {
		$data = $this->request->getBody()->getContents();
		$data = json_decode($data, true);

		$isCreated = (new UsersModel())->create($data);
		if ($isCreated) {
			$this->data[] = ['message' => 'Успешная регистрация!'];
		} else {
			$this->error[] = 'Ошибка регистрации';
		}

		return $this->response();
	}

	/**
	 * Вход в систему
	 */
	public function login() : Response {
		$data = $this->request->getBody()->getContents();
		$data = json_decode($data, true);
		$userId = (new UsersModel)->emailExist($data['email']);
		$user = (new UsersModel)->get((int)$userId);
		$user = User::createFromArray($user);

		if ($user != null && password_verify($data['password'], $user->password)) {
			$token = [
				"iss" => "http://localhost",
				"aud" => "http://localhost",
				"iat" => 1356999524,
				"nbf" => 1357000000,
				"data" => [
					"id" => $user->id,
					"email" => $user->email
				]
			];

			$jwt = JWT::encode($token, $_ENV['JWT_KEY'], 'HS256');

			$this->data[] = [
				'message' => "Успешный вход в систему",
				"jwt" => $jwt
			];
		} else {
			$this->error[] = 'Не удалось войти в систему';
		}

		return $this->response();
	}
}