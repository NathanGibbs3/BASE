#! /bin/bash

CCR='Code Coverage Report'
CT1="ubmitting $CCR"

echo -n "Travis-CI Environment: "
if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Yes"
	if [ -f ./build/logs/clover.xml ]; then
		echo "S$CT1 to Codecov.io"
		bash <(curl -s https://codecov.io/bash)
		if [ "$Composer" != "0" ]; then
			ph=php
			if [ "$SafeMode" = "1" ]; then
				ph="$ph -dsafe_mode=0"
			fi
			echo -n "S$CT1 to Coverage.io "
			if [ "$Coveralls" == "1" ]; then
				# Coveralls 1.x Support PHP 5.3+
				echo "via Coveralls 1.x."
				$ph ./vendor/bin/coveralls --exclude-no-stmt -v
			elif [ "$Coveralls" == "2" ]; then
				# Coveralls 2.x Support PHP 5.5+
				echo "via Coveralls 2.x."
				$ph ./vendor/bin/php-coveralls --exclude-no-stmt -v
			else
				echo "failed."
				echo "No Coveralls interface available."
				echo "Not S$CT1."
			fi
		fi
	else
		echo "No $CCR Generated."
		echo "Not S$CT1."
	fi
else
	echo "No"
	echo "Not S$CT1."
fi
