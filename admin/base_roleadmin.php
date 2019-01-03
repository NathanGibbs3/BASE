<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: page for the role admin functions (create, disable etc....)
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
  $cs = new CriteriaState("admin/base_roleadmin.php");
  $cs->ReadState();
  
  // Check role out and redirect if needed -- Kevin
  $roleneeded = 1;
  $BUser = new BaseUser();
  if (($BUser->hasRole($roleneeded) == 0) && ($Use_Auth_System == 1))
    base_header("Location: ". $BASE_urlpath . "/base_main.php");

  $page_title = _ROLEADMIN;
    
  // I would like to clean this up later into a display class or set of functions -- Kevin
  switch ($_GET['action'])
  {
    case "create";
      // display the new Role form
      $user = new BaseRole();
      $form = "<form action='base_roleadmin.php?action=add' Method='POST'>";
      $form = $form . "<table border=1 class='query'>";
      $form = $form . "<tr><td width='25%' align='right'>"._FRMROLEID."</td>";
      $form = $form . "<td align='left'><input type='text' name='roleid'></td></tr>";
      $form = $form . "<tr><td width='25%' align='right'>"._FRMROLENAME."</td>";
      $form = $form . "<td align='left'><input type='text' name='rolename'></td></tr>";
      $form = $form . "<tr><td width='25%' align='right'>"._FRMROLEDESC."</td>";
      $form = $form . "<td align='left'><input type='text' name='desc'></td></tr>";
      $form = $form . "<tr><td colspan='2' align='center'><input type='submit' name='submit' value='"._SUBMITQUERY."'></td>";
      $form = $form . "</tr></table></form>";
     
      $pagebody = $form;
      break;
      
    case "add";
      // actually add the user to the database
      $roleid = filterSql($_POST['roleid']);
      $rolename = filterSql($_POST['rolename']);
      $desc = filterSql($_POST['desc']);
      
      $BRole = new BaseRole();
      $added = $BRole->addRole($roleid, $rolename, $desc);
      
      $pagebody = $added;
      break;

    case "editrole";
      // edit role form -- Kevin
      // $myrow = array(role_id, |role_name, |role_desc)
      $role = new BaseRole();
      $roleinfo = $role->returnEditRole(filterSql($_GET['roleid']));
      
      $form = "<form action='base_roleadmin.php?action=updaterole' Method='POST'>";
      $form = $form . "<input type='hidden' name='role_id' value='". $roleinfo[0] ."'";
      $form = $form . "<table border=1 class='query'>";
      $form = $form . "<tr><td width='25%' align='right'>"._FRMROLEID."</td>";
      $form = $form . "<td align='left'>". $roleinfo[0] ."</td></tr>";
      $form = $form . "<tr><td width='25%' align='right'>"._FRMROLENAME."</td>";
      $form = $form . "<td align='left'><input type='text' name='role_name' value='". $roleinfo[1] ."'></td></tr>";
      $form = $form . "<tr><td width='25%' align='right'>"._FRMROLEDESC."</td>";
      $form = $form . "<td align='left'><input type='text' name='desc' value='". $roleinfo[2] ."'></td></tr>";
      $form = $form . "<tr><td colspan='2' align='center'><input type='submit' name='submit' value='"._UPDATEROLE."'></td>";
      $form = $form . "</tr></table></form>";
     
      $pagebody = $form;

      break;
    
    case "updaterole";
      // Updates role from above form....
      $role = new BaseRole();
      $rolearray = array(filterSql($_POST['role_id']), filterSql($_POST['role_name']), filterSql($_POST['desc']),);
      $role->updateRole($rolearray);
      base_header("Location: base_roleadmin.php?action=list");
      break;

    case "deleterole";
      // Deletes role
      $roleid = filterSql($_GET['roleid']);
      $BRole = new BaseRole();
      $BRole->deleteRole($roleid);
      base_header("Location: base_roleadmin.php?action=list");
      break;
    
    case "list";
      // lists the roles
      // Build table to list roles and return it as $roletable
      $role = new BaseRole();
      $roles = $role->returnRoles();
      $tmpHTML = "<TABLE CELLSPACING=0 CELLPADDING=2 BORDER=0 WIDTH='100%' BGCOLOR='#000000'><TR><TD>";
      $tmpHTML = $tmpHTML . "<table CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH='100%' BGCOLOR='#FFFFFF'>";
      $tmpHTML = $tmpHTML ."<tr><td CLASS='plfieldhdr' width=25>"._EDIT."</td><td CLASS='plfieldhdr' width=35>&nbsp;"._DELETE."</td><td CLASS='plfieldhdr'>"._ID."</td><td CLASS='plfieldhdr'>"._NAME;
      $tmpHTML = $tmpHTML ."</td><td CLASS='plfieldhdr'>"._DESC."</td></tr>";
      foreach ($roles as $row)
      {
        //explode array rows and build table
        $tmpRow = explode("|", $row);
        
        $tmpHTML = $tmpHTML . "<tr><td align='center'><a href='base_roleadmin.php?action=editrole&amp;roleid=".urlencode($tmpRow[0])."'>";
        $tmpHTML = $tmpHTML . "<img src='" . $BASE_urlpath ."/images/button_edit.png' border='0' alt='button_edit'></a></td>";
        $tmpHTML = $tmpHTML . "<td align='center'><a href='base_roleadmin.php?action=deleterole&amp;roleid=".urlencode($tmpRow[0])."'>";
        $tmpHTML = $tmpHTML . "<img src='" . $BASE_urlpath ."/images/button_delete.png' border='0' alt='button_delete'></a></td>";
        $tmpHTML = $tmpHTML . "<td align='center'>" . htmlspecialchars($tmpRow[0]);
        $tmpHTML = $tmpHTML . "</td><td align='center'>" . htmlspecialchars($tmpRow[1]);
        $tmpHTML = $tmpHTML . "</td><td align='center'>" . htmlspecialchars($tmpRow[2]);
        $tmpHTML = $tmpHTML . "</td></tr>";
      }
      // Footer of table
      $tmpHTML = $tmpHTML . "</table></td></tr></table>";
      
      $pagebody = $tmpHTML;
      break;
  }
    // Start the output to the page.....
    PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages);

    PrintBASEAdminMenuHeader();

        echo($pagebody);
    PrintBASEAdminMenuFooter();
    
    PrintBASESubFooter();
    echo "</body>\r\n</html>";
?>
