#! /bin/bash

echo -n "Travis-CI Environment: "
if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Yes"
else
	echo "No"
fi

sfx="Generating Code Quality Reports."
if [ "$TRAVIS" != "true" ]; then
	echo $sfx
	td=`pwd|sed -e "s/^.*\///"`
	if [ "$td" == "tests" ]; then
		pfx=".."
	else
		pfx="."
	fi
	cd $pfx
	pcs="phpcs"
	pcr="$pcs -q"
	exclude='build,custom,contrib,vendor,tests'
	rd='./build/logs'
	phpdd -a 5.3 -e $exclude ./ > "$rd/0-phpdd-full"
	$pcr --report-full --report-file="$rd/1-$pcs-full"
	$pcr --report-summary --report-file="$rd/2-$pcs-sum"
	$pcr --report-source --report-file="$rd/3-$pcs-src"
	$pcr --report-gitblame --report-file="$rd/4-$pcs-gbp"
	$pcr -s --report-gitblame --report-file="$rd/5-$pcs-gbs"
	$pcr --report-code --report-file="$rd/6-$pcs-code"
	$pcr -s --report-code --report-file="$rd/7-$pcs-scode"
	if [ "$td" == "tests" ]; then
		cd $td
	fi
else
	echo "Not $sfx"
fi