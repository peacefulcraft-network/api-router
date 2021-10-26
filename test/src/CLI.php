<?php

use net\peacefulcraft\apirouter\cli\std\ApiRouterSTD;
use net\peacefulcraft\apirouter\ConsoleApplication;

require __DIR__ . '/../vendor/autoload.php';

$CA = new ConsoleApplication();
$CA->usePlugin(new ApiRouterSTD());

$CA->getTerminal()->runInteractivePrompt();