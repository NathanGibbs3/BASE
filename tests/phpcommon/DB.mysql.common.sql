\! echo "Creating SNORT Tables.";
SOURCE sql/create_snort_tbls_mysql.sql;
\! echo "Creating BASE Tables.";
SOURCE sql/create_base_tbls_mysql.sql;
\! echo "Adding referential integrity to the database schema.";
# This will run on tables using the MyISAM storage engine, but will do nothing.
SOURCE sql/enable_RI.sql;
