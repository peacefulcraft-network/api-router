<?php
use \ncsa\phpmvj\router\Router;

Router::registerRoute('GET', '', '\ncsa\phpmvj\test\controllers\Index');
Router::registerRoute('GET', 'echo', '\ncsa\phpmvj\test\controllers\request\HTTPGetEcho');
Router::registerRoute('DELETE', 'echo', '\ncsa\phpmvj\test\controllers\request\HTTPGetEcho');
Router::registerRoute('POST', 'echo', '\ncsa\phpmvj\test\controllers\request\HTTPPostEcho');
Router::registerRoute('PATCH', 'echo', '\ncsa\phpmvj\test\controllers\request\HTTPPostEcho');

?>