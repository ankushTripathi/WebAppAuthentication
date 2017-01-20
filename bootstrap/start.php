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
use \App\Controllers\AuthController;
use Illuminate\Database\Capsule\Manager;
use\App\Models\User;
use Slim\Flash\Messages;
use \App\Helpers\Hash;
use \App\Helpers\Validator;
use \App\Helpers\Mailer;
use RandomLib\Factory as RandomLib;

require 'config.php';

$mode = file_get_contents(INC_ROOT.'/mode.php');
$config = config($mode);

$app = new App($config);

$container = $app->getContainer();

$capsule = new Manager();
$capsule->addConnection($container->get('settings')['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['authUser'] = function(){
	return false;
};

$container['flash'] = function($container){
	return new Messages();
};
$container['view'] = function($container){
	$view = new Twig(INC_ROOT.'/views',[
		'cache' => false,
		]);
	$view->addExtension(new TwigExtension(
		$container->router,
		$container->request->getUri()
		)
	);
	$view->getEnvironment()->addGlobal('flash',$container['flash']);
	$view->parserOptions = $container->get('settings')['twig']['debug'];
	return $view;
};

$container['db'] = function($container) use ($capsule){
	return $capsule;
};

$container['user'] = function(){
	return new User;
};

$container['hash'] = function($container) {
	return Hash::getInstance(
		$container->get('settings')['app']['hash']['algo'],
		$container->get('settings')['app']['hash']['cost']
	);
};

$container['validation'] = function($container){
	return Validator::getInstance($container->user);
};

 $container['mailer'] = function($container){
 	$mailer = new PHPMailer;

 	$mailSettings = $container->get('settings')['mail'];

 	$mailer->IsSMTP();
 	$mailer->SMTPDebug = 2;
 	$mailer->Host = $mailSettings['host'];
 	$mailer->SMTPAuth = $mailSettings['smtp_auth'];
 	$mailer->SMTPSecure = $mailSettings['smtp_secure'];
 	$mailer->Port = $mailSettings['port'];
 	$mailer->Username = $mailSettings['username'];
 	$mailer->Password = $mailSettings['password'];
 	$mailer->isHTML($mailSettings['html']);

 	return Mailer::getInstance($mailer,$container->view);
 };

 $container['randomlib'] = function($secureInstance){

 		$factory = new RandomLib;
 		return $factory->getMediumStrengthGenerator();
 };


$container['HomeController'] = function($container){
	return new HomeController($container);
};

$container['AuthController'] = function($container){
	return new AuthController($container);
};

$app->add('AuthController:isAuthenticated');

require 'routes.php';		//routes