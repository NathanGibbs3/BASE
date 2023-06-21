#! /bin/bash

echo -n "Travis-CI Environment: "
if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Yes"
	DBS=localhost
	OPT=""
else
	echo "No"
	# Local test env.
	DBS=db
	if  [ "$1" != "" ]; then
		DB=$1
		read -s -p "DB Password: " PW
	fi
	if [ "$DB" = "postgres" ]; then
		PGPASSWORD="$PW"
	elif [ "$DB" = "mysql" ]; then
		OPT="-p$PW"
	else
		echo "Not Setting up Database."
	fi
fi

DBN=testpig
DBNNRI=testpig2

if [ "$DB" = "postgres" ]; then
	echo "Creating $DB Database $DBN."
	psql -h $DBS -c 'CREATE DATABASE IF NOT EXISTS $DBN;' -U postgres
	echo "Creating SNORT Tables."
	psql -h $DBS -d $DBN -f sql/create_snort_tbls_pgsql.sql
	echo "Creating BASE Tables."
	psql -h $DBS -d $DBN -f sql/create_base_tbls_pgsql.sql
	echo "Adding referential integrity to the database schema."
	psql -h $DBS -d $DBN -f sql/enable_RI.sql
elif [ "$DB" = "mysql" ]; then
	echo "Creating $DB Database $DBN using InnoDB SE."
	mysql -h $DBS -u travis $OPT -e "CREATE DATABASE IF NOT EXISTS $DBN;"
	mysql -h $DBS -u travis $OPT -D $DBN < tests/phpcommon/DB.mysql.InnoDB.sql
	echo "Creating $DB Database $DBNNRI using MyISAM SE."
	mysql -h $DBS -u travis $OPT -e "CREATE DATABASE IF NOT EXISTS $DBNNRI;"
	mysql -h $DBS -u travis $OPT -D $DBNNRI < tests/phpcommon/DB.mysql.MyISAM.sql
else
	echo "Not Setting up Database."
fi
