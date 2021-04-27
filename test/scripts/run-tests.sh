#!/bin/bash
# Get directory of script
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )";
PHPUNIT="/bin/php $DIR/test/vendor/bin/phpunit";
TESTS_DIR="$DIR/test/tests/units";
BOOTSTRAP_FILE="$DIR/test/tests/bootstrap.php";
REPORT_FILE="$DIR/phpunit-report.xml";
TEST_COMMAND="$PHPUNIT $TESTS_DIR --bootstrap $BOOTSTRAP_FILE --testdox --log-junit $REPORT_FILE";
echo $TEST_COMMAND;
$TEST_COMMAND;
