#! /bin/bash

td=`pwd|sed -e "s/^.*\///"`

if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Running on Travis-CI"
	export COVERALLS_PARALLEL=true
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
	if [ "$TRAVIS" != "true" ]; then
		if which php > /dev/null; then
			if [ -a $pv ]; then
				puv=`php $pv`
				bail=0
			else
				echo "Not Running in Local test environment"
				bail=1
			fi
		else
			echo "PHP not installed"
			bail=1
		fi
	else
		puv=`php $pv`
		bail=0
	fi
	if [ "$bail" == "1" ]; then
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

if [ "$TRAVIS" != "true" ]; then
	if which composer > /dev/null; then # Composer present
		echo "PHP Composer installed"
		Composer=2
		if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
			export Composer=2
		fi
	else
		echo "PHP Composer not installed"
		Composer=0
	fi
fi
if [ "$Composer" \< "1" ]; then # Can we install it?
	# Composer won't install on PHP < 5.3x or nightly currently PHP 8.x
	if [ "$pvM" \< "5" ] || ( [ "$pvM" == "5" ] && [ "$pvm" \< "3" ] ) || [ "$pvM" \> "7" ]; then
		echo "PHP Composer install not supported"
		Composer=0
		if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
			export Composer=0
		fi
	else
		echo "PHP Composer install supported"
		Composer=1
		if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
			export Composer=1
		fi
	fi
	# Travis Adjustments
	if [ "$TRAVIS" == "true" ]; then
		if [ "$Composer" \> "0" ]; then
			# If composer enabled use system Composer.
			export Composer=2
		fi
	fi
fi

if [ "$Composer" \< "1" ]; then
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
