#! /bin/bash

# Print PHPUnit version in X.X.X format
# Used for getting this outside of a Test Class.

pu=phpunit
if which $pu > /dev/null; then # PHPUnit present
	puv=`$pu --version|sed -e "s/^PHPUnit\s//" -e "s/\sby.*$//"`
	pvM=`echo $puv|sed -r -e "s/\.[0-9]\.[0-9]+$//"`
	pvm=`echo $puv|sed -r -e "s/^[0-9]\.//" -e "s/\.[0-9]+$//"`
	pvr=`echo $puv|sed -r -e "s/^[0-9]\.[0-9]\.//"`
	echo $puv
else
	echo "0.0.0"
fi

