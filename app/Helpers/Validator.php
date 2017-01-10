<?php

namespace App\Helpers;

use Violin\Violin;
use App\Models\User;

class Validator extends Violin
{

	protected $user;
	protected static $instance = null;

	private function __construct(User $user){
		$this->user = $user;
		$this->addFieldMessages([
			'email' => [
					'uniqueEmail' => 'that email is already in use.'
					],
			'username' => [
					'uniqueUsername' => 'that username is taken.'
				]
			]);
	}

	public static function getInstance($user){
		if(!self::$instance){
			self::$instance = new Validator($user);
		}
		return self::$instance;
	} 

	public function validate_uniqueEmail($value,$input,$args){
		$user = $this->user->where('email',$value);
		return !(bool) $user->count();
	}
	public function validate_uniqueUsername($value,$input,$args){
		return !(bool) $this->user->where('username',$value)->count();
	}
}