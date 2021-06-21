#! /bin/bash

echo -n "Travis-CI Environment: "
if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Yes"
else
	echo "No"
fi

if  [ "$1" == "" ]; then
	td=`pwd|sed -e "s/^.*\///"`
	pv=phpver
	if [ "$td" == "tests" ]; then
		pv="./$pv"
	else
		pv="./tests/$pv"
	fi
	if [ "$TRAVIS" != "true" ]; then
		if which php > /dev/null; then
			if [ -a $pv ]; then
				puv=`$pv`
				bail=0
			else
				echo "Not Running in Local test environment."
				bail=1
			fi
		else
			echo "PHP not installed."
			bail=1
		fi
	else
		export COVERALLS_PARALLEL=true
		puv=`php $pv`
		bail=0
	fi
	if [ "$bail" == "1" ]; then
		echo "Exiting."
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
echo -n "PHP XDebug "
# XDebug not enabled on travis-ci PHP 5.2x
if [ "$pvM" \< "5" ] || ( [ "$pvM" == "5" ] && [ "$pvm" \< "3" ]); then
	echo "disabled."
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		# Fix XDebug on travis-ci PHP 5.2x.
		# Solution: Load Custom xdebug.ini from repo
		echo "Enabling PHP XDebug."
#		phpenv config-add tests/phpcommon/5.2-xdebug.ini
		cp ./tests/phpcommon/5.2-xdebug.ini ${HOME}/.phpenv/versions/$(phpenv version-name)/etc/conf.d/xdebug.ini
		echo "Enabling PHP 5.2x Code Coverage fix."
		cp ./tests/phpcommon/5.2-base_conf.php ./base_conf.php
		cp ./tests/phpcommon/5.2-phpunit.xml.dist ./phpunit.xml.dist
	fi
else
	echo "enabled."
fi
# PHP Safe Mode not available on PHP 5.4+
# Throws Deprecation errors on 5.3x.
# Breaks unit tests that need process isolation and don't preserve global
# state. See: https://github.com/NathanGibbs3/BASE/issues/36
echo -n "PHP Safe Mode "
if [ "$pvM" == "5" ] && (
	( [ "$pvm" \< "4" ] && [ "$pvm" \> "1" ] ) ||
	( [ "$pvm" == "1" ] && [ "$pvr" \> "4" ] )
); then
	echo "can be enabled."
	SafeMode=1
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		# Enable safe_mode on PHP 5.1.5 to 5.3x
		# Load Custom safe_mode.ini from repo
		echo "Enabling Safe Mode."
		phpenv config-add tests/phpcommon/safe_mode.ini
		export SafeMode=1
	fi
else
	SafeMode=0
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		export SafeMode=0
	fi
	if [ "$pvM" \> "5" ] || ( [ "$pvM" == "5" ] && [ "$pvm" \> "3" ] ); then
		echo "not available."
	else
		echo "disabled."
	fi
fi

if [ "$TRAVIS" != "true" ]; then
	echo -n "PHP Composer "
	if which composer > /dev/null; then # Composer present
		echo "installed."
		Composer=2
		if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
			export Composer=2
		fi
	else
		echo "not installed."
		Composer=0
	fi
fi
echo -n "PHP Composer "
if [ "$Composer" \< "1" ]; then # Can we install it?
	# Composer won't install on PHP < 5.3x or nightly currently PHP 8.x
	if [ "$pvM" \< "5" ] || ( [ "$pvM" == "5" ] && [ "$pvm" \< "3" ] ) || [ "$pvM" \> "7" ]; then
		echo "install not supported."
		Composer=0
		if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
			export Composer=0
		fi
	else
		echo "install supported."
		Composer=1
		if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
			export Composer=1
		fi
	fi
	# Travis Adjustments
	if [ "$TRAVIS" == "true" ]; then
		if [ "$Composer" \> "0" ]; then
			export COMPOSER_MEMORY_LIMIT=2G
			if [ "$SafeMode" == "1" ]; then # Safe mode.
				# Install composer.
				export Composer=1
			else
				# Use system Composer.
				export Composer=2
			fi
		fi
	fi
fi

echo -n "PHP Coveralls "
if [ "$Composer" \< "1" ] || ( [ "$pvM" == "5" ] && [ "$pvm" \< "3" ] ); then
	echo "not supported."
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		export Coveralls=0
	fi
elif [ "$pvM" == "5" ] && ( [ "$pvm" \> "2" ] && [ "$pvm" \< "5" ] ); then
	echo "1x supported."
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		export Coveralls=1
	fi
else
	echo "2x supported."
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		export Coveralls=2
	fi
fi

# Setup ADODB
# GitHub Source Setup
ADOSrc=github.com/ADOdb/ADOdb
ADODl=archive
ADOFilePfx=v
ADOFileSfx=.tar.gz
if [ "$pvM" \> "5" ]; then # PHP 7x
	if [ "$pvm" \> "1" ]; then # PHP 7.2+
		ADODBVer=5.20.12
	else
		ADODBVer=5.20.0
	fi
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		ADODBPATH="ADOdb-$ADODBVer"
	fi
elif [ "$pvM" \> "4" ]; then # PHP 5x
	if [ "$pvm" \> "2" ]; then # PHP 5.3+
		ADODBVer=5.10
	else
		ADODBVer=5.01beta
	fi
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		ADODBPATH="ADOdb-$ADODBVer/phplens/adodb5"
	fi
else # PHP 4x
#	Legacy ADODB
#	If we get the chance, verify Legacy PHP / ADODB version interoperability
#	and update:
#	https://github.com/NathanGibbs3/BASE/wiki/ADOdb-version-requirements-by-PHP-Version
#	ADODBVer=494
#	Sourceforge Source Setup
#	ADOSrc=sourceforge.net/projects/adodb
#	ADODl=files/adodb-php-4-and-5
#	ADOFileSfx=.tgz
#	if [ "$ADODBVer" == "494" ]; then
#		# V 494 weirdness :-)
#		ADOFilePfx="adodb-$ADODBVer-for-php4-and-5/adodb"
#	else
#		Sourceforge standard
#		ADOFilePfx="adodb-$ADODBVer-for-php/adodb"
#	fi
#	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
#		export ADODBPATH="adodb"
#	fi
	ADODBVer=5.01beta
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		ADODBPATH="ADOdb-$ADODBVer/phplens/adodb"
	fi
fi
ADOFile=$ADOFilePfx$ADODBVer$ADOFileSfx
echo "Setup PHP ADODB: $ADODBVer from: https://$ADOSrc"
if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
	mkdir -p build/adodb
	wget -nv https://$ADOSrc/$ADODl/$ADOFile -O build/adodb.tgz
	tar -C build/adodb -zxf build/adodb.tgz
	export ADODBPATH=$ADODBPATH
	RFADODBPATH="build/adodb/$ADODBPATH"
else
	RFADODBPATH='/usr/share/php/adodb'
	echo "Would Download https://$ADOSrc/$ADODl/$ADOFile"
fi
if [ "$1" == "" ]; then
	if [ "$td" != "tests" ]; then
		echo "Current directory: `pwd`"
		echo "Creating Build Log Directory: `pwd`/build/logs"
		mkdir -p build/logs
		echo "Creating custom footer Directory: `pwd`/custom"
		mkdir -p custom/testdir.htm
		touch custom/testext.php
		touch custom/testhtm.htm
		touch custom/testhtml.html
		touch custom/testCASE.HTML
		touch custom/readTestOK.txt
		touch custom/readTestFail.txt
		sudo chown -h nobody:nogroup custom/*
		sudo touch /etc/BASEtestsym.htm
		sudo chown 1000:nogroup /etc/BASEtestsym.htm
		ln -s /etc/BASEtestsym.htm custom/testsym.htm
		ln -s testhtm.htm custom/testsymok.htm
		touch custom/testuser.htm
		sudo chown root:root custom/testuser.htm
		sudo chown root:root custom/readTestFail.txt
		sudo chmod 000 custom/readTestFail.txt
		sudo touch $RFADODBPATH/readTestFail.php
		sudo chown root:root $RFADODBPATH/readTestFail.php
		sudo chmod 000 $RFADODBPATH/readTestFail.php
	fi
	if [ "$TRAVIS" != "true" ]; then
		if [ "$td" == "tests" ]; then
			php ./setuptestdb.php
		else
			php ./tests/setuptestdb.php
		fi
	fi
fi
