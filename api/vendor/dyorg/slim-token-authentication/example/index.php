<?php

require_once 'vendor/autoload.php';

use Slim\App;
use Slim\Middleware\TokenAuthentication;

$config = [
    'settings' => [
        'displayErrorDetails' => true
    ]
];

$app = new App($config);

$authenticator = function($request, TokenAuthentication $tokenAuth){

    /**
     * Try find authorization token via header, parameters, cookie or attribute
     * If token not found, return response with status 401 (unauthorized)
     */
    $token = $tokenAuth->findToken($request);

    /**
     * Call authentication logic class
     */
    $auth = new \app\Auth();

    /**
     * Verify if token is valid
     */
    $auth->getUserByToken($token);
};

$error = function($request, $response, TokenAuthentication $tokenAuth) {
	$output = [];
	$output['error'] = [
			'msg' => $tokenAuth->getResponseMessage(),
			'token' => $tokenAuth->getResponseToken(),
			'status' => 401,
			'error' => true
	];
	return $response->withJson($output, 401);
};

/**
 * Add token authentication middleware
 */
$app->add(new TokenAuthentication([
    'path' =>   '/restrict',
    'authenticator' => $authenticator
]));

/**
 * Public route example
 */
$app->get('/', function($request, $response){
    $output = ['msg' => 'It is a public area'];
    $response->withJson($output, 200, JSON_PRETTY_PRINT);
});

/**
 * Restrict route example
 * Our token is "usertokensecret"
 */
$app->get('/restrict', function($request, $response){
    $output = ['msg' => 'It\'s a restrict area. Token authentication works!'];
    $response->withJson($output, 200, JSON_PRETTY_PRINT);
});

$app->run();