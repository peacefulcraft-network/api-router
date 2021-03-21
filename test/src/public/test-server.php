<?php
ob_start();
require(__DIR__ . '/../../vendor/autoload.php');

use \ncsa\phpmcj\Application;
require(__DIR__ . '/../config/config.test.php');

$Application = new Application($config);

require(__DIR__ . '/routes.php');
$Application->handle();
ob_flush();