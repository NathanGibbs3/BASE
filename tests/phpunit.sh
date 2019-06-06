#! /bin/bash

echo -n "Travis-CI Environment: "
if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Yes"
else
	echo "No"
fi

pu=phpunit
ph=php
if [ "$SafeMode" = "1" ]; then
	ph="$ph -dsafe_mode=0"
fi
if [ "$TRAVIS" != "true" ]; then
	if ! which $pu > /dev/null; then # No System PHPUnit
		echo "PHPUnit not installed."
		echo "Exiting."
		exit
	fi
fi
# Use Composer installed or System version?
if [ "$SafeMode" = "1" ]; then
	pi=Composer
	px="$ph vendor/bin/$pu -dsafe_mode=0"
else
	puv=`$pu --version`
	puv=`echo $puv|sed -e "s/^PHPUnit\s//" -e "s/\sby.*$//"`
	pvM=`echo $puv|sed -r -e "s/\.[0-9]\.[0-9]+$//"`
	pvm=`echo $puv|sed -r -e "s/^[0-9]\.//" -e "s/\.[0-9]+$//"`
	pvr=`echo $puv|sed -r -e "s/^[0-9]\.[0-9]\.//"`
	echo "System PHPUnit Version: $puv"
	if [ "$TRAVIS" != "true" ]; then
		echo "              Location: `which $pu`"
	fi
	if [ "$pvM" == "4" ] && [ "$pvm" == "8" ] && [ "$pvr" \< "19" ]; then
		# Apparantly PHPUnits below 4.8.19 fail with a
		# PHP Fatal error: Class 'Text_Template' not found
		# when using expectOutputString in test code.
		# This may be a Debian/Ubuntu specific Issue.
		pi=Composer
		px="$ph vendor/bin/$pu"
	else
		pi=System
		px=$pu
	fi
fi
# Now what PHPUnit Version are we using?
puv=`$px --version|sed -e "s/^PHPUnit\s//" -e "s/\sby.*$//"`
echo "Will test with $pi PHPUnit Version: $puv.";
$ph ./tests/cptgenerate $puv # Generate PHPUnit Tests
$px -c $pu.xml.dist
