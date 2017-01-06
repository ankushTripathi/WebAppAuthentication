<?php

namespace App\Helpers;

class Hash
{

	private static $instance = null;
	protected $cost;
	protected $algo;

	private function __construct($algo,$cost){
		$this->cost = $cost;
		$this->algo = $algo;
	}

	public static function getInstance($algo,$cost){
		if(self::$instance == null){
			self::$instance = new Hash($algo,$cost);
		}
		return self::$instance;
	}

	public function createPassword($password){
		return password_hash($password,$this->algo,['cost' => $this->cost]);
	}

	public function checkPassword($password,$hash){
		return password_verify($password,$hash);
	}

}