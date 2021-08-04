<?php
ob_start();
require(__DIR__ . '/../../vendor/autoload.php');

use \net\peacefulcraft\apirouter\Application;
use net\peacefulcraft\apirouter\test\api\DummyPlugin;

require(__DIR__ . '/../config/config.test.php');

$Application = new Application($config);
$Application->usePlugin(new DummyPlugin());
require(__DIR__ . '/routes.php');
$Application->handleRequest();
ob_flush();