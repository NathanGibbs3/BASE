#! /bin/bash

echo -n "Travis-CI Environment: "
if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Yes"
else
	echo "No"
fi

pu=phpunit
if [ "$TRAVIS" != "true" ]; then
	if ! which $pu > /dev/null; then # No System PHPUnit
		echo "PHPUnit not installed."
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
if [ 1 == 0 ]; then # Use Composer installed or System version?
   pi=Composer
   px="vendor/bin/$pu"
else
   pi=System
   px=$pu
fi
# Now what PHPUnit Version are we using?
puv=`$px --version|sed -e "s/^PHPUnit\s//" -e "s/\sby.*$//"`
echo "Will test with $pi PHPUnit Version: $puv.";
./tests/cptgenerate $puv # Generate PHPUnit Tests
$px -c $pu.xml.dist
