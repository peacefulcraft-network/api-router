<?php

use net\peacefulcraft\apirouter\router\RequestMethod;
use net\peacefulcraft\apirouter\test\controllers\Index;
use net\peacefulcraft\apirouter\test\middleware\Alwaysware;
use net\peacefulcraft\apirouter\test\middleware\Neverware;

$router = $Application->getRouter();

$router->registerRoute(RequestMethod::GET, '', [], '\net\peacefulcraft\apirouter\test\controllers\Index');
$router->registerRoute(RequestMethod::GET, '/echo', [], '\net\peacefulcraft\apirouter\test\controllers\request\HTTPGetEcho');
$router->registerRoute(RequestMethod::GET, 'echo/:message', [], '\net\peacefulcraft\apirouter\test\controllers\request\HTTPGetEcho');
$router->registerRoute(RequestMethod::DELETE, 'echo', [], '\net\peacefulcraft\apirouter\test\controllers\request\HTTPGetEcho');
$router->registerRoute(RequestMethod::POST, 'echo', [], '\net\peacefulcraft\apirouter\test\controllers\request\HTTPPostEcho');
$router->registerRoute(RequestMethod::PATCH, 'echo', [],  '\net\peacefulcraft\apirouter\test\controllers\request\HTTPPostEcho');
$router->registerRoute(RequestMethod::PUT, 'echo', [], '\net\peacefulcraft\apirouter\test\controllers\request\HTTPPostEcho');
$router->registerRoute(RequestMethod::POST, 'file-upload', [], '\net\peacefulcraft\apirouter\test\controllers\request\HTTPFileUpload');
$router->registerRoute(RequestMethod::GET, 'file-download/:filename', [], '\net\peacefulcraft\apirouter\test\controllers\request\HTTPFileDownload');

$router->registerRoute(RequestMethod::GET, 'never-ware', ['\net\peacefulcraft\apirouter\test\middleware\Neverware'], '\net\peacefulcraft\apirouter\test\controllers\Index');
$router->registerRoute(RequestMethod::GET, 'always-ware', ['\net\peacefulcraft\apirouter\test\middleware\Alwaysware'], '\net\peacefulcraft\apirouter\test\controllers\Index');

// Test that Router && Application behave the same when given Middleware & Controller objects instead of strings
$IndexController = new Index();
$router->registerRoute(RequestMethod::GET, 'never-ware', [new Neverware()], $IndexController);
$router->registerRoute(RequestMethod::GET, 'always-ware', [new Alwaysware()], $IndexController);
?>