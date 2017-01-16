<?php

namespace App\Controllers;

use App\Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AuthController extends Controller
{

	public function register(Request $req,Response $res){
		$formData = $req->getParsedBody();

		$username = $formData['username'];
		$email = $formData['email'];
		$password = $formData['password'];
		$confirmPassword = $formData['confirm_password'];

		$validator = $this->validation;
		$validator->validate([
			'email' => [$email,'required|email|uniqueEmail'],
			'username' => [$username,'required|alnumDash|max(20)|uniqueUsername'],
			'password' => [$password,'required|min(8)'],
			'confirm_password' => [$confirmPassword,'required|matches(password)']
			]);

		if($validator->passes()){

			$user = $this->user->create([
				'username' => $formData['username'],
				'email' => $formData['email'],
				'password' => $this->hash->createPassword($formData['password'])
			]);

		$this->flash->addMessage('global','you have been registered');
		return $res->withStatus(302)->withHeader('location',$this->router->pathFor('home'));
		}
		else{
			$this->view->render($res,'register.twig',[
				'errors' =>$validator->errors(),
				'formdata' => $formData
				]);
		}

	}

	public function login(Request $req,Response $res){
		 $formData = $req->getParsedBody();

		 $identifier = $formData['identifier'];
		 $password = $formData['password'];
		 $validator = $this->validation;
		 $validator->validate([
		 	'identifier' => [$identifier,'required'],
		 	'password' => [$password,'required']
 		 	]);
		 if($validator->passes()){
		 	$user = $this->user->where('username',$identifier)
		 					   ->orWhere('email',$identifier)
		 					   ->first();
		 	if($user){
		 		if($this->hash->checkPassword($password,$user->password))
		 			die('pass');
		 		else
		 			die('fail');
		 	}else{
		 		die('fail');
		 	}
		 }else{
		 	$this->view->render($res,'login.twig',[
				'errors' =>$validator->errors(),
				'formdata' => $formData
				]);
		 }

	}
}