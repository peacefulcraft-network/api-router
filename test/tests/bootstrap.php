<?php
echo "Performing test setup..." . PHP_EOL;
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../src/config/config.test.php');
require('ControllerTest.php');
?>