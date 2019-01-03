<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: Index page for the administrative functions
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
  
  include("../base_conf.php");
  include("$BASE_path/includes/base_constants.inc.php");
  include("$BASE_path/includes/base_include.inc.php");
  include_once("$BASE_path/base_db_common.php");
  include_once("$BASE_path/base_common.php");
  include_once("$BASE_path/base_stat_common.php");

  $et = new EventTiming($debug_time_mode);
  $cs = new CriteriaState("admin/index.php");
  $cs->ReadState();
  
  // Check role out and redirect if needed -- Kevin
  $roleneeded = 1;
  $BUser = new BaseUser();
  if (($BUser->hasRole($roleneeded) == 0) && ($Use_Auth_System == 1)) {
    base_header("Location: ". $BASE_urlpath . "/base_main.php");
  }
    
  $page_title = _BASEADMIN;
    
    PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages);
  
    PrintBASEAdminMenuHeader();
  
    echo _BASEADMINTEXT;

    PrintBASEAdminMenuFooter();

    PrintBASESubFooter();
    echo "</body>\r\n</html>";
?>
