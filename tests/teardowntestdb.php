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
		$uid = $user->returnUserID('TestAdmin');
		$stat = $user->deleteUser($uid);
		print "$val $stat\n";
		$uid = $user->returnUserID('TestUser');
		$stat = $user->deleteUser($uid);
		print "$val $stat\n";
		$uid = $user->returnUserID('TestAGEditor');
		$stat = $user->deleteUser($uid);
		print "$val $stat\n";
		$uid = $user->returnUserID('TestAnonUser');
		$stat = $user->deleteUser($uid);
		print "$val $stat\n";
		$uid = $user->returnUserID('TestDisabledUser');
		$stat = $user->deleteUser($uid);
		print "$val $stat\n";
		$uid = $user->returnUserID('TestOver');
		$stat = $user->deleteUser($uid);
		print "$val $stat\n";
		$uid = $user->returnUserID('Test<br/>XSS');
		$stat = $user->deleteUser($uid);
		print "$val $stat\n";
		$stat = $role->deleteRole('30000');
		print "$val $stat\n";
	}
	$alert_dbname = $OADB;
}

?>
