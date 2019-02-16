#! /bin/bash

if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Running on Travis-CI"
else
	echo "Not Running on Travis-CI"
fi

puv=`php ./tests/phpver.php`
pvM=`echo $puv|sed -r -e "s/\.[0-9]\.[0-9]+$//"`
pvm=`echo $puv|sed -r -e "s/^[0-9]\.//" -e "s/\.[0-9]+$//"`
pvr=`echo $puv|sed -r -e "s/^[0-9]\.[0-9]\.//"`

echo "System PHP Version: $puv"

if [ "$pvM" == "5" ] && [ "$pvm" == "2" ]; then
	echo "PHP Extension XDebug not installed"
	if [ "$TRAVIS" == "true" ]; then
		export XDebug=1
	fi
else
	echo "PHP Extension XDebug installed"
	if [ "$TRAVIS" == "true" ]; then
		export XDebug=0
	fi
fi

# Composer won't install on PHP nightly currently PHP 8.x
if ( [ "$pvM" == "5" ] && [ "$pvm" == "2" ] ) || [ "$pvM" == "8" ]; then
	echo "PHP Composer not supported"
	if [ "$TRAVIS" == "true" ]; then
		export Composer=0
	fi
else
	echo "PHP Composer supported"
	if [ "$TRAVIS" == "true" ]; then
		export Composer=1
	fi
fi
