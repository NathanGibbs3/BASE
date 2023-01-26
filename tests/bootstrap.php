<?php

$BASE_path = dirname(__FILE__);
$BASE_path = preg_replace("/\/tests.*/", "", $BASE_path);
define( '_BASE_INC', 1 );
$BASE_VERSION = '0.0.0 (Joette)';
$Use_Auth_System = 1;
$BASE_urlpath = '';
$BASE_installID = 'Test Runner';
$base_style = 'base_style.css';
$db_connect_method = 1;
$html_no_cache = 0;
$maintain_history = 0;
$refresh_stat_page = 1;
$refresh_all_pages = 0;
$stat_page_refresh_time = 180;
$debug_time_mode = 1;
$debug_mode = 0;
$sql_trace_mode = 0;
$event_cache_auto_update = 0;
$last_num_alerts = 15;
// Red, yellow, orange, gray, white, blue
$priority_colors = array ('FF0000','FFFF00','FF9900','999999','FFFFFF','006600');

session_start();
print "   Testing with: (BASE) $BASE_installID $BASE_VERSION\n";
print "Testing code in: $BASE_path\n";

include ("$BASE_path/tests/phpcommon/tsf.php"); // Test Support Functions.
// BASE Runtime
include("$BASE_path/includes/base_constants.inc.php");
include("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_common.php");
include_once("$BASE_path/base_qry_common.php");
include_once("$BASE_path/base_stat_common.php");

?>
