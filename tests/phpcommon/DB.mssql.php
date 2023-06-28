<?php
$alert_user='travis';
$alert_password='';
$alert_dbname='testpig';
$alert_port='1433';
$DBtype='mssql';
$archive_exists   = 0;
$archive_dbname   = '';
$archive_host     = '';
$archive_port     = '';
$archive_user     = '';
$archive_password = '';
$TRAVIS = getenv('TRAVIS');
if( !$TRAVIS ){ // Running on Local Test System.
	$alert_host='db';
	require('../dbbasedev.php');
}else{
	$alert_host='localhost';
}
?>
