#! /bin/bash

if [ "$TRAVIS" == "true" ] && [ "$CI" == "true" ] && [ "$HAS_JOSH_K_SEAL_OF_APPROVAL" == "true" ]; then
	echo "Running on Travis-CI"
else
	echo "Not Running on Travis-CI"
fi

if [ "$DB" = "postgres" ]; then
	if [ "$TRAVIS" == "true" ]; then
		echo "Creating $DB Database snort"
		psql -c 'DROP DATABASE IF EXISTS snort;' -U postgres
		psql -c 'CREATE DATABASE snort;' -U postgres
		echo "Creating BASE Tables"
		psql -d snort -f sql/create_base_tbls_pgsql.sql
		echo "Adding referential integrity to the database schema"
		psql -d snort -f sql/create_base_tbls_pgsql_extra.sql
	fi
elif [ "$DB" = "mysql" ]; then
	if [ "$TRAVIS" == "true" ]; then
		echo "Creating $DB Database snort"
		mysql -e 'CREATE DATABASE IF NOT EXISTS snort;'
		echo "Creating BASE Tables"
		mysql snort < sql/create_base_tbls_mysql.sql
	fi
else
	echo "Not Setting up Database"
fi
