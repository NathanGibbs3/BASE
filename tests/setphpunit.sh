#! /bin/bash

if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Running on Travis-CI"
else
	echo "Not Running on Travis-CI"
fi

pu=phpunit
syspu=`which $pu`
puv=`phpunit --version|sed -e "s/^PHPUnit\s//" -e "s/\sby.*$//"`
pvM=`echo $puv|sed -r -e "s/\.[0-9]\.[0-9]+$//"`
pvm=`echo $puv|sed -r -e "s/^[0-9]\.//" -e "s/\.[0-9]+$//"`
pvr=`echo $puv|sed -r -e "s/^[0-9]\.[0-9]\.//"`

echo "System PHPUnit Version: $puv"
echo "              Location: $syspu"
if [ "$pvM" == "4" ] && [ "$pvm" == "8" ] && [ "$pvr" \< "28" ]; then
	echo "Using Composer PHPUnit"
	pu="vendor/bin/$pu"
	if [ "$TRAVIS" == "true" ]; then
		export ComPU=1
	fi
else
	echo "Using System PHPUnit"
	if [ "$TRAVIS" == "true" ]; then
		export ComPU=0
	fi
fi
if [ "$XDebug" == "1" ]; then
	pux="php -d xdebug.profiler_enable=on $pu"
else
	pux=$pu
fi
echo "PHPUnit Executable: $pux"
if [ "$TRAVIS" == "true" ]; then
	export PUX=$pux
fi