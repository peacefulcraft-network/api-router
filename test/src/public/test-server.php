<?php
require(__DIR__ . '/../../vendor/autoload.php');

use \net\peacefulcraft\apirouter\Application;

require(__DIR__ . '/../config/config.test.php');

$Application = new Application($config);
require(__DIR__ . '/routes.php');
$Application->handleRequest();