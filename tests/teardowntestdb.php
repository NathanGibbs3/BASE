<?php

$BASE_VERSION = '0.0.0 (Joette)';
$BASE_installID = 'DB Setup';
$BASE_path = dirname(__FILE__);
$sc = DIRECTORY_SEPARATOR;
$ReqRE =  "\\".$sc.'tests.*';
$BASE_path = preg_replace('/'.$ReqRE.'/', '', $BASE_path);

// BASE Runtime.
include_once("$BASE_path$sc" . "includes$sc" . "base_rtl.php");
SetConst('_BASE_INC', 1); // Include Load Flag.
include_once("$BASE_path$sc" . "base_common.php");
include_once("$BASE_path$sc" . "includes$sc" . "base_auth.inc.php");
include("$BASE_path/includes/base_include.inc.php");
// Language
include_once("$BASE_path/languages/english.lang.php");

// Setup DB System.
$TRAVIS = getenv('TRAVIS');
if (!$TRAVIS){ // Running on Local Test System.
	// Default Debian/Ubuntu location.
	$DBlib_path = '/usr/share/php/adodb';
	require("$BASE_path/../database.php");
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
	$role = new BaseRole();
	$uid = $user->returnUserID('TestAdmin');
	$stat = $user->deleteUser($uid);
	print "$stat\n";
	$uid = $user->returnUserID('TestUser');
	$stat = $user->deleteUser($uid);
	print "$stat\n";
	$uid = $user->returnUserID('TestAGEditor');
	$stat = $user->deleteUser($uid);
	print "$stat\n";
	$uid = $user->returnUserID('TestAnonUser');
	$stat = $user->deleteUser($uid);
	print "$stat\n";
	$uid = $user->returnUserID('TestDisabledUser');
	$stat = $user->deleteUser($uid);
	print "$stat\n";
	$uid = $user->returnUserID('TestOver');
	$stat = $user->deleteUser($uid);
	print "$stat\n";
	$uid = $user->returnUserID('Test<br/>XSS');
	$stat = $user->deleteUser($uid);
	print "$stat\n";
	$stat = $role->deleteRole('30000');
	print "$stat\n";
}

?>
