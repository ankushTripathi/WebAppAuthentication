<?php

namespace App\Helpers;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
class CSRFMiddleware
{
	protected $container;

	public function __construct($container){
		$this->container = $container;
	}

	public function generate(Request $request,Response $response,$next){
		if(isset($_SESSION[$this->container->settings['csrf']['key']]))
			unset($_SESSION[$this->container->settings['csrf']['key']]);
		$token = $this->container->randomlib->generateString(128);
		$hashValue = $this->container->hash->hash($token);
		$_SESSION[$this->container->settings['csrf']['key']] = $hashValue;
		$this->container->view->offsetSet('csrf_key',$this->container->settings['csrf']['key']);
		$this->container->view->offsetSet('csrf_value',$token);
		$response = $next($request, $response);
		return $response;

	}

	public function check(Request $request,Response $response,$next){
		if(isset($_SESSION[$this->container->settings['csrf']['key']])&&($request->getParsedBody()[$this->container->settings['csrf']['key']])){
			$sessionKey = $_SESSION[$this->container->settings['csrf']['key']];
			$formKey = $request->getParsedBody()[$this->container->settings['csrf']['key']];
			$token = $this->container->hash->hash($formKey);
			if($this->container->hash->hashCheck($sessionKey,$token)){
				unset($_SESSION[$this->container->settings['csrf']['key']]);
		$response = $next($request, $response);
		return $response;
			}
			else{
				$this->container->flash->addMessage('global','problem signing in');
				return $response->withHeader('Location',$this->container->router->pathFor('home'));
			}
		}
		$this->container->flash->addMessage('global','problem signing in');
				return $response->withHeader('Location',$this->container->router->pathFor('home'));
	}
}