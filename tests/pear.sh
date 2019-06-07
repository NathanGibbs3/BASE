#! /bin/bash

echo -n "Travis-CI Environment: "
if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Yes"
else
	echo "No"
fi

if [ "$TRAVIS" == "true" ]; then
	pv=phpver
	pear channel-update pear.php.net
	pear install mail Mail_Mime
fi
