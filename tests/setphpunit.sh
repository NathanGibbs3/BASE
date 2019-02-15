#! /bin/bash

if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Running on Travis-CI"
else
	echo "Not Running on Travis-CI"
fi

puv=`phpunit --version|sed -e "s/^PHPUnit\s//" -e "s/\sby.*$//"`
pvM=`echo $puv|sed -r -e "s/\.[0-9]\.[0-9]+$//"`
pvm=`echo $puv|sed -r -e "s/^[0-9]\.//" -e "s/\.[0-9]+$//"`
pvr=`echo $puv|sed -r -e "s/^[0-9]\.[0-9]\.//"`

echo "System PHPUnit Version: $puv"
if [ "$pvM" == "4" ] && [ "$pvm" == "8" ] && [ "$pvr" < "28" ]; then
	echo "Using Composer PHPUnit"
	if [ "$TRAVIS" == "true" ]; then
		export ComPU=1
	fi
else
	echo "Using System PHPUnit"
	if [ "$TRAVIS" == "true" ]; then
		export ComPU=0
	fi
fi
