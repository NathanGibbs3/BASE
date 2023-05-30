<?php

$BASE_path = dirname(__FILE__);
$sc = DIRECTORY_SEPARATOR;
$ReqRE =  "\\".$sc.'tests.*';
$BASE_path = preg_replace('/'.$ReqRE.'/', '', $BASE_path);

// Conf File Values.
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
$colored_alerts = 0;
// Red, yellow, orange, gray, white, green
$priority_colors = array ('FF0000','FFFF00','FF9900','999999','FFFFFF','006600');
$archive_exists = 0;
$AllowedClients = '';

session_start();
print "   Testing with: (BASE) $BASE_installID $BASE_VERSION\n";
print "Testing code in: $BASE_path\n";

include ("$BASE_path/tests/phpcommon/tsf.php"); // Test Support Functions.

// BASE Runtime.
include_once("$BASE_path$sc" . "includes$sc" . "base_rtl.php");
SetConst('_BASE_INC', 1); // Include Load Flag.
include_once("$BASE_path$sc" . "base_common.php");
include_once("$BASE_path$sc" . "includes$sc" . "base_auth.inc.php");
include_once("$BASE_path$sc" . "includes$sc" . "base_capabilities.php");
include_once("$BASE_path$sc" . "includes$sc" . "base_constants.inc.php");
include_once("$BASE_path$sc" . "includes$sc" . "base_db.inc.php");
include_once("$BASE_path$sc" . "includes$sc" . "base_log_timing.inc.php");

include("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_qry_common.php");
include_once("$BASE_path/base_stat_common.php");
//$et = new EventTiming($debug_time_mode);
?>
