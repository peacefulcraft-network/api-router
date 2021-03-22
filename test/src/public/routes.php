<?php

use ncsa\phpmcj\router\RequestMethod;

$router = $Application->getRouter();

$router->registerRoute(RequestMethod::GET, '', [], '\ncsa\phpmcj\test\controllers\Index');
$router->registerRoute(RequestMethod::GET, 'echo', [], '\ncsa\phpmcj\test\controllers\request\HTTPGetEcho');
$router->registerRoute(RequestMethod::GET, 'echo/:message', [], '\ncsa\phpmcj\test\controllers\request\HTTPGetEcho');
$router->registerRoute(RequestMethod::DELETE, 'echo', [], '\ncsa\phpmcj\test\controllers\request\HTTPGetEcho');
$router->registerRoute(RequestMethod::POST, 'echo', [], '\ncsa\phpmcj\test\controllers\request\HTTPPostEcho');
$router->registerRoute(RequestMethod::PATCH, 'echo', [],  '\ncsa\phpmcj\test\controllers\request\HTTPPostEcho');
$router->registerRoute(RequestMethod::PUT, 'echo', [], '\ncsa\phpmcj\test\controllers\request\HTTPPostEcho');
$router->registerRoute(RequestMethod::POST, 'file-upload', [], '\ncsa\phpmcj\test\controllers\request\HTTPFileUpload');
$router->registerRoute(RequestMethod::GET, 'file-download/:filename', [], '\ncsa\phpmcj\test\controllers\request\HTTPFileDownload');

$router->registerRoute(RequestMethod::GET, 'never-ware', ['\ncsa\phpmcj\test\middleware\Neverware'], '\ncsa\phpmcj\test\controllers\Index');
$router->registerRoute(RequestMethod::GET, 'always-ware', ['\ncsa\phpmcj\test\middleware\Alwaysware'], '\ncsa\phpmcj\test\controllers\Index');
?>