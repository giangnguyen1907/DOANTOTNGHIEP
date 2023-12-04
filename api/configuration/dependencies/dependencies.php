<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($container) {
    $settings = $container->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// Register component on container

$container['view'] = function ($container) {
	$settings = $container->get('settings');
	$view = new \Slim\Views\Twig($settings['renderer']['template_path'], [
			'cache' => $settings['renderer']['template_cache']
	]);
	$view->addExtension(new \Slim\Views\TwigExtension(
			$container->get('router'),
			$container->get('request')->getUri()
			));

	return $view;
};

// monolog
$container['logger'] = function ($container) {
    $settings = $container->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// monolog
$container['logger_db'] = function ($container) {
    $settings = $container->get('settings')['logger_db'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Config Mailer
$container['mailer'] = function ($container) {
	
	$setting 		= $container->get('settings')['mailer'];

	$setting_mailer = (object)$setting;

	return new \App\PsMail\PsMailer($container->view, $setting_mailer);
	//return new \App\PsMail\PsMailer($container->renderer, $setting_mailer);
};

$container['db'] = function ($container) {
	$capsule = new \Illuminate\Database\Capsule\Manager;
	$capsule->addConnection($container['settings']['db']);

	$capsule->setAsGlobal();
	
	$capsule->connection()->enableQueryLog();// enable log
	
	//$log_db = $capsule->getConnection()->getQueryLog();
	
	//$container['logger'] ->info('SQL: '.$log_db['query']);

	$capsule->bootEloquent();

	return $capsule;
};

//$getQueryLog = $container['db']->connection()->getQueryLog();
//$container['logger_db']->info ('BINDINGS: ');

$container['default_setting_app'] = function ($container) {
	return $container->get('settings')['default_setting_app'];
};

$container['apps_id'] = $apps_id;

