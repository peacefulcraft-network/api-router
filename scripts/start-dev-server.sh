#! /bin/bash
composer --working-dir=test/ install
composer --working-dir=test/ dump-autoload -o
php -S 127.0.0.1:8081 test/src/public/test-server.php