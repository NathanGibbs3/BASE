<?php
// Basic Analysis and Security Engine (BASE)
// Copyright (C) 2019-2020 Nathan Gibbs
// Copyright (C) 2004 BASE Project Team
// Copyright (C) 2000 Carnegie Mellon University
//
//   For license info: See the file 'base_main.php'
//
//       Project Lead: Nathan Gibbs
// Built upon work by: Kevin Johnson & the BASE Project Team
//                     Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
//
//            Purpose: Index page for the administrative functions
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

if (!isset($BASE_path)){ // Issue #5
	$BASE_path = dirname(__FILE__);
	$BASE_path = preg_replace("/\/admin.*/", "", $BASE_path);
}
include("$BASE_path/base_conf.php");
include_once("$BASE_path/includes/base_constants.inc.php");
include("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_common.php");
include_once("$BASE_path/base_stat_common.php");

AuthorizedRole(1,'base_main');
$et = new EventTiming($debug_time_mode);
$UIL = new UILang($BASE_Language); // Create UI Language Object.
$cs = new CriteriaState("admin/index.php");
$cs->ReadState();
$page_title = _BASEADMIN;
PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages);
PrintBASEAdminMenuHeader();
print _BASEADMINTEXT;
PrintBASEAdminMenuFooter();
PrintBASESubFooter();
?>
