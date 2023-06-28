<?php

$sc = DIRECTORY_SEPARATOR;
$tmp = dirname(__FILE__);
$ReqRE = preg_quote($sc.'phpcommon', '/').'.*';
$tmp = preg_replace('/'.$ReqRE.'/', '', $tmp);
require_once("$tmp$sc" . "phpcommon$sc" . 'base_TEkrnl.php');

$BASE_VERSION = '0.0.0 (Joette)';
$BASE_installID = 'DB Setup';

include("$BASE_path/includes/base_include.inc.php");
// Language
include_once("$BASE_path/languages/english.lang.php");

// Setup DB System.
$TRAVIS = getenv('TRAVIS');
if( !$TRAVIS ){ // Running on Local Test System.
	// Default Debian/Ubuntu location.
	$DBlib_path = '/usr/share/php/adodb';
	$DB = 'mysql';
}else{
	$ADO = getenv('ADODBPATH');
	if( !$ADO ){
		print "Unable to setup ADODB\n";
	}else{
		$DBlib_path = "build/adodb/$ADO";
	}
	$DB = getenv('DB');
}
if( !$DB ){
	print "Unable to get DB Engine.\n";
}elseif( $DB == 'mysql' ){
	$DBT = array('testpig', 'testpig2');
	require('./tests/phpcommon/DB.mysql.RI.php');
}elseif( $DB == 'postgres' ){
	$DBT = array('testpig');
	require('./tests/phpcommon/DB.pgsql.php');
}elseif( $DB == 'mssql' ){
	$DBT = array('testpig');
	require('./tests/phpcommon/DB.mssql.php');
}else{
	self::markTestSkipped("CI Support unavialable for DB: $DB.");
}
if (!isset($DBtype)){
	print "Unable to Set DB: $DB.\n";
}else{
	$OADB = $alert_dbname;
	foreach( $DBT as $val ){
		$alert_dbname = $val;
		// Create Test User Set
		$user = new BaseUser();
		$role = new BaseRole();
		$stat = $user->addUser('TestAdmin', '1', 'password', 'TestAdmin');
		print "$val $stat\n";
		$stat = $user->addUser('TestUser', '10', 'password', 'TestUser');
		print "$val $stat\n";
		$stat = $user->addUser('TestAGEditor', '50', 'password', 'TestAGEditor');
		print "$val $stat\n";
		$stat = $user->addUser('TestAnonUser', '10000', 'password', 'TestAnonUser');
		print "$val $stat\n";
		$stat = $user->addUser('TestDisabledUser', '10000', 'password', 'TestDisabledUser');
		print "$val $stat\n";
		$uid = $user->returnUserID('TestDisabledUser');
		$user->disableUser($uid);
		$stat = $user->addUser('TestOver', '20000', 'password', 'TestOverUser');
		print "$val $stat\n";
		$stat = $user->addUser('Test<br/>XSS', '10', 'password', 'Test<br/>XXS in Username');
		print "$val $stat\n";
		$stat = $role->addRole('30000', 'Test<br/>XSS', 'Test<br/>XXS in Rolename');
		print "$val $stat\n";
	}
	$alert_dbname = $OADB;
}

?>
