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
use Illuminate\Database\Capsule\Manager;

$app = new App([
	'settings' => [
			'displayErrorDetails' => true,
	'db'   => [
		'driver' => 'mysql',
		'host' => '127.0.0.1',
		'database' => 'webapp',
		'username' => 'admin',
		'password' => 'admin',
		'charset' => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix' => '',
		]
	]
]);

$container = $app->getContainer();

$capsule = new Manager();
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

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

$container['db'] = function($container) use ($capsule){
	return $capsule;
};

$container['HomeController'] = function($container){
	return new HomeController($container);
};

require INC_ROOT.'/app/routes.php';		//routes