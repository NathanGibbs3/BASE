#! /bin/bash

if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Running on Travis-CI."
else
	echo "Not Running on Travis-CI."
fi

pu=phpunit
echo -n "PHPUnit "
if [ "$TRAVIS" != "true" ]; then
	if which $pu > /dev/null; then # PHPUnit present
		echo "installed."
	else
		echo "not installed."
		echo "Exiting."
		exit
	fi
fi
puv=`$pu --version|sed -e "s/^PHPUnit\s//" -e "s/\sby.*$//"`
pvM=`echo $puv|sed -r -e "s/\.[0-9]\.[0-9]+$//"`
pvm=`echo $puv|sed -r -e "s/^[0-9]\.//" -e "s/\.[0-9]+$//"`
pvr=`echo $puv|sed -r -e "s/^[0-9]\.[0-9]\.//"`
echo "System PHPUnit Version: $puv"
if [ "$TRAVIS" != "true" ]; then
	echo "              Location: `which $pu`"
fi
#if [ "$pvM" == "4" ] && [ "$pvm" == "8" ] && [ "$pvr" \< "28" ]; then
#	echo "Using Composer PHPUnit."
#	px="vendor/bin/$pu"
#else
#	echo "Using System PHPUnit."
	px=$pu
#fi
$px --version

# Generate PHPUnit Tests
php -f ./tests/phptestgen.php ./tests/php5.3 $puv

echo "Running PHPUnit: $px"
$px -c $pu.xml.dist
