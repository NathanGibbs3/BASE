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
	$stat = $user->addUser('TestAdmin', '1', 'password', 'TestAdmin');
	print "$stat\n";
	$stat = $user->addUser('TestUser', '10', 'password', 'TestUser');
	print "$stat\n";
	$stat = $user->addUser('TestAGEditor', '50', 'password', 'TestAGEditor');
	print "$stat\n";
	$stat = $user->addUser('TestAnonUser', '10000', 'password', 'TestAnonUser');
	print "$stat\n";
	$stat = $user->addUser('TestDisabledUser', '10000', 'password', 'TestDisabledUser');
	print "$stat\n";
	$uid = $user->returnUserID('TestDisabledUser');
	$user->disableUser($uid);
	$stat = $user->addUser('TestOver', '20000', 'password', 'TestOverUser');
	print "$stat\n";
	$stat = $user->addUser('Test<br/>XSS', '10', 'password', 'Test<br/>XXS in Username');
	print "$stat\n";
	$stat = $role->addRole('30000', 'Test<br/>XSS', 'Test<br/>XXS in Rolename');
	print "$stat\n";
}

?>
