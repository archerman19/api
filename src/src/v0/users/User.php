<?php

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
}