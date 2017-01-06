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
		'remember_identifier',
		'remember_token'
	];

}