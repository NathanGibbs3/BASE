<?php

// Fix for Issue #5 Code Coverage Warnings.
// Travis-CI PHP 5.2 Custom base_conf.php
$BASE_path = dirname(__FILE__);
$BASE_VERSION = '0.0.0 (Joette)';
$BASE_urlpath = '';
$Use_Auth_System = 1;
$debug_time_mode = 1;
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
