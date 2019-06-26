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
		pear install mail Mail_Mime Image_Graph-alpha Image_Canvas-alpha Image_Color
	else
		# Manual install
		dc="wget -nv http://download.pear.php.net/package"
		ic="pear install "
		dl=build
		de=.tgz
		$dc/Mail-1.4.1$de -O $dl/Mail$de
		$dc/Mail_Mime-1.10.2$de -O $dl/Mail_Mime$de
		$dc/Image_Graph-0.8.0$de -O $dl/Image_Graph$de
		$dc/Image_Canvas-0.3.5$de -O $dl/Image_Canvas$de
		$dc/Image_Color-1.0.4$de -O $dl/Image_Color$de
		$ic$dl/Mail$de
		$ic$dl/Mail_Mime$de
		$ic$dl/Image_Color$de
		$ic$dl/Image_Canvas$de
		$ic$dl/Image_Graph$de
	fi
fi
