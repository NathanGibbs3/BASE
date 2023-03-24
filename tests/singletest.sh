#! /bin/bash

echo -n "Travis-CI Environment: "
if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Yes"
else
	echo "No"
fi
if  [ "$1" == "" ] ; then
	echo "Test not specified."
	echo "Exiting."
	exit
fi
if [ "$TRAVIS" == "true" ]; then
	echo "Not Running in Local test environment."
	echo "Exiting."
	exit
else
	td=`pwd|sed -e "s/^.*\///"`
	if [ "$td" == "tests" ]; then
		td="."
	else
		td="./tests"
	fi
	pu=phpunit
	ph=php
	if [ "$SafeMode" == "1" ] && [ "$Composer" == "1" ]; then
		ph="$ph -dsafe_mode=0"
		px="$ph vendor/bin/$pu"
	else
		px=$pu
	fi
	puv=`$px --version|sed -e "s/^PHPUnit\s//" -e "s/\sby.*$//"`
	pvM=`echo $puv|sed -r -e "s/\.[0-9]\.[0-9]+$//"`
	pvm=`echo $puv|sed -r -e "s/^[0-9]\.//" -e "s/\.[0-9]+$//"`
	pvr=`echo $puv|sed -r -e "s/^[0-9]\.[0-9]\.//"`
	echo "PHPUnit Version: $puv"
	echo "              Location: `which $pu`"
	px=$pu
	# What PHPUnit Version are we using?
	puv=`$px --version|sed -e "s/^PHPUnit\s//" -e "s/\sby.*$//"`
	echo "Will test with PHPUnit Version: $puv.";
	$ph ./tests/cptgenerate $puv # Generate PHPUnit Tests
	$px --bootstrap $td/bootstrap.php $td/php/$1
fi