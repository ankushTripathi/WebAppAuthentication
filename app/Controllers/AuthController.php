<?php

namespace App\Controllers;

use App\Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AuthController extends Controller
{

	public function register(Request $req,Response $res){
		$formData = $req->getParsedBody();
		$user = $this->user->create([
			'username' => $formData['username'],
			'email' => $formData['email'],
			'password' => $this->hash->createPassword($formData['password'])
			]);

		$this->flash->addMessage('global','you have been registered');
		return $res->withStatus(302)->withHeader('location',$this->router->pathFor('home'));
	}
}