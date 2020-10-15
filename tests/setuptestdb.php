<?php

$BASE_path = dirname(__FILE__);
$BASE_path = preg_replace("/\/tests.*/", "", $BASE_path);
define( '_BASE_INC', 1 );
$BASE_VERSION = '0.0.0 (Joette)';
$BASE_installID = 'DB Setup';

// BASE Runtime
include("$BASE_path/includes/base_constants.inc.php");
include("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_common.php");
// Language
include_once("$BASE_path/languages/english.lang.php");

// Setup DB System.
$TRAVIS = getenv('TRAVIS');
if (!$TRAVIS){ // Running on Local Test System.
	// Default Debian/Ubuntu location.
	$DBlib_path = '/usr/share/php/adodb';
	require('../database.php');
}else{
	$ADO = getenv('ADODBPATH');
	if (!$ADO) {
		print "Unable to setup ADODB\n";
	}else{
		$DBlib_path = "build/adodb/$ADO";
	}
	$DB = getenv('DB');
	if (!$DB){
		print "Unable to get DB Engine.\n";
	}elseif ($DB == 'mysql' ){
		require('./tests/phpcommon/DB.mysql.php');
	}elseif ($DB == 'postgres' ){
		require('./tests/phpcommon/DB.pgsql.php');
	}else{
		print "CI Support unavialable for DB: $DB.\n";
	}
}
if (!isset($DBtype)){
	print "Unable to Set DB: $DB.\n";
}else{
	$alert_dbname='snort';
	// Create Test User Set
	$user = new BaseUser();
	$stat = $user->addUser('TestAdmin', '1', 'password', 'TestAdmin');
	print "$stat\n";
	$stat = $user->addUser('TestUser', '10', 'password', 'TestUser');
	print "$stat\n";
	$stat = $user->addUser('TestAGEditor', '50', 'password', 'TestAGEditor');
	print "$stat\n";
	$stat = $user->addUser('TestAnonUser', '10000', 'password', 'TestAnonUser');
	print "$stat\n";
	$stat = $user->addUser('TestOver', '20000', 'password', 'TestOverUser');
	print "$stat\n";
}

?>
