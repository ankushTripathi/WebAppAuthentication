<?php

session_cache_limiter(false);
session_start();

ini_set('display_errors','On'); //development level
define('INC_ROOT',dirname(__DIR__)); //root directory

require INC_ROOT.'/vendor/autoload.php'; //autoload dependencies
//namespaces
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use \App\Controllers\HomeController;

$app = new App([
	'settings' => [
			'displayErrorDetails' => true,
		]
	]);

$container = $app->getContainer();

$container['view'] = function($container){
	$view = new Twig(INC_ROOT.'/resources/views',[
		'cache' => false,
		]);
	$view->addExtension(new TwigExtension(
		$container->router,
		$container->request->getUri()
		)
	);

	return $view;
};

$container['HomeController'] = function($container){
	return new HomeController($container);
};

require INC_ROOT.'/app/routes.php';		//routes