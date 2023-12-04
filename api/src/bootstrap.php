<?php
// Set the project's base
if (!defined('APP_ROOT')) {
    $spl = new SplFileInfo(__DIR__ . '/..');
    define('APP_ROOT', $spl->getRealPath());
}

$loader = require_once APP_ROOT.'/vendor/autoload.php';

date_default_timezone_set("Asia/Ho_Chi_Minh");
ini_set('date.timezone', 'Asia/Ho_Chi_Minh');

$loader->setPsr4('App\\', APP_ROOT.'/src/App');
$loader->setPsr4('Api\\', APP_ROOT.'/src/Api');

// Load Environment file
$dotEnv = new \Dotenv\Dotenv(APP_ROOT.'/configuration/environments','environment.env');
$dotEnv->load();

$settings = require APP_ROOT.'/configuration/settings.php';

return new App\Application($settings);