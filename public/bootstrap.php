<?php
use App\Core\Dispatcher;
use App\Config\Configuration;
use App\Config\Local;
use RedBean_Facade as Redbean;

define(APP_DIR, dirname(__DIR__) . DIRECTORY_SEPARATOR . 'App');
chdir(dirname(__DIR__));

// Composer Autoload.
require_once "vendor/autoload.php";
// Application Autoload.
require_once(APP_DIR . DIRECTORY_SEPARATOR . 'Autoload.php');

App\Autoload::init();

Configuration::init();

Redbean::setup(Configuration::getDsn(), Configuration::getUserName(), Configuration::getPassword());
$dispatcher = new App\Core\Dispatcher;
$dispatcher->dispatch();