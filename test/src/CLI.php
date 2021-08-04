<?php

use net\peacefulcraft\apirouter\Application;

require __DIR__ . '/../vendor/autoload.php';

$Application = new Application([]);

require __DIR__ . '/public/routes.php';

$Application->launchConsole();