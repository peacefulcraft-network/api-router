<?php
echo "Performing test setup..." . PHP_EOL;
require(__DIR__ . '/../src/config/config.test.php');
require('./vendor/autoload.php');
require('ControllerTest.php');
?>