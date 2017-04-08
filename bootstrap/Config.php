<?php
function config($mode){
	if($mode === 'development'){
		return [

		'settings' => [
				'displayErrorDetails' => true,
				'determineRouteBeforeAppMiddleware' => true,

			'db'   => [
				'driver' => 'mysql',
				'host' => '127.0.0.1',
				'database' => 'webapp',
				'username' => 'admin',
				'password' => 'admin',
				'charset' => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix' => '',
				],
			'app' => [
				'url' => 'http://localhost',
				'hash' => [
					'algo' => PASSWORD_BCRYPT,
					'cost' => 10,
				]
			],

			'auth' => [
				'session' => 'a2v_wu_cc',
				'remember' => 'vxc_dsd_dw'
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
				'key' => 'csrf_token'
			]
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
		'displayErrorDetails' => false,
		'determineRouteBeforeAppMiddleware' => true
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