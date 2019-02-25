#! /bin/bash

td=`pwd|sed -e "s/^.*\///"`

if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Running on Travis-CI"
else
	echo "Not Running on Travis-CI"
fi

if  [ "$1" == "" ]; then
	pv=phpver.php
	if [ "$td" == "tests" ]; then
		pv="./$pv"
	else
		pv="./tests/$pv"
	fi
	if [ -a $pv ]; then
		puv=`php $pv`
	else
		echo "Not Running in Local test environment"
		echo "Exiting"
		exit
	fi
else
	puv=`echo $1|grep "^[0-9]\.[0-9]\.[0-9]"`
	if [ "$puv" == "" ]; then
		echo "Option use enables test mode."
		echo "Environment will not be changed."
		echo
		echo "  Usage: $0 [ PHP version ]"
		echo "Example: $0 5.2.17"
		exit
	fi
fi

pvM=`echo $puv|sed -r -e "s/\.[0-9]\.[0-9]+.*$//"`
pvm=`echo $puv|sed -r -e "s/^[0-9]\.//" -e "s/\.[0-9]+?.*$//"`
pvr=`echo $puv|sed -r -e "s/^[0-9]\.[0-9]\.//"`

echo "System PHP Version: $puv"

# No XDebug on travis-ci PHP 5.2x
if [ "$pvM" \< "5" ] || ( [ "$pvM" == "5" ] && [ "$pvm" \< "3" ]); then
	echo "PHP XDebug disabled"
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		export XDebug=1
	fi
else
	echo "PHP XDebug enabled"
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		export XDebug=0
	fi
fi

# Composer won't install on PHP < 5.3x or nightly currently PHP 8.x
if [ "$pvM" \< "5" ] || ( [ "$pvM" == "5" ] && [ "$pvm" \< "3" ] ) || [ "$pvM" == "8" ]; then
	echo "PHP Composer not supported"
	Composer=0
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		export Composer=0
	fi
else
	echo "PHP Composer supported"
	Composer=1
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		export Composer=1
	fi
fi

if [ "$Composer" == "0" ]; then
	echo "PHP Coveralls not supported"
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		export Coveralls=0
	fi
elif [ "$pvM" == "5" ] && ( [ "$pvm" \> "2" ] && [ "$pvm" \< "5" ] ); then
	echo "PHP Coveralls 1x supported"
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		export Coveralls=1
	fi
else
	echo "PHP Coveralls 2x supported"
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		export Coveralls=2
	fi
fi

if [ "$1" == "" ] && [ "$td" != "tests" ]; then
	echo "Creating Build Log Directory"
	mkdir -p build/logs
fi
