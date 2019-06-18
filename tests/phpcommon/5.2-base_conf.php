<?php

// Fix for Issue #5 Code Coverage Warnings.
// Travis-CI PHP 5.2 Custom base_conf.php
$BASE_VERSION = '0.0.0 (Joette)';
$BASE_installID = 'Test Runner';
$BASE_path = dirname(__FILE__);
$BASE_urlpath = '';
$archive_dbname   = 'snort_archive';
$avoid_counts = 0;
$base_custom_footer = '';
$base_style = 'base_style.css';
$Use_Auth_System = 1;
$db_connect_method = 1;
$debug_time_mode = 1;
$debug_mode = 0;
$maintain_history = 0;
$refresh_all_pages = 0;
$refresh_stat_page = 1;
$event_cache_auto_update = 0;
$last_num_alerts = 15;
$last_num_ualerts = 15;
$last_num_uports = 15;
$last_num_uaddr = 15;
$freq_num_alerts = 5;
$freq_num_uaddr = 15;
$freq_num_uports = 15;
$main_page_detail = 1;
$resolve_IP = 0;
$stat_page_refresh_time = 180;
$use_sig_list = 0;
$TRAVIS = getenv('TRAVIS');
if ($TRAVIS){ // Running on Travis CI.
	$ADO = getenv('ADODBPATH');
	$DBlib_path = "build/adodb/$ADO";
	$DB = getenv('DB');
	if ($DB == 'mysql' ){
		require('./tests/phpcommon/DB.mysql.php');
	}elseif ($DB == 'postgres' ){
		require('./tests/phpcommon/DB.pgsql.php');
	}
}else{ // Local Testing outside of PHPUnit.
	$DBlib_path = '/usr/share/php/adodb';
	require('../database.php');
	$var = '_BASE_INC';
	if (!defined($var)){
		define($var, 1);
	}
	include("$BASE_path/languages/english.lang.php");
	print "   Testing with: (BASE) $BASE_installID $BASE_VERSION\n";
	print "Testing code in: $BASE_path\n";
}
?>
