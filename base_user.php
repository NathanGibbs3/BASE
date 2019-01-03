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
** Purpose: User Preferences page
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
  
  include("base_conf.php");
  include("$BASE_path/includes/base_constants.inc.php");
  include("$BASE_path/includes/base_include.inc.php");
  include_once("$BASE_path/base_db_common.php");
  include_once("$BASE_path/base_common.php");
  include_once("$BASE_path/base_stat_common.php");

   // Check role out and redirect if needed -- Kevin
  $roleneeded = 10000;
  $BUser = new BaseUser();
  if (($BUser->hasRole($roleneeded) == 0) && ($Use_Auth_System == 1))
    base_header("Location: ". $BASE_urlpath . "/index.php");

  $page_body="";
  $et = new EventTiming($debug_time_mode);
  $cs = new CriteriaState("base_user.php");
  $cs->ReadState();
  $userprefs = new BaseUserPrefs();
  $userobj = new BaseUser();
  
  $username = $userobj->returnUser();
  
  $page_title = _BASEUSERTITLE;
  PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages);
  if (isset($_GET['action']))
  {
    //This is where the processing of this page happens.
    switch ($_GET['action'])
    {
      case "change":
        //call auth.inc
        if (($_POST['newpasswd1'] == $_POST['newpasswd2']) && ($_POST['newpasswd1'] != ""))
        {
          $pwdresponse = $userobj->changePassword($username, filterSql($_POST['oldpasswd']), filterSql($_POST['newpasswd1']));
          $page_body = $pwdresponse;
          break;
        } else
        {
          $page_body = _BASEUSERERRPWD;
        }
      
      case "changepassword":
        $form = "<form action='base_user.php?action=change' Method='POST'>";
        $form = $form . "<table border=1 class='query'>";
        $form = $form . "<tr><td width='25%' align='right'>"._BASEUSEROLDPWD."</td>";
        $form = $form . "<td align='left'><input type='password' name='oldpasswd'></td></tr>";
        $form = $form . "<tr><td width='25%' align='right'>"._BASEUSERNEWPWD."</td>";
        $form = $form . "<td align='left'><input type='password' name='newpasswd1'></td></tr>";
        $form = $form . "<tr><td width='25%' align='right'>"._BASEUSERNEWPWDAGAIN."</td>";
        $form = $form . "<td align='left'><input type='password' name='newpasswd2'></td></tr>";
        $form = $form . "<tr><td colspan='2' align='center'><input type='submit' name='submit'></td>";
        $form = $form . "</tr></table>";
        $page_body = $page_body . $form;
        break;
      
      case "display":
        $user = new BaseUser();
        $userlogin = $user->returnUser();
        $userid = $user->returnUserID($userlogin);
        $userinfo = $user->returnEditUser($userid);
        $form = "<table border=1 class='query'>";
        $form = $form . "<tr><td width='25%' align='right'>"._FRMUID."</td>";
        $form = $form . "<td align='left'>". $userinfo[0] ."</td></tr>";
        $form = $form . "<tr><td width='25%' align='right'>"._FRMLOGIN."</td>";
        $form = $form . "<td align='left'>". $userinfo[1] ."</td></tr>";
        $form = $form . "<tr><td width='25%' align='right'>"._FRMFULLNAME."</td>";
        $form = $form . "<td align='left'>". $userinfo[3] ."</td></tr>";
        $form = $form . "<tr><td width='25%' align='right'>"._FRMROLE."</td>";
        $form = $form . "<td align='left'>" . $user->roleName($userinfo[2]) ."</td></tr>";
        $form = $form . "</tr></table>";
       
        $page_body = $form;
        break;
        
      default:
        $page_body = $page_body . " ";
    }
    
  }

?>
<!-- Footer menu -->
<table width="100%" border=0><tr><td width="15%" valign="top">
<div class="mainheadermenu" width="50%">
<table border="0" class="mainheadermenu">
    <tr>
        <td class="menuitem">

<a href="base_user.php?action=changepassword" class="menuitem"><?php echo _CHNGPWD; ?></a><br>
<a href="base_user.php?action=display" class="menuitem"><?php echo _DISPLAYU; ?></a><br>

</td>
    </tr>
</table></div>&nbsp;&nbsp;&nbsp;</td><td><?php echo($page_body); ?></td></tr></table>
<?php
    PrintBASESubFooter();
    echo "</body>\r\n</html>";
?>
