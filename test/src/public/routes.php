<?php

use ncsa\phpmvj\router\RequestMethod;

$router = $Application->getRouter();

$router->registerRoute(RequestMethod::GET, '', '\ncsa\phpmvj\test\controllers\Index');
$router->registerRoute(RequestMethod::GET, 'echo', '\ncsa\phpmvj\test\controllers\request\HTTPGetEcho');
$router->registerRoute(RequestMethod::GET, 'echo/:message', '\ncsa\phpmvj\test\controllers\request\HTTPGetEcho');
$router->registerRoute(RequestMethod::DELETE, 'echo', '\ncsa\phpmvj\test\controllers\request\HTTPGetEcho');
$router->registerRoute(RequestMethod::POST, 'echo', '\ncsa\phpmvj\test\controllers\request\HTTPPostEcho');
$router->registerRoute(RequestMethod::PATCH, 'echo', '\ncsa\phpmvj\test\controllers\request\HTTPPostEcho');
$router->registerRoute(RequestMethod::PUT, 'echo', '\ncsa\phpmvj\test\controllers\request\HTTPPostEcho');
$router->registerRoute(RequestMethod::OPTIONS, 'options', '\ncsa\phpmvj\test\controllers\request\HTTPOptions');
$router->registerRoute(RequestMethod::POST, 'file-upload', '\ncsa\phpmvj\test\controllers\request\HTTPFileUpload');
$router->registerRoute(RequestMethod::GET, 'file-download/:filename', '\ncsa\phpmvj\test\controllers\request\HTTPFileDownload');
?>