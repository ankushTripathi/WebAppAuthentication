<?php

namespace App\Controllers;

use App\Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AuthController extends Controller
{
	protected $checkroute = array('login','register','login.post','register.post','activate','forgotpassword','forgotpassword.post');
	protected $authroute = array('logout','profile');

	public function isAuthenticated($request,$response,$next){
		
		if(isset($_COOKIE[$this->settings['auth']['remember']])){
			$cookie_val = $_COOKIE[$this->settings['auth']['remember']];
			$hash_cookie_val = $this->hash->hash($cookie_val);
			$user = $this->user->where('remember_identifier',$cookie_val)->first();
			if(!$user || !$this->hash->hashCheck($user->remember_token,$hash_cookie_val)){
				$this->flash->addMessage('global','problem signing in');
				if($request->getAttribute('route')->getName() === 'home')
					return $this->view->render($res,'home.twig');
				else
					return $response->withHeader('Location',$this->router->pathFor('home'));
			}
			else
				$_SESSION[$this->settings['auth']['session']] = $user->id;

		}
	if(isset($_SESSION[$this->settings['auth']['session']])){
		$this->authUser =  $this->user->where('id',$_SESSION[$this->settings['auth']['session']])->first();
		$this->view->offsetSet('authUser',$this->authUser);
		
		$route = $request->getAttribute('route');
		$routeName = $route->getName();
		if(in_array($routeName, $this->checkroute)){
			$this->flash->addMessage('global','you are already logged in!');
			return $response->withHeader('Location',$this->router->pathFor('home'));
		}
	}
	$response = $next($request, $response);
	return $response;
	}

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

			$activeString = $this->randomlib->generateString(128);

			$user = $this->user->create([
				'username' => $formData['username'],
				'email' => $formData['email'],
				'password' => $this->hash->createPassword($formData['password']),
				'active' => false,
				'active_hash' => $this->hash->hash($activeString)
			]);

			$this->mailer->send([
				'to' => $user->email,
				'subject' =>'Thank you for registering'
				],
				'templates/email/register.twig',
					[
					'user' => $user,
					'activeString' => $activeString,
					'baseUrl' => $this->settings['app']['url']
					]
				);

		$this->flash->addMessage('global','You have been registered!');
		return $res->withStatus(302)->withHeader('location',$this->router->pathFor('home'));
		}
		else{
			$this->view->render($res,'register.twig',[
				'errors' =>$validator->errors(),
				'formdata' => $formData
				]);
		}

	}

	public function activate(Request $req,Response $res){
		
		$args = $req->getQueryParams();
		$email = $args['email'];
		$identifier = $args['identifier'];

		$hashedIdentifer = $this->hash->hash($identifier);
		
		$user = $this->user->where('email',$email)
						   ->where('active',false)->first();
		
		if(!user || !$this->hash->hashCheck($user->active_hash,$hashedIdentifer)){
			$this->flash->addMessage('global','could not activate account. try again');
			return $res->withHeader('Location',$this->router->pathFor('home'));
		}
		else{
			$user->activate();
			$this->flash->addMessage('global','account activated, sign in now!');
			return $res->withHeader('Location',$this->router->pathFor('login'));
		}
	}

	public function login(Request $req,Response $res){

		 $formData = $req->getParsedBody();

		 $identifier = $formData['identifier'];
		 $password = $formData['password'];
		 $remember = (!empty($formData['remember'])? : false);
		 $validator = $this->validation;
		 $validator->validate([
		 	'identifier' => [$identifier,'required|exists'],
		 	'password' => [$password,'required']
 		 	]);
		 if($validator->passes()){
		 	$user = $this->user->where([['username',$identifier],['active',true]])
		 					   ->orWhere([['email',$identifier],['active',true]])
		 					   ->first();

		 		if($user && $this->hash->checkPassword($password,$user->password)){
		 			if($remember){
		 				unset($_COOKIE[$this->settings['auth']['remember']]);
		 				$randomString = $this->randomlib->generateString(128);
		 				$rememberToken = $this->hash->hash($randomString);
		 				setcookie($this->settings['auth']['remember'],$randomString,time()+604800,'/','/',true,true);
		 				$rUser = $this->user->where('id',$user->id)->first();
		 				$rUser->update([
		 					'remember_identifier' => $randomString,
		 					'remember_token' => $rememberToken
		 					]);
		 			}
		 			$_SESSION[$this->settings['auth']['session']] = $user->id;
		 			$this->flash->addMessage('global','you have been logged in');
		 			return $res->withStatus(302)->withHeader('Location',$this->router->pathFor('home'));
		 		}else
		 			$this->view->render($res,'login.twig',[
					'errors' =>'invalid username/email or password!',
					'formdata' => $formData
					]);
		 }else{
		 	$this->view->render($res,'login.twig',[
				'errors' =>$validator->errors(),
				'formdata' => $formData
				]);
		 }

	}

	public function logout(Request $req,Response $res){
		if(isset($_SESSION[$this->settings['auth']['session']])){
			$this->authUser->update([
				'remember_identifier' => null,
				'remember_token' => null
				]);
			unset($_SESSION[$this->settings['auth']['session']]);
			setcookie($this->settings['auth']['remember'],null,time()-100);
			$this->flash->addMessage('global','you have been logged out');
		 	return $res->withStatus(302)->withHeader('Location',$this->router->pathFor('home'));
		}
	}

		public function forgotpassword(Request $req,Response $res){
		$formData = $req->getParsedBody();
		$email = $formData['email'];
		$validator = $this->validation;
		$validator->validate([
			'email' => [$email,'required|email|exists']
			]);
		if($validator->passes()){

		$user = $this->user->where('email',$email)->first();
		if($user){
		
			$recoverString = $this->randomlib->generateString(128); 
			$recoverHash = $this->hash->hash($recoverString);		
			$user->recover($recoverHash);
			$this->mailer->send([
			'to' => $user->email,
			'subject' => 'Recover Password'
			],
			'templates/email/recoverpassword.twig',
			[
				'user' => $user,
				'recoverString' => $recoverString,
				'baseUrl' => $this->settings['app']['url']
			]
		);
		$this->flash->addMessage('global','request accepted! check your mail for further steps');
		return $res->withStatus(302)->withHeader('Location',$this->router->pathFor('home'));
		}
		
		}else{
			$this->view->render($res,'forgotpassword.twig',[
				'errors' =>$validator->errors(),
				'formdata' => $formData
				]);
		}
	}

}