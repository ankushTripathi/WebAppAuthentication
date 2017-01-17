<?php

namespace App\Auth;

class Auth{

	protected $container;

	public function _construct($container){
		$this->container = $container;
	}

	public function __invoke($request,$response,$next){
	if(isset($_SESSION[$this->container->get('auth')['session']])){
		$this->container->authUser =  $this->container->user
											->where('id',$_SESSION[$container->get('auth')['session']])
											->first();
		$this->container->view->offsetSet('authUser',$this->container->authUser);
	}
	$response = $next($request, $response);
	return $response;
	}
}