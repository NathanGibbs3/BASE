<?php

$BASE_path = dirname(__FILE__);
print "$BASE_path\n";
$BASE_path = preg_replace("/\/tests.*/", "", $BASE_path);
print "$BASE_path\n";
define( '_BASE_INC', 1 );
$BASE_installID = '';
$debug_mode = 0;
$sql_trace_mode = 0;
$db_connect_method = 1;

# include("$BASE_path/includes/base_constants.inc.php");
include("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_common.php");

?>