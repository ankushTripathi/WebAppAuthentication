<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	protected $table = "users";

	protected $fillable = [
		'username',
		'email',
		'password',
		'active',
		'active_hash',
		'recover_hash',
		'remember_identifier',
		'remember_token'
	];
	public function displayName(){
		if($this->first_name && $this->last_name){
			return "{$this->first_name} {$this->last_name}";
		}
		else
			return $this->username;
	}
	public function activate(){
		if(!$this->active){
			$this->update([
				'active' => true,
				'active_hash' => null
				]);
		}
	}
	public function recover(){
		$this->update([
			'active' => true,
			'recover_hash' => null
			]);
	}
}