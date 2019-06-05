#! /bin/bash

echo -n "Travis-CI Environment: "
if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Yes"
else
	echo "No"
fi

pu=composer
ph=php
if [ "$SafeMode" = "1" ]; then
	ph="$ph -dsafe_mode=0"
fi
if [ "$TRAVIS" != "true" ]; then
	echo -n "PHP Composer "
	if which $pu > /dev/null; then # Composer present
		echo "installed."
		Composer=2
	else
		echo "not installed." # Can we install it?
	fi
fi

if [ "$Composer" = "1" ]; then
	if [ "$TRAVIS" == "true" ]; then # Only install on travis
		curl -s http://getcomposer.org/installer | $ph
	fi
	px="$ph $pu.phar"
elif [ "$Composer" = "2" ]; then
	px=$pu
else
	echo "PHP Composer install not supported."
	if [ "$TRAVIS" != "true" ]; then
		Composer=0
	fi
fi

if [ "$Composer" \> "0" ]; then
	if [ "$SafeMode" = "1" ]; then
		puv=`$px --version`
	else
		puv=`$pu --version`
	fi
	puv=`echo $puv|sed -e "s/^Composer version //" -r -e "s/ [0-9]+.*$//"`
	#pvM=`echo $puv|sed -r -e "s/\.[0-9]\.[0-9]+$//"`
	#pvm=`echo $puv|sed -r -e "s/^[0-9]\.//" -e "s/\.[0-9]+$//"`
	#pvr=`echo $puv|sed -r -e "s/^[0-9]\.[0-9]\.//"`
	if [ "$TRAVIS" == "true" ]; then # Disable XDebug
		mv ${HOME}/.phpenv/versions/$(phpenv version-name)/etc/conf.d/xdebug.ini ${HOME}/xdebug.ini
	fi
	if [ "$Composer" = "2" ]; then
		echo "System Composer Version: $puv"
		if [ "$TRAVIS" != "true" ]; then
			echo "               Location: `which $pu`"
		fi
	fi
	$px --version
	if [ "$TRAVIS" == "true" ]; then
		echo "Running Composer: $px"
		$px install -vvv --no-interaction
		# Enable Xdebug
		mv ${HOME}/xdebug.ini ${HOME}/.phpenv/versions/$(phpenv version-name)/etc/conf.d/xdebug.ini
	else
		echo "Would run Composer: $px"
	fi
fi
