#! /bin/bash

if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Running on Travis-CI"
else
	echo "Not Running on Travis-CI"
fi

pu=composer
if which $pu > /dev/null; then # Composer present
	echo "PHP Composer installed"
	if [ "$TRAVIS" != "true" ]; then
		Composer=2
	fi
	px=$pu
	puv=`$pu --version|sed -e "s/^Composer version //" -r -e "s/ [0-9]+.*$//"`
	#pvM=`echo $puv|sed -r -e "s/\.[0-9]\.[0-9]+$//"`
	#pvm=`echo $puv|sed -r -e "s/^[0-9]\.//" -e "s/\.[0-9]+$//"`
	#pvr=`echo $puv|sed -r -e "s/^[0-9]\.[0-9]\.//"`
else # Can we install it?
	echo "PHP Composer not installed"
	if [ "$Composer" = "1" ]; then
		if [ "$TRAVIS" == "true" ]; then # Only install on travis
			curl -s http://getcomposer.org/installer | php
		fi
		px="php $pu.phar"
	elif [ "$Composer" = "2" ]; then
		px=$pu
	else
		echo "PHP Composer install not supported"
		if [ "$TRAVIS" != "true" ]; then
			Composer=0
		fi
	fi
fi

if [ "$Composer" \> "0" ]; then
	if [ "$TRAVIS" == "true" ]; then # Disable XDebug
		mv ${HOME}/.phpenv/versions/$(phpenv version-name)/etc/conf.d/xdebug.ini ${HOME}/xdebug.ini
	fi
	echo "System Composer Version: $puv"
	if [ "$TRAVIS" != "true" ]; then
		echo "               Location: `which $pu`"
	fi
	$px --version
	if [ "$TRAVIS" == "true" ]; then 
		echo "Running Composer: $px"
		$px install --no-interaction
		# Enable Xdebug
		mv ${HOME}/xdebug.ini ${HOME}/.phpenv/versions/$(phpenv version-name)/etc/conf.d/xdebug.ini
	else
		echo "Would run Composer: $px"
	fi
fi
