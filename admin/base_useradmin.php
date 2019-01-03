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
** Purpose: page for the user functions (create, disable etc....)
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
  $cs = new CriteriaState("admin/base_useradmin.php");
  $cs->ReadState();
  
  // Check role out and redirect if needed -- Kevin
  $roleneeded = 1;
  $BUser = new BaseUser();
  if (($BUser->hasRole($roleneeded) == 0) && ($Use_Auth_System == 1))
    base_header("Location: ". $BASE_urlpath . "/base_main.php");

  $page_title = _USERADMIN;
    
  // I would like to clean this up later into a display class or set of functions -- Kevin
  switch (filterSql($_GET['action']))
  {
    case "create";
      // display the new user form
      $user = new BaseUser();
      $defaultrole = 10;
      $form = "<form action='base_useradmin.php?action=add' Method='POST'>";
      $form = $form . "<table border=1 class='query'>";
      $form = $form . "<tr><td width='25%' align='right'>"._FRMLOGIN."</td>";
      $form = $form . "<td align='left'><input type='text' name='user'></td></tr>";
      $form = $form . "<tr><td width='25%' align='right'>"._FRMFULLNAME."</td>";
      $form = $form . "<td align='left'><input type='text' name='fullname'></td></tr>";
      $form = $form . "<tr><td width='25%' align='right'>"._FRMPWD."</td>";
      $form = $form . "<td align='left'><input type='password' name='password'></td></tr>";
      $form = $form . "<tr><td width='25%' align='right'>"._FRMROLE."</td>";
      $form = $form . "<td align='left'>" . $user->returnRoleNamesDropDown($defaultrole) ."</td></tr>";
      $form = $form . "<tr><td colspan='2' align='center'><input type='submit' name='submit' value='"._SUBMITQUERY."'></td>";
      $form = $form . "</tr></table></form>";
     
      $pagebody = $form;
      break;
      
    case "add";
      // actually add the user to the database
      $username = filterSql($_POST['user']);
      $role = filterSql($_POST['roleID']);
      $password = filterSql($_POST['password']);
      $name = filterSql($_POST['fullname']);
      
      $BUser = new BaseUser();
      $added = $BUser->addUser($username, $role, $password, $name);
      
      $pagebody = $added;
      break;

    case "edituser";
      // edit user form -- Kevin
      // $myrow = array(usr_id, usr_login, role_id, usr_name, usr_enabled)
      $user = new BaseUser();
      $userinfo = $user->returnEditUser(filterSql($_GET['userid']));
      
      $form = "<form action='base_useradmin.php?action=updateuser' Method='POST'>";
      $form = $form . "<input type='hidden' name='usr_id' value='". $userinfo[0] ."'";
      $form = $form . "<table border=1 class='query'>";
      $form = $form . "<tr><td width='25%' align='right'>"._FRMUID."</td>";
      $form = $form . "<td align='left'>". $userinfo[0] ."</td></tr>";
      $form = $form . "<tr><td width='25%' align='right'>"._FRMLOGIN."</td>";
      $form = $form . "<td align='left'>". $userinfo[1] ."</td></tr>";
      $form = $form . "<tr><td width='25%' align='right'>"._FRMFULLNAME."</td>";
      $form = $form . "<td align='left'><input type='text' name='fullname' value='". $userinfo[3] ."'></td></tr>";
      $form = $form . "<tr><td width='25%' align='right'>"._FRMROLE."</td>";
      $form = $form . "<td align='left'>" . $user->returnRoleNamesDropDown($userinfo[2]) ."</td></tr>";
      $form = $form . "<tr><td colspan='2' align='center'><input type='submit' name='submit' value='"._UPDATEUSER."'></td>";
      $form = $form . "</tr></table></form>";
     
      $pagebody = $form;

      break;
    
    case "updateuser";
      // Updates user from above form....
      /* This function accepts an array in the following format
          $userarray[0] = $userid
          $userarray[1] = $fullname
          $userarray[2] = $roleid
        */
      $user = new BaseUser();
      $userarray = array(filterSql($_POST['usr_id']), filterSql($_POST['fullname']), filterSql($_POST['roleID']),);
      $user->updateUser($userarray);
      base_header("Location: base_useradmin.php?action=list");
      break;

    case "disableuser";
      // disable user -- Kevin
      $userid = filterSql($_GET['userid']);
      $BUser = new BaseUser();
      $BUser->disableUser($userid);
      base_header("Location: base_useradmin.php?action=list");
      break;
    
    case "enableuser";
      // enable user -- Kevin
      $userid = filterSql($_GET['userid']);
      $BUser = new BaseUser();
      $BUser->enableUser($userid);
      base_header("Location: base_useradmin.php?action=list");
      break;

    case "deleteuser";
      // Deletes user
      $userid = filterSql($_GET['userid']);
      $BUser = new BaseUser();
      $BUser->deleteUser($userid);
      base_header("Location: base_useradmin.php?action=list");
      break;
    
    case "list";
      // Build table to list users and return it as $usertable
      $user = new BaseUser();
      $users = $user->returnUsers();
      $tmpHTML = "<TABLE CELLSPACING=0 CELLPADDING=2 BORDER=0 WIDTH='100%' BGCOLOR='#000000'><TR><TD>";
      $tmpHTML = $tmpHTML . "<table CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH='100%' BGCOLOR='#FFFFFF'>";
      $tmpHTML = $tmpHTML ."<tr><td CLASS='plfieldhdr' width=25>"._EDIT."</td><td CLASS='plfieldhdr' width=35>&nbsp;"._DELETE."</td><td CLASS='plfieldhdr'>"._ID."</td><td CLASS='plfieldhdr'>"._LOGIN;
      $tmpHTML = $tmpHTML ."</td><td CLASS='plfieldhdr'>"._ROLEID."</td><td CLASS='plfieldhdr'>"._NAME;
      $tmpHTML = $tmpHTML ."</td><td CLASS='plfieldhdr'>"._ENABLED."</td></tr>";
      
      // Verify that we have a user in the db --Kevin
      if ($users <> "")
      {
        foreach ($users as $row)
        {
          //explode array rows and build table
          $tmpRow = explode("|", $row);
          $enabled = ($tmpRow[4] == 1) ? "<a href='base_useradmin.php?action=disableuser&amp;userid=".urlencode($tmpRow[0])."'><img src='".$BASE_urlpath ."/images/greencheck.png' border='0' alt='button_greencheck'></a>" : "<a href='base_useradmin.php?action=enableuser&amp;userid=".urlencode($tmpRow[0])."'><img src='".$BASE_urlpath ."/images/button_exclamation.png' border='0' alt='button_exclamation'>";
          $rolename = $user->roleName($tmpRow[2]);
          $name = ($tmpRow[2] == 1) ? "<font color='#ff000'><b>".htmlspecialchars($tmpRow[1])."</b></font>" : htmlspecialchars($tmpRow[1]);
          
          $tmpHTML = $tmpHTML . "<tr><td align='center'><a href='base_useradmin.php?action=edituser&amp;userid=".urlencode($tmpRow[0])."'>";
          $tmpHTML = $tmpHTML . "<img src='" . $BASE_urlpath ."/images/button_edit.png' border='0' alt='button_edit'></a></td>";
          $tmpHTML = $tmpHTML . "<td align='center'><a href='base_useradmin.php?action=deleteuser&amp;userid=".urlencode($tmpRow[0])."'>";
          $tmpHTML = $tmpHTML . "<img src='" . $BASE_urlpath ."/images/button_delete.png' border='0' alt='button_delete'></a></td>";
          $tmpHTML = $tmpHTML . "<td align='center'>" . htmlspecialchars($tmpRow[0]);
          $tmpHTML = $tmpHTML . "</td><td align='center'>" . htmlspecialchars($name);
          $tmpHTML = $tmpHTML . "</td><td align='center'>" . htmlspecialchars($rolename);
          $tmpHTML = $tmpHTML . "</td><td align='center'>" . htmlspecialchars($tmpRow[3]);
          $tmpHTML = $tmpHTML . "</td><td align='center'>" . $enabled . "</td></tr>";
        }
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
