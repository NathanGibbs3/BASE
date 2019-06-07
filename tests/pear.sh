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
	# Pear doesn't work on travis-ci PHP 5.2x
	# Due to openssl not being built into PHP.
	if [ "$pvM" \> "5" ] || ( [ "$pvM" == "5" ] && [ "$pvm" \> "2" ]); then
		# Pear Install
		pear channel-update pear.php.net
		pear install mail Mail_Mime
	else
		# Manual install
		wget -nv http://download.pear.php.net/package/Mail-1.4.1.tgz -O build/Mail.tgz
		wget -nv http://download.pear.php.net/package/Mail_Mime-1.10.2.tgz -O build/Mail_Mime.tgz
		pear install build/Mail.tgz
		pear install build/Mail_Mime.tgz
	fi
fi
