#!/bin/bash
# Get directory of script
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )";
PHPUNIT="$DIR/../vendor/bin/phpunit";
TESTS_DIR="$DIR/../tests/units";
BOOTSTRAP_FILE="$DIR/../tests/bootstrap.php";
TEST_COMMAND="$PHPUNIT $TESTS_DIR --bootstrap $BOOTSTRAP_FILE --testdox";
echo $TEST_COMMAND;
$TEST_COMMAND;