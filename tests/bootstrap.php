<?php

$BASE_path = dirname(__FILE__);
$BASE_path = str_replace("tests", "", $BASE_path);
define( '_BASE_INC', 1 );

include("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_common.php");

?>