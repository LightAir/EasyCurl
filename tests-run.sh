#!/bin/sh

php -S localhost:3351 -t testing/srv > /dev/null 2>&1 &
php_pid=$!
export CURL_TEST_SERVER_RUNNING=1

vendor/bin/phpunit $@  --coverage-clover=coverage.xml
ret=$?

if [ $CURL_TEST_SERVER_RUNNING ]; then
	kill $php_pid
fi

exit $ret