#! /bin/bash

TF=`echo $0|sed -e s/\.sh//`.txt
TFN=$TF.new
TFD=../languages
CT1="ranslation files in $TFD containing invalid UTF-8 characters."

if [[ $LANG =~ UTF-8$ ]]; then
	if  [ "$1" == "-s" ]; then
		grep -axvc '.*' $TFD/*|grep -v ":0"|sed -r -e s/^\.\.\\/// -e s/:[0-9]+$// > $TF
		if [ -s $TF ]; then
			echo "T$CT1"
			cat $TF
			echo
			echo "List saved in $TF."
		else
			echo "Empty List not saved."
			rm -f ./$TF
		fi
	elif [ "$1" == "-h" ]; then
		echo "Find t$CT1"
		echo "Usage: $0 [-g -h]"
		echo "    -s Save file list."
		echo "    -n Check for new files."
		echo "    -t Test Run."
		echo "    -h Help"
		echo
		echo "No options:"
		echo "    Print file list."
		echo "    Save file list."
		echo "    Check for new files."
	elif [ "$1" == "-n" ]; then
		grep -axvc '.*' ../languages/*|grep -v ":0"|grep -vf $TF > $TFN
		if [ -s $TFN ]; then
			echo "New t$CT1"
			cat $TFN
		else
			echo "No new t$CT1"
			rm -f ./$TFN
		fi
	elif [ "$1" == "-t" ]; then
		grep -axvc '.*' ../languages/*|grep -v ":0"
	else
		if [ -s $TF ]; then
			echo "T$CT1"
			cat $TF
		else
			echo "Find t$CT1"
			$0 -t
			$0 -s
			$0 -n
		fi
	fi
else
	echo "Cannot run test"
	echo "System locale must support UTF-8"
fi