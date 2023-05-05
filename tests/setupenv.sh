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

# Issue #154
# Export PHP Version Info
export PHVM=$pvM
export PHVm=$pvm
export PHVr=$pvr

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
if [ "$pvM" \> "7" ] || ( [ "$pvM" == "7" ] && [ "$pvm" \> "2" ]); then
	echo "Enabling PHP 7.3+ Code Coverage fix."
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		phpenv config-add tests/phpcommon/xdebug-7.3+.ini
	fi
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
	# Composer won't install on PHP < 5.3x or nightly.
	if [ "$pvM" \< "5" ] || ( [ "$pvM" == "5" ] && [ "$pvm" \< "3" ] ) || [ "$pvM" \> "8" ]; then
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
			if [ "$pvM" == "5" ] && [ "$pvm" == "3" ]; then
				# Update CA Bundle on PHP 5.3x Issue #155
				# On travis-ci the certificates in the installed
				# ca-certificates package have expired.
				dc="wget -nv --no-check-certificate http://curl.se/ca/cacert.pem"
				dl=/usr/local/share/ca-certificates
				sudo $dc -O $dl/new-ca-bundle.crt
				sudo update-ca-certificates -f # Update system ca-certs
			fi
			if [ "$SafeMode" == "1" ]; then # Safe mode, Install composer.
				export Composer=1
			else # Use system Composer.
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
GHMode=release
if [ "$pvM" \> "7" ]; then # PHP 8x
	ADODBVer=5.22.5
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		ADODBPATH="ADOdb-$ADODBVer"
	fi
elif [ "$pvM" \> "5" ]; then # PHP 7x
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
		ADODBVer=5.21.3
	else
		ADODBVer=5.01beta
	fi
	if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
		ADOvM=`echo $ADODBVer|sed -r -e "s/\.[0-9]+.*$//"`
		ADOvm=`echo $ADODBVer|sed -r -e "s/^[0-9]\.//" -e "s/[a-z]+$//"`
		ADOtmp=`echo $ADOvm|sed -r -e "s/\.?[0-9]+$//"`
		if [ "$ADOtmp" != "" ]; then
			ADOvm=$ADOtmp
		fi
		if [ "$ADOvM" == "5" ] && [ $ADOvm \> "18" ]; then
			ADODBPATH="ADOdb-$ADODBVer"
		else
			ADODBPATH="ADOdb-$ADODBVer/phplens/adodb5"
		fi
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
echo -n "Setup PHP ADODB: "
if [ "$GHMode" == "release" ]; then
	echo -n $ADODBVer
	ADOFile=$ADOFilePfx$ADODBVer$ADOFileSfx
	ADODl=archive
else # Branch Mode
	echo -n "branch $GHBranch"
	ADOFile=$GHBranch
	ADODl=tarball
fi
echo " from: https://$ADOSrc"
if [ "$1" == "" ] && [ "$TRAVIS" == "true" ]; then
	echo "Creating Build ADOdb Directory: `pwd`/build/adodb"
	mkdir -p build/adodb
	wget -nv https://$ADOSrc/$ADODl/$ADOFile -O build/adodb.tgz
	tar -C build/adodb -zxf build/adodb.tgz
	if [ "$GHMode" == "branch" ]; then
		ADODBPATH=`ls build/adodb`
	fi
	export ADODBPATH=$ADODBPATH
	RFADODBPATH="build/adodb/$ADODBPATH"
else
	RFADODBPATH='/usr/share/php/adodb'
	echo "Would Download https://$ADOSrc/$ADODl/$ADOFile"
fi
if [ "$1" == "" ]; then
	echo "Current directory: `pwd`"
	if [ "$td" == "tests" ]; then
		pfx=".."
	else
		pfx="."
	fi
	echo "Creating Build Log Directory: `pwd`/$pfx/build/logs"
	mkdir -p $pfx/build/logs
	echo "Creating custom footer Directory: `pwd`/$pfx/custom"
	mkdir -p $pfx/custom/testdir.htm
	touch $pfx/custom/testext.php
	touch $pfx/custom/testhtm.htm
	touch $pfx/custom/testhtml.html
	touch $pfx/custom/testCASE.HTML
	touch $pfx/custom/readTestOK.txt
	touch $pfx/custom/readTestFail.txt
	sudo chown -h nobody:nogroup $pfx/custom/*
	sudo touch /etc/BASEtestsym.htm
	sudo chown 1000:nogroup /etc/BASEtestsym.htm
	ln -s /etc/BASEtestsym.htm $pfx/custom/testsym.htm
	ln -s testhtm.htm $pfx/custom/testsymok.htm
	touch $pfx/custom/testuser.htm
	sudo chown root:root $pfx/custom/testuser.htm
	sudo chown root:root $pfx/custom/readTestFail.txt
	sudo chmod 000 $pfx/custom/readTestFail.txt
	sudo touch $RFADODBPATH/readTestFail.php
	sudo chown root:root $RFADODBPATH/readTestFail.php
	sudo chmod 000 $RFADODBPATH/readTestFail.php
	if [ "$TRAVIS" != "true" ]; then
		if [ "$td" == "tests" ]; then
			php ./setuptestdb.php
		else
			php ./tests/setuptestdb.php
		fi
	fi
fi
