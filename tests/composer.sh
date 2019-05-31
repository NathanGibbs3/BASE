#! /bin/bash

echo -n "Travis-CI Environment: "
if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Yes"
else
	echo "No"
fi

pu=composer
if [ "$TRAVIS" != "true" ]; then
	echo -n "PHP Composer "
	if which $pu > /dev/null; then # Composer present
		echo "installed."
		Composer=2
	else
		echo "not installed."
		# Can we install it?
	fi
fi

if [ "$Composer" = "1" ]; then
	if [ "$TRAVIS" == "true" ]; then # Only install on travis
		curl -s http://getcomposer.org/installer | php
	fi
	px="php $pu.phar"
elif [ "$Composer" = "2" ]; then
	px=$pu
else
	echo "PHP Composer install not supported."
	if [ "$TRAVIS" != "true" ]; then
		Composer=0
	fi
fi

if [ "$Composer" \> "0" ]; then
	puv=`$pu --version|sed -e "s/^Composer version //" -r -e "s/ [0-9]+.*$//"`
	#pvM=`echo $puv|sed -r -e "s/\.[0-9]\.[0-9]+$//"`
	#pvm=`echo $puv|sed -r -e "s/^[0-9]\.//" -e "s/\.[0-9]+$//"`
	#pvr=`echo $puv|sed -r -e "s/^[0-9]\.[0-9]\.//"`
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
		$px install -vvv --no-interaction -dsafe_mode=0
		# Enable Xdebug
		mv ${HOME}/xdebug.ini ${HOME}/.phpenv/versions/$(phpenv version-name)/etc/conf.d/xdebug.ini
	else
		echo "Would run Composer: $px"
	fi
fi
