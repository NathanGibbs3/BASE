#! /bin/bash

echo -n "Travis-CI Environment: "
if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Yes"
else
	echo "No"
fi

td=`pwd|sed -e "s/^.*\///"`
pv=phpver
if [ "$td" == "tests" ]; then
	pv="./$pv"
else
	pv="./tests/$pv"
fi
puv=`$pv`
pvM=`echo $puv|sed -r -e "s/\.[0-9]\.[0-9]+.*$//"`
pvm=`echo $puv|sed -r -e "s/^[0-9]\.//" -e "s/\.[0-9]+?.*$//"`
pvr=`echo $puv|sed -r -e "s/^[0-9]\.[0-9]\.//"`
echo "System PHP Version: $puv"
pear -V
peip=`pear config-get php_dir`
phip=`php -r 'print get_include_path()."\n";'`
echo "PEAR install path: $peip"
echo " PHP include path: $phip"

if [ "$TRAVIS" == "true" ]; then
	# Don't update channel on travis-ci PHP 5.2x
	if [ "$pvM" \< "5" ] || ( [ "$pvM" == "5" ] && [ "$pvm" \< "3" ]); then
		pear channel-update pear.php.net
	fi
	pear install mail Mail_Mime
fi
