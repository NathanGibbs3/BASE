#! /bin/bash

if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Running on Travis-CI"
else
	echo "Not Running on Travis-CI"
fi

td=`pwd|sed -e "s/^.*\///"`
OM=/usr/share/ieee-data/oui.txt
PF=base_mac_prefixes.map

	if [ "$td" == "tests" ]; then
		OF="./bmpm.tmp"
	elif [ "$td" == "BASE" ]; then
		OF="./$PF"
	else
		OF=''
	fi

if [ -a $OM ]; then
	if [ "$OF" == "" ]; then
		cat $OM | egrep "^[[:xdigit:]]{6}"|sed -r -e "s/\s+\(base 16\)\s+/  /"|sed -r -e "s/ +?\r$//"|sort|sed -r -e "s/\b[A-Z]{2,}/\L\0/g"|sed -e "s/\b./\u\0/g"
	else
		if [ "$TRAVIS" != "true" ]; then
			OF="./tests/bmpm.tmp"
		else
			echo "Rebuilding $PF"
		fi
		cat $OM | egrep "^[[:xdigit:]]{6}"|sed -r -e "s/\s+\(base 16\)\s+/  /"|sed -r -e "s/ +?\r$//"|sort|sed -r -e "s/\b[A-Z]{2,}/\L\0/g"|sed -e "s/\b./\u\0/g" > $OF
	fi
else
	echo "System Mac Prefix DB does not exist"
fi
