<?php
function config($mode){
	if($mode === 'development'){
		return [

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
			],
			'app' => [
				'url' => 'http://localhost',
				'hash' => [
					'algo' => PASSWORD_BCRYPT,
					'cost' => 10,
				]
			],

			'auth' => [
				'session' => 'user_id',
				'remember' => 'user_r'
			],

			'mail' => [
				'smtp_auth' => true,
				'smtp_secure' => 'tls',
				'host' => 'smtp.gmail.com',
				'username' => '***REMOVED***',
				'password' => '***REMOVED***',
				'port' => 587,
				'html' => true
			],

			'twig' => [
				'debug' => true
			],

			'csrf' => [
				'session' => 'csrf_token'
			]
		];
	}
	else if($mode === 'production'){
		return [

	'app' => [
		'url' => 'http://',
		'hash' => [
			'algo' => PASSWORD_BCRYPT,
			'cost' => 10,
		],
		'displayErrorDetails' => false
	],

	'db'   => [
		'driver' => 'mysql',
		'host' => '',
		'database' => 'webapp',
		'username' => '',
		'password' => '',
		'charset' => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix' => '',
		],

	'auth' => [
		'session' => 'user_id',
		'remember' => 'user_r'
	],

	'mail' => [
		'smtp_auth' => true,
		'smtp_secure' => 'tls',
		'host' => 'smtp.gmail.com',
		'username' => '',
		'password' => '',
		'port' => 587,
		'html' => true
	],

	'twig' => [
		'debug' => false
	],

	'csrf' => [
		'session' => 'csrf_token'
	]
];
	}
}