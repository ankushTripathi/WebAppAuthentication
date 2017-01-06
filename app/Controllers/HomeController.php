<?php

namespace App\Controllers;
use Slim\Views\Twig as View;
use App\Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HomeController extends Controller
{
	public function index(Request $req,Response $res){
		return $this->view->render($res,'home.twig');
	}
	public function flash(Request $req,Response $res){
		$this->flash->addMessage('global','this is test dude');
		return $res->withStatus(302)->withHeader('location',$this->router->pathFor('home'));
	}
}