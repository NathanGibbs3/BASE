<?php
$alert_user='postgres';
$alert_password='';
$alert_dbname='testpig';
$alert_port='5432';
$DBtype='postgres';
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
