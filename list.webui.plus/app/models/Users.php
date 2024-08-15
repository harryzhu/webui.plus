<?php

use Phalcon\Mvc\Model;

class Users extends BaseModel
{
	public $id;
	public $username;
	public $password;
	public $email;
	public $apikey;
	public $is_admin;
	public $is_member;
	public $enbaled;

	static function LoginCheck($u="",$p=""){
		if(empty($u)||empty($p)){
			return False;
		}
		$user = Users::findFirst('username="'.$u.'" and password="'.$p.'"');
		if(!$user){
			return False;
		}
		if($user->enabled != 1){
			return False;
		}

		return (object)array(
			"id"=>$user->id,
			"username"=>$user->username,
			"email"=>$user->email,
			"apikey"=>$user->apikey,
			"is_admin"=>$user->is_admin,
			"is_member"=>$user->is_member,
		);
	}

}
