<?php
if (PHP_SAPI == 'cli-server') {
	// To help the built-in PHP dev server, check if the request was actually for
	// something which should probably be served as a static file
	$file = __DIR__ . $_SERVER ['REQUEST_URI'];
	if (is_file ( $file )) {
		return false;
	}
}

session_start ();

// Instantiate the app
$app = require __DIR__ . '/src/bootstrap.php';

// Validate token
use Slim\Middleware\TokenAuthentication;


$authenticator = function ($request, TokenAuthentication $tokenAuth) use ($app) {

	/**
	 * Try find authorization token via header, parameters, cookie or attribute
	 * If token not found, return response with status 401 (unauthorized)
	 */
	$_token = $tokenAuth->findToken ( $request );

	// global $app;

	$container = $app->getContainer ();

	/**
	 * Call authentication logic class
	 */
	$auth = new App\Authentication\PsAuthentication ( $container->get ( 'logger' ), $container );

	/**
	 * Verify if token is valid on database
	 * If token isn't valid, must throw an UnauthorizedExceptionInterface
	 */
	global $user_login;

	$user_login = $auth->getUserColumnByToken ( $_token );

	$app->user_token = $user_login;
};

$error = function ($request, $response, TokenAuthentication $tokenAuth) {
	$output = [ ];
	$output ['error'] = [ 
			'msg' => $tokenAuth->getResponseMessage (),
			'token' => $tokenAuth->getResponseToken (),
			'status' => 401,
			'error' => true
	];

	return $response->withJson ( $output, 401 );
};

/**
 * Add token authentication middleware *
 */
$app->add ( new TokenAuthentication ( [ 
		'path' => '/',
		'passthrough' => [ 
				'/appversion',
				'/ps_user/logout',
				'/ps_user/login',
				'/ps_user/forgot_password',
				'/layout/mobile'
		], // not check token
		'secure' => false,
		'authenticator' => $authenticator,
		// 'error' => $error,
		'header' => 'Authorization',
		'regex' => '/Bearer\s+(.*)$/i',
		'cookie' => null,
		'parameter' => null
] ) );

// Run app
$app->run ();