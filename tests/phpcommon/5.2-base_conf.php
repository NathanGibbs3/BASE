<?php

// Fix for Issue #5 Code Coverage Warnings.
// Travis-CI PHP 5.2 Custom base_conf.php
$BASE_path = dirname(__FILE__);
$debug_mode = 0;
$TRAVIS = getenv('TRAVIS');
if ($TRAVIS){ // Running o
	$ADO = getenv('ADODBPATH');
	$DBlib_path = "build/adodb/$ADO";
	$DB = getenv('DB');
	if ($DB == 'mysql' ){
		require('./tests/phpcommon/DB.mysql.php');
	}elseif ($DB == 'postgres' ){
		require('./tests/phpcommon/DB.pgsql.php');
	}
}
?>
