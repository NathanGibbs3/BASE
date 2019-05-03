#! /bin/bash

echo -n "Travis-CI Environment: "
if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Yes"
else
	echo "No"
fi

td=`pwd|sed -e "s/^.*\///"`
OM=/usr/share/ieee-data/oui.txt
PF=base_mac_prefixes.map

if [ ! -f $OM ]; then
	echo "No System Mac Prefix DB."
	if [ "$td" == "tests" ]; then
		OM=./oui.txt
	elif [ "$td" == "BASE" ]; then
		OM=./tests/oui.txt
	else
		echo "Not Running in Local test environment."
		echo "Exiting."
		exit
	fi
	echo "Pulling DB from: http://standards-oui.ieee.org"
	wget -nv http://standards-oui.ieee.org/oui/oui.txt
	echo "Using Local Mac Prefix DB."
else
	echo "Using System Mac Prefix DB."
fi

if [ "$td" == "tests" ]; then
	OF="./bmpm"
elif [ "$td" == "BASE" ]; then
	if [ "$TRAVIS" != "true" ]; then
		OF="./tests/bmpm"
	else
		OF="./$PF"
	fi
else
	echo "Not Running in Local test environment."
	echo "Exiting."
	exit
fi

if [ -a $OM ]; then
	# Sorted MAC List
	cat $OM | egrep "^[[:xdigit:]]{6} {5}\(base 16\)\s{2}"|sort > "$OF.tmp0"
	# Global sed transforms
	cat "$OF.tmp0" |sed -r -e "s/\s+\(base 16\)\s+/  /" -e "s/( +)?\r$//" -e "s/ +\././" > "$OF.tmp1"
	# Initial Upper Case Vendor Names
	cat "$OF.tmp1" |sed -r -e "s/\b[A-Z]{2,}/\L\0/g" -e "s/\b./\u\0/g" > "$OF.tmp0"
	# Standardize Abbreviations
	cat "$OF.tmp0" |sed -r -e "s/Gmbh/GmbH/" > "$OF.tmp1"
	# Specific Vendor Name Transforms
	cat "$OF.tmp1" |sed -r -e "s/Cisco Systems,? Inc\.?/Cisco Systems Inc./" > "$OF.tmp0"
	cat "$OF.tmp0" |sed -r -e "s/Hewlett Packard/Hewlett-Packard Company/" > "$OF.tmp1"
	cat "$OF.tmp1" |sed -r -e "s/3COM/3Com/" > "$OF.tmp0"
	if [ "$OF" == "" ]; then
		cat "$OF.tmp0"
	else
		if [ "$TRAVIS" == "true" ]; then
			echo "Rebuilding $PF"
		else
			echo "Writing $OF.txt"
		fi
		cat "$OF.tmp0" > "$OF.txt"
	fi
else
	echo "System Mac Prefix DB does not exist."
fi
