<?php

session_cache_limiter(false);
session_start();

ini_set('display_errors','On'); //development level
define('INC_ROOT',dirname(__DIR__)); //root directory

require INC_ROOT.'/vendor/autoload.php';
//namespaces
use Slim\App;

$app = new App();
$app->get('/',function(){
	echo "Hey! ,test route here";
});

$app->get('/name/{name}',function($req,$res,$args){
	return $res->write('hello '.$args['name'].'!');
});